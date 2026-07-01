<a href="https://www.karriere.at/" target="_blank"><img width="200" src="https://raw.githubusercontent.com/karriereat/.github/main/profile/logo.svg"></a>
<span>&nbsp;&nbsp;&nbsp;</span>
![](https://github.com/karriereat/pdf-merge/workflows/test/badge.svg)
![](https://github.com/karriereat/pdf-merge/workflows/lint/badge.svg)

# Pdf Merge Solution for PHP

This package is a wrapper for the `TCPDF` class that provides an elegant API for merging PDF files.

## Installation

You can install the package via composer:

```bash
composer require karriere/pdf-merge
```

## Usage

```php
use Karriere\PdfMerge\PdfMerge;

$pdfMerge = new PdfMerge();

$pdfMerge->add('/path/to/file1.pdf');
$pdfMerge->add('/path/to/file2.pdf');

$pdfMerge->merge('/path/to/output.pdf');
```

Please note, that the `merge()`-method will throw a `NoFilesDefinedException` if no files where added.

### Check for file existence
You can check if a file was already added for merging by calling:

```php
$pdfMerge->contains('/path/to/file.pdf');
```

## License

Apache License 2.0 Please see [LICENSE](LICENSE) for more information.
