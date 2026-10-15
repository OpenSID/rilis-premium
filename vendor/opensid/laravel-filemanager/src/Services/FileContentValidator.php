<?php

namespace OpenSID\LaravelFilemanager\Services;

/**
 * Cross-checks uploaded bytes against the extension they claim to be —
 * FilemanagerConfig::isExtensionAllowed()/isFilenameSafe() only look at the
 * filename, which a webshell renamed to "shell.jpg" satisfies trivially.
 *
 * Coverage:
 * 1. Images — raster signature / getimagesize() sanity + polyglot scan
 *    (valid image concealing PHP/HTML/script in EXIF or trailing bytes).
 * 2. SVG XSS — script/object/foreignObject/event-handler/javascript: etc.
 *    (kept as defence-in-depth even though 'svg' is off the default
 *    whitelist; a host that re-adds it still gets this check).
 * 3. Every other allowed extension — file-signature (magic-byte) match
 *    against KNOWN_SIGNATURES, then a polyglot script scan of the whole
 *    file. An allowed non-image extension with no known signature is
 *    rejected outright: "can't verify" is treated as "don't trust".
 *
 * The polyglot scan reads the entire file (no 4 MB cap) and catches the
 * bare PHP short tag `<?` in addition to `<?php` / `<?=` / `<script`.
 *
 * The `<?=` and bare `<?` markers are only 2-3 literal bytes, which real
 * compressed raster formats (PNG/JPEG/WebP) turn up by pure chance often
 * enough to reject legitimate uploads — every ~1 in 30 real PNGs in
 * testing. Both alternatives therefore additionally require a run of
 * plausible source-code bytes right after the marker (see
 * MIN_CODE_RUN_AFTER_SHORT_TAG), which real injected PHP always has and
 * random compressed binary essentially never does.
 */
class FileContentValidator
{
    /**
     * ext => list of alternatives; each alternative is a list of
     * [byteOffset, bytes] pairs that must ALL be present for that
     * alternative to match. The file is accepted if ANY alternative
     * matches. Extensions that share a container format (OOXML = zip,
     * legacy Office = OLE2, ISO-BMFF = ftyp) share signatures. A negative
     * byteOffset counts back from the end of the file (dmg's trailer).
     */
    protected const KNOWN_SIGNATURES = [
        'pdf' => [[[0, '%PDF-']], [[3, '%PDF-']]], // 2nd form tolerates a leading UTF-8 BOM
        'doc' => [[[0, "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1"]]],
        'xls' => [[[0, "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1"]]],
        'docx' => [[[0, "PK\x03\x04"]], [[0, "PK\x05\x06"]], [[0, "PK\x07\x08"]]],
        'xlsx' => [[[0, "PK\x03\x04"]], [[0, "PK\x05\x06"]], [[0, "PK\x07\x08"]]],
        'zip' => [[[0, "PK\x03\x04"]], [[0, "PK\x05\x06"]], [[0, "PK\x07\x08"]]],
        'rar' => [[[0, "Rar!\x1A\x07\x00"]], [[0, "Rar!\x1A\x07\x01\x00"]]],
        'gz' => [[[0, "\x1F\x8B"]]],
        'tar' => [[[257, 'ustar']]],
        'iso' => [[[32769, 'CD001']], [[32769, 'BEA01']]], // ISO 9660 / UDF volume descriptor
        'dmg' => [[[-512, 'koly']]], // UDIF trailer
        'mp3' => [[[0, 'ID3']], [[0, "\xFF\xFB"]], [[0, "\xFF\xF3"]], [[0, "\xFF\xF2"]], [[0, "\xFF\xFA"]], [[0, "\xFF\xE3"]]],
        'mpga' => [[[0, 'ID3']], [[0, "\xFF\xFB"]], [[0, "\xFF\xF3"]], [[0, "\xFF\xF2"]], [[0, "\xFF\xFA"]], [[0, "\xFF\xE3"]]],
        'm4a' => [[[4, 'ftyp']]],
        'ac3' => [[[0, "\x0B\x77"]]],
        'aiff' => [[[0, 'FORM'], [8, 'AIFF']], [[0, 'FORM'], [8, 'AIFC']]],
        'mid' => [[[0, 'MThd']]],
        'wma' => [[[0, "\x30\x26\xB2\x75\x8E\x66\xCF\x11"]]], // ASF header GUID
        'flv' => [[[0, 'FLV']]],
        'mpeg' => [[[0, "\x00\x00\x01\xBA"]], [[0, "\x00\x00\x01\xB3"]]], // MPEG-PS pack / sequence header
        'mpg' => [[[0, "\x00\x00\x01\xBA"]], [[0, "\x00\x00\x01\xB3"]]],
        'ogg' => [[[0, 'OggS']]],
        'wav' => [[[0, 'RIFF'], [8, 'WAVE']]],
        'avi' => [[[0, 'RIFF'], [8, 'AVI ']]],
        'webm' => [[[0, "\x1A\x45\xDF\xA3"]]],
        'mp4' => [[[4, 'ftyp']]],
        'm4v' => [[[4, 'ftyp']]],
        'mov' => [[[4, 'ftyp']], [[4, 'moov']], [[4, 'free']], [[4, 'mdat']], [[4, 'wide']], [[4, 'skip']]],
    ];

