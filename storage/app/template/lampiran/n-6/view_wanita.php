<page orientation="portrait" format="F4" style="font-size: 12pt">

    <!-- Judul Lampiran -->
    <table align="right">
        <tr>
            <td colspan="10">Lampiran IX</td>
        </tr>
        <tr>
            <td colspan="10">Kepdirjen Bimas Islam Nomor 473 Tahun 2020</td>
        </tr>
        <tr>
            <td colspan="10">Tentang</td>
        </tr>
        <tr>
            <td colspan="10">Petunjuk Teknis Pelaksanaan Pencatatan Nikah</td>
        </tr>
        <tr>
            <?php for ($i = 0; $i < 48; $i++) : ?>
                <td>&nbsp;</td>
            <?php endfor; ?>
        </tr>
    </table>

    <!-- Model N6 -->
    <table align="right">
        <tr>
            <td><strong>Model N6</strong></td>
            <td colspan="30">&nbsp;</td>
        </tr>
    </table>

    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr><td colspan="48">&nbsp;</td></tr>
        <tr>
            <td colspan="17">KANTOR DESA/KELURAHAN</td>
            <td>: </td>
            <td colspan="30">[NAma_desa]</td>
        </tr>
        <tr>
            <td colspan="17">KECAMATAN</td>
            <td>: </td>
            <td colspan="30">[NAma_kecamatan]</td>
        </tr>
        <tr>
            <td colspan="17">KABUPATEN/KOTA</td>
            <td>:</td>
            <td colspan="30">[NAma_kabupaten]</td>
        </tr>
        <tr>
            <?php for ($i = 0; $i < 48; $i++): ?>
                <td>&nbsp;</td>
            <?php endfor; ?>
        </tr>
    </table>

    <p style="margin: 0; text-align: center;" class="title-nikah"><u>SURAT KETERANGAN KEMATIAN</u></p>
    <p style="margin: 0; text-align: center;">Nomor : <?= $format_surat ?></p>

    <p>Yang bertanda tangan dibawah ini menjelaskan dengan sesungguhnya bahwa : </p>
    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="1">A. </td>
            <td colspan="20">1.  Nama lengkap dan alias</td>
            <td>: </td>
            <td colspan="27"><strong>[NAma_dst]</strong></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Bin</td>
            <td>: </td>
            <td colspan="27">[Form_bin_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27">[NiK_dst]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
            <td>: </td>
            <td colspan="27">[TeMpatlahir_dst]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="27">[TaNggallahir_dst]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27">[WArga_negara_dst]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27">[AgAma_dst]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27">[PeKerjaan_dst]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27">[AlAmat_dst]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Telah meninggal dunia pada tanggal</td>
            <td>: </td>
            <td colspan="27">[Form_tanggal_meninggal_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Di</td>
            <td>: </td>
            <td colspan="27">[Form_tempat_meninggal_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="27">[AlAmat_dst]</td>
        </tr>

        <tr>
            <td colspan="48">Yang bersangkutan adalah suami / isteri*) dari :</td>
        </tr>

        <tr>
            <td colspan="1">B.</td>
            <td colspan="20">1. Nama lengkap dan alias</td>
            <td>&nbsp;</td>
            <td colspan="27"><strong>[NAma_dcpw]</strong></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Binti</td>
            <td>&nbsp;</td>
            <td colspan="27">[Form_binti_dcpW]</td>
        </tr>


        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>&nbsp;</td>
            <td colspan="27">[NiK_dcpw]</td>
        </tr>


        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
            <td>&nbsp;</td>
            <td colspan="27">[TtL_dcpw]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>&nbsp;</td>
            <td colspan="27">[WArga_negara_dcpw]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>&nbsp;</td>
            <td colspan="27">[AgAma_dcpw]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>&nbsp;</td>
            <td colspan="27">[PeKerjaan_dcpw]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>&nbsp;</td>
            <td colspan="27">[AlAmat_dcpw]</td>
        </tr>
    </table>

    <p>Demikian surat pengantar ini dibuat dengan mengingat sumpah jabatan dan untuk
dipergunakan sebagaimana mestinya.</p>

    <!-- Penandatangan -->
    <br><br><br>
    <table style="border-collapse: collapse; width: 100%; height: 144px;" border="0">
    <tbody>
    <tr style="height: 18px;">
    <td style="width: 26.6281%; text-align: center; height: 18px;"> </td>
    <td style="width: 2.75528%; height: 18px;"> </td>
    <td style="width: 70.6166%; text-align: center; height: 18px;">[Nama_desA], [TgL_surat]</td>
    </tr>
    <tr style="height: 18px;">
    <td style="width: 26.6281%; text-align: center; height: 18px;"> </td>
    <td style="width: 2.75528%; height: 18px;"> </td>
    <td style="width: 70.6166%; text-align: center; height: 18px;">[Atas_namA]</td>
    </tr>
    <tr style="height: 72px;">
    <td style="width: 26.6281%; text-align: center; height: 72px;"> </td>
    <td style="width: 2.75528%; height: 72px;"><br><br><br><br></td>
    <td style="width: 70.6166%; height: 72px;"> </td>
    </tr>
    <tr style="height: 18px;">
    <td style="width: 26.6281%; text-align: center; height: 18px;"> </td>
    <td style="width: 2.75528%; height: 18px;"> </td>
    <td style="width: 70.6166%; text-align: center; height: 18px;">[NAma_pamonG]</td>
    </tr>
    <tr style="height: 18px;">
    <td style="width: 26.6281%; height: 18px;"> </td>
    <td style="width: 2.75528%; height: 18px;"> </td>
    <td style="width: 70.6166%; text-align: center; height: 18px;"> </td>
    </tr>
    </tbody>
    </table>
</page>