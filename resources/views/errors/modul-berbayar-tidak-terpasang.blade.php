<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isAdmin ? 'Fitur Add-on Belum Terpasang' : 'Layanan Belum Tersedia' }}</title>
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/AdminLTE.css">
</head>
<body>
<div class="container">
    <div class="error-page">
        <h2 class="headline text-yellow">404</h2>

        <div class="error-content">
            @if ($isAdmin)
                <h3><i class="fa fa-warning text-yellow"></i> Fitur Add-on Belum Terpasang</h3>
                <p>
                    Halaman yang Anda tuju adalah bagian dari fitur tambahan (add-on)
                    <strong>{{ $nama }}</strong>, yang belum terpasang di situs ini.
                    @if ($lisensi === 'anjungan')
                        Fitur ini merupakan bagian dari <strong>Lisensi Anjungan</strong> &mdash;
                        lisensi terpisah dari langganan Premium standar.
                    @else
                        Fitur ini termasuk dalam <strong>langganan Premium</strong> Anda, namun
                        belum dipasang di situs ini.
                    @endif
                </p>
                <p>
                    Untuk memasang atau mengaktifkannya, buka menu <strong>Paket Tambahan</strong>
                    dan cari <strong>{{ $nama }}</strong> di katalog.
                </p>
                <p>
                    <a href="{{ url('plugin') }}" class="btn btn-primary">Buka Paket Tambahan</a>
                    <a href="{{ url('/') }}">Kembali ke Beranda</a>
                </p>
            @else
                <h3><i class="fa fa-warning text-yellow"></i> Layanan Belum Tersedia</h3>
                <p>
                    Layanan <strong>{{ $nama }}</strong> yang Anda tuju merupakan fitur tambahan
                    yang perlu diaktifkan oleh admin desa, dan saat ini belum tersedia di situs ini.
                </p>
                <p>Silakan hubungi kantor/admin desa untuk informasi lebih lanjut.</p>
                <p><a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a></p>
            @endif
        </div>
    </div>
</div>
</body>
</html>