    /**
     * Minimum run of printable/text bytes required right after a bare `<?`
     * or `<?=` marker for it to count as embedded code rather than a chance
     * byte sequence inside compressed binary data.
     */
    protected const MIN_CODE_RUN_AFTER_SHORT_TAG = 10;

    /**
     * Matches an embedded PHP open tag (including the bare short tag) or an
     * HTML/script document structure smuggled inside another file type.
     */
    protected const POLYGLOT_PATTERN = '/(<\s*script|<\?php|<\?=(?=[\x09\x0A\x0D\x20-\x7E]{'.self::MIN_CODE_RUN_AFTER_SHORT_TAG.',})|<\?[\s\r\n](?=[\x09\x0A\x0D\x20-\x7E]{'.self::MIN_CODE_RUN_AFTER_SHORT_TAG.',})|<!doctype\s+html|<\s*html\b|<\s*body\b)/i';

    public function isValidUpload(string $localPath, string $extension): bool
    {
        $extension = mb_strtolower(ltrim($extension, '.'));

        if (in_array($extension, config('filemanager.extensions.image', []), true)) {
            return match ($extension) {
                'svg' => $this->isSafeSvg($localPath),
                // getimagesize() has no reliable .ico decoder across PHP
                // builds; every other raster type must parse as an image.
                'ico' => $this->isSafeRasterImage($localPath, false),
                default => $this->isSafeRasterImage($localPath, true),
            };
        }

        if ($extension === 'svg') {
            return $this->isSafeSvg($localPath);
        }

        // Non-image allowed type: signature must verify, then no polyglot.
        if (! $this->matchesKnownSignature($localPath, $extension)) {
            return false;
        }

        return $this->isFreeOfEmbeddedScript($localPath);
    }

    protected function isSafeSvg(string $localPath): bool
    {
        $contents = @file_get_contents($localPath);

        if ($contents === false || ! str_contains($contents, '<svg')) {
            return false;
        }

        $pattern = '/(<\s*script|<\s*object|<\s*iframe|<\s*embed|<\s*foreignObject|<\s*meta|<\s*link|<\s*form|<\s*base|<\s*applet|<\s*style|<\s*set\b|<\s*animate|<!entity|<!doctype|javascript\s*:|data\s*:\s*text\/html|\b(on[a-zA-Z]+)\s*=|xlink\s*:\s*href|xmlns\s*:\s*script|<\?php|<\?=|<\?[\s\r\n])/i';

        return ! preg_match($pattern, $contents);
    }

    protected function isSafeRasterImage(string $localPath, bool $checkImageSize): bool
    {
        if ($checkImageSize && @getimagesize($localPath) === false) {
            return false;
        }

        return $this->isFreeOfEmbeddedScript($localPath);
    }

    /**
     * True when the file does not contain a PHP open tag or HTML/script
     * document structure anywhere in its bytes.
     */
    protected function isFreeOfEmbeddedScript(string $localPath): bool
    {
        $contents = @file_get_contents($localPath);

        if ($contents === false) {
            return false;
        }

        return ! preg_match(self::POLYGLOT_PATTERN, $contents);
    }

    /**
     * True when either the extension has no signature we know how to check
     * AND it is an image (handled elsewhere), or the file's leading bytes
     * match one of the known signatures for that extension. A non-image
     * allowed extension absent from KNOWN_SIGNATURES returns false — it
     * cannot be verified, so it is not accepted.
     */
    protected function matchesKnownSignature(string $localPath, string $extension): bool
    {
        $signatures = self::KNOWN_SIGNATURES[$extension] ?? null;

        if ($signatures === null) {
            return false;
        }

        $handle = @fopen($localPath, 'rb');
        if ($handle === false) {
            return false;
        }

        try {
            foreach ($signatures as $alternative) {
                $allPresent = true;

                foreach ($alternative as [$offset, $needle]) {
                    if ($this->readAt($handle, $offset, strlen($needle)) !== $needle) {
                        $allPresent = false;
                        break;
                    }
                }

                if ($allPresent) {
                    return true;
                }
            }

            return false;
        } finally {
            fclose($handle);
        }
    }

    /**
     * Reads $length bytes at $offset (negative = from the end of the file),
     * or null when the file is too short to hold them.
     *
     * @param  resource  $handle
     */
    protected function readAt($handle, int $offset, int $length): ?string
    {
        $seeked = $offset < 0 ? fseek($handle, $offset, SEEK_END) : fseek($handle, $offset);

        if ($seeked !== 0) {
            return null;
        }

        $bytes = fread($handle, $length);

        return $bytes === false ? null : $bytes;
    }
}
