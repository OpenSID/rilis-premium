@php $lvSinergi = collect(function_exists('sinergi_program') ? sinergi_program() : [])->filter(static fn ($s) => filled($s['gambar_url'] ?? null) && ! str_contains($s['gambar_url'], '404-image-not-found')); @endphp
{{-- gambar_url berisi placeholder 404 bila file logo tidak ada (SinergiProgram::getGambarUrlAttribute) --}}

@if ($lvSinergi->isNotEmpty())
    <section class="lv-wrap" style="padding-bottom: 88px" aria-labelledby="lv-sinergi-judul">
        <h2 id="lv-sinergi-judul" style="font-size: 15px; font-weight: 600; color: var(--muted); text-align: center; margin-bottom: 18px">Sinergi Program</h2>
        <div class="lv-partners">
            @foreach ($lvSinergi as $program)
                <a href="{{ $program['tautan'] ?: '#' }}" target="_blank" rel="noopener noreferrer" title="{{ $program['judul'] }}">
                    <img src="{{ $program['gambar_url'] }}" alt="{{ $program['judul'] }}" loading="lazy">
                </a>
            @endforeach
        </div>
    </section>
@endif
