<?php $this->load->view('head'); ?>
<div class="page-heading">
    <h3>Detail Tanggungan Santri</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item active"><?= $santri->nis ?></li>
                        <li class="list-group-item"><?= $santri->nama ?></li>
                        <li class="list-group-item"><?= $santri->desa . ' - ' . $santri->kec . ' - ' . $santri->kab ?></li>
                        <li class="list-group-item"><?= $santri->k_formal . '-' . $santri->jurusan . '-' . $santri->t_formal ?></li>
                        <li class="list-group-item"><?= $santri->k_madin . ' ' . $santri->r_madin ?></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Ganti Santri</label>
                        <select class="choices form-select" id="nisSelect" onchange="redirectToPage2()">
                            <option value=""> -pilih- </option>
                            <?php foreach ($santriData as $dts) : ?>
                                <option value="<?= $dts->nis ?>"><?= $dts->nama ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Buat Tanggungan Santri</h5>
            <div class="row">
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th colspan="2">NAMA TAGIHAN</th>
                                    <th>JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($tagihan as $tg) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td class="text-bold-500"><?= $tg->nama_pos  ?></td>
                                        <td class="text-bold-500"><?= $tg->id_jenis . ' - ' . $tg->nama_tagihan ?></td>
                                        <td>
                                            <form action="<?= base_url('bp/editTagihan') ?>" method="post" class="form-horizontal">
                                                <input type="hidden" name="id" value="<?= $tg->id_tagihan ?>">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control form-control-sm uang" name="nominal" value="<?= $tg->nom_jadi ?>">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                                <button type="submit" class="btn btn-success"><i class="bi bi-check"></i></button>
                                                                <a href="<?= base_url('bp/delTagihan/' . $tg->id_tagihan) ?>" class="btn btn-danger tombol-hapus"><i class="bi bi-trash"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="<?= base_url('bp/genarate/' . $santri->nis) ?>" class="btn btn-sm btn-primary btn-block tbl-confirm" value="Fitur ini akan membuat tanggungan. dan jika sudah ada maka akan mengganti ke data yang terbaru">Genarate Tanggungan</a>
                    <a href="<?= base_url('bp/reset/' . $santri->nis) ?>" class="btn btn-sm btn-danger btn-block tbl-confirm" value="Fitur ini akan mereset/menghapus tanggungan santri terkait">Reset Tanggungan</a>
                    <hr>

                    <form action="<?= base_url('bp/editRingan') ?>" method="post">
                        <label for="">Keringanan (%)</label>
                        <?php foreach ($keringanan as $ring) : ?>
                            <input type="hidden" name="id" value="<?= $ring->id_keringanan ?>">
                            <input type="hidden" name="nis" value="<?= $ring->nis ?>">
                            <div class="input-group mb-1">
                                <input type="number" class="form-control" name="jumlah" value="<?= $ring->jumlah ?>">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-success" type="submit">Update</button>
                            </div>
                        <?php endforeach ?>
                    </form>
                    <br>
                    <h7 class="text-muted font-semibold">Jumlah Tanggungan</h7>
                    <h6 class="font-extrabold mb-0"><?= rupiah($tanggTotal) ?></h6>
                    <br>
                    <h7 class="text-muted font-semibold">Jumlah Keringanan</h7>
                    <h6 class="font-extrabold mb-0"><?= rupiah(($ring->jumlah / 100) * $tanggTotal) ?></h6>
                    <br>
                    <h7 class="text-muted font-semibold">Dekosan</h7>
                    <h6 class="font-extrabold mb-0"><?php $dekos = $santri->ket == 0 ? 300000 * 10 : 0;
                                                    echo rupiah($dekos);  ?></h6>

                    <hr>
                    <center>
                        <h5 class="text-muted font-semibold">Total Akhir BP</h5>
                        <h4 class="font-extrabold mb-0"><?= rupiah($dekos + $tanggTotal - (($ring->jumlah / 100) * $tanggTotal)) ?></h4>
                    </center>
                    <hr>
                    Calculator App
                    <div id="calculator">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" class="form-control uang" id="num1" required>
                            </div>
                            <div class="col-md-4">
                                <select id="operator" class="form-select">
                                    <option value="+">+</option>
                                    <option value="-">-</option>
                                    <option value="*">x</option>
                                    <option value="/">/</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" class="form-control uang" id="num2" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success btn-sm" onclick="calculate()">Calculate</button>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" class="form-control uang" id="hasilHitung" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<!--Disabled Backdrop Modal -->
<div class="modal fade text-left" id="tambahData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel4">Tambah Akun Piutang</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <?= form_open('bp/savePiutang') ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="exampleInputEmail1">Nama Piutang</label>
                    <input type="text" name="nama" class="form-control" id="" placeholder="Nama Piutang" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1" class="form-label">ket</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" required name="ket"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="submit" class="btn btn-primary ms-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Simpan Data</span>
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?php $this->load->view('foot'); ?>
<script>
    function calculate() {
        var num1 = parseFloat(document.getElementById('num1').value.replaceAll('.', '').replace(',', '.')); // Hilangkan titik pemisah dan konversi ke angka
        var num2 = parseFloat(document.getElementById('num2').value.replaceAll('.', '').replace(',', '.')); // Hilangkan titik pemisah dan konversi ke angka
        var operator = document.getElementById('operator').value;

        // Kirim permintaan ke server menggunakan AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?= base_url("bp/calculate"); ?>', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Tampilkan hasil di halaman tanpa refresh
                document.getElementById('hasilHitung').value = parseFloat(xhr.responseText).toLocaleString();
                console.log(xhr.responseText)
            }
        };

        // Kirim data ke server
        xhr.send('num1=' + num1 + '&num2=' + num2 + '&operator=' + operator);
    }
</script>