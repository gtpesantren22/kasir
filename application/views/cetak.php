<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <style>
        @font-face {
            font-family: 'Merchant Copy Doublesize';
            /* Ganti NamaFont dengan nama font yang Anda inginkan */
            src: url('../../template/assets/static/fonts/Merchant-Copy-Doublesize.ttf') format('truetype');
            /* Ganti path/to/font.woff2 dengan path menuju file font Anda */
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Merchant Copy Doublesize', sans-serif;
        }

        .judul {
            font-size: small;
            font-weight: bold;
        }

        .almt {
            font-size: 7px;
            text-align: center;
        }

        table {
            font-size: x-small;
            /* font-weight: bold; */
        }

        .terima {
            font-size: x-small;
            /* font-weight: bold; */
            text-decoration: underline;
            margin-top: 10px;
        }

        .catatan {
            font-size: x-small;
            /* font-weight: bold; */
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="text-center judul"><b>KWITANSI PEMBAYARAN BP <br> PP. DARUL LUGHAH WAL KAROMAH</b></div>
        <div class="almt">Jl. Mayjend Pandjaitan No.12, Kel. Sidomukti - Kec. Kraksaan - Probolinggo - Jawa Timur</div>
        <hr class="border border-dark border-1 opacity-75">
        <table>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td><?= date('d-M-Y H:i') ?></td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>:</td>
                <td><?= $user->nama ?></td>
            </tr>
            <tr>
                <td>Ket</td>
                <td>:</td>
                <td>Pembayaran BP</td>
            </tr>
        </table>
        <div class="terima">Diterima dari</div>
        <table>
            <tr>
                <td>No. Briva</td>
                <td>:</td>
                <td><?= $tangg->briva ?></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><?= $santri->nama ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?= $santri->desa . '-' . $santri->kec . '-' . $santri->kab ?></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td><?= $santri->k_formal . ' ' . $santri->jurusan . ' ' . $santri->t_formal ?></td>
            </tr>
        </table>
        <div class="terima"></div>
        <table class="table table-bordered table-sm">
            <tr>
                <th>Tgl Bayar</th>
                <th><?= $data->at ?></th>
            </tr>
            <tr>
                <th>Nominal</th>
                <th><?= rupiah($data->nominal) ?></th>
            </tr>
            <tr>
                <th>Penerima</th>
                <th><?= $data->kasir ?></th>
            </tr>
            <tr>
                <th>Ket</th>
                <th><?= $data->bulan ?></th>
            </tr>
        </table>
        <div class="terima"></div>
        <div class="catatan">
            Catatan : <br>
            Bukti pembayaran ini DISIMPAN oleh wali santri sebagai bukti pembayaran Biaya Pendidikan (BP) PP. Darul Lughah Wal Karomah Tahun Pelajaran <?= $tahun ?>
        </div>
        <div class="terima"></div>
        <div class="catatan">
            Hal-hal yang berkaitan dengan teknis keuangan dapat menghubungi Contact Person Berikut
            <u>082 329 641 926</u> <br> Terimakasih
        </div>
        <div class="terima"></div>
        <table class=" table table-sm">
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <!-- <td>&nbsp;&nbsp;&nbsp;&nbsp;</td> -->
                <!-- <td>&nbsp;&nbsp;&nbsp;&nbsp;</td> -->
                <!-- <td>&nbsp;&nbsp;&nbsp;&nbsp;</td> -->
                <!-- <td>&nbsp;&nbsp;&nbsp;&nbsp;</td> -->
                <td>
                    <div class="catatan">Kraksaan, <?= date('d-m-Y') ?></div><br><br>
                    <div class="catatan"><u>Bendahara Pesantren</u></div>
                </td>
            </tr>
        </table>

    </div>
</body>

<script>
    window.print()
</script>

</html>