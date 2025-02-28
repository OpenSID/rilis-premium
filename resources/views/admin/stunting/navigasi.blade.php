<div class="col-md-3 col-lg-3">
    <div class="box box-info">
        <div class="box-body no-padding">
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li class="@active($navigasi === 'posyandu')"><a href="{{ ci_route('stunting') }}">Posyandu</a></li>
                </ul>
                <ul class="nav nav-stacked">
                    <li class="@active($navigasi === 'kia')"><a href="{{ ci_route('stunting.kia') }}">Kesehatan Ibu dan Anak (KIA)</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Pemantauan</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body no-padding">
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li class="@active($navigasi === 'pemantauan-bulanan-ibu-hamil')"><a href="{{ ci_route('stunting.pemantauan_ibu_hamil') }}">Bulanan
                            Ibu Hamil</a></li>
                    <li class="@active($navigasi === 'pemantauan-bulanan-anak')"><a href="{{ ci_route('stunting.pemantauan_anak') }}">Bulanan Anak 0-2
                            Tahun</a></li>
                    <li class="@active($navigasi === 'pemantauan-sasaran-paud')"><a href="{{ ci_route('stunting.pemantauan_paud') }}">Sasaran Paud Anak
                            2-6 tahun</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Rekapitulasi</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body no-padding">
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li class="@active($navigasi === 'rekapitulasi-hasil-pemantauan-ibu-hamil')"><a href="{{ ci_route('stunting.rekapitulasi_ibu_hamil') }}">
                            Bulanan Ibu Hamil</a></li>
                    <li class="@active($navigasi === 'rekapitulasi-hasil-pemantauan-anak')"><a href="{{ ci_route('stunting.rekapitulasi_bulanan_anak') }}">
                            Bulanan Anak 0-2 Tahun</a></li>
                    <li class="@active($navigasi === 'rekapitulasi-hasil-pemantauan-balita')"><a href="{{ ci_route('stunting.rekapitulasi_bulanan_balita') }}">
                            Bulanan Anak 2-6 Tahun</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-body no-padding">
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li class="@active($navigasi === 'scorcard-konvergensi')"><a href="{{ ci_route('stunting.scorecard_konvergensi') }}">Scorecard
                            Konvergensi</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
