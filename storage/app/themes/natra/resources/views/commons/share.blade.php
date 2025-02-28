@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="btn-group" role="group" aria-label="Bagikan ke teman anda" style="clear:both;">
    <a name="fb_share" href="http://www.facebook.com/sharer.php?u={{ $link }}" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Facebook'><button type="button"
            class="btn btn-primary btn-sm"
        ><i class="fa fa-facebook-square fa-2x"></i></button></a>
    <a href="http://twitter.com/share?source=sharethiscom&text={{ $judul }}%0A&url={{ $link }}&via=ariandii" class="twitter-share-button" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer'
        target='_blank' title='Twitter'
    ><button type="button" class="btn btn-info btn-sm"><i class="fa fa-twitter fa-2x"></i></button></a>
    <a href="mailto:?subject={{ $judul }}&body={{ potong_teks($single_artikel['isi'], 1000) }} ... Selengkapnya di {{ $link }}" title='Email'><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-envelope fa-2x"></i></button></a>
    <a href="https://telegram.me/share/url?url={{ $link }}&text={{ $judul }}%0A" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Telegram'><button type="button"
            class="btn btn-dark btn-sm"
        ><i class="fa fa-telegram fa-2x"></i></button></a>
    <a href="#" onclick="printDiv('printableArea')" title='Cetak Artikel'><button type="button" class="btn btn-warning btn-sm"><i class="fa fa-print fa-2x"></i></button></a>
    <a href="https://api.whatsapp.com/send?text={{ $judul }}%0A{{ $link }}" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Whatsapp'><button type="button"
            class="btn btn-success btn-sm"
        ><i class="fa fa-whatsapp fa-2x"></i></button></a>
</div>
