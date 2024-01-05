<div class="page-heading">
    <h3>Biaya Pendidikan</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form action="" class="form form-horizontal">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="first-name-horizontal">Pilih Santri</label>
                                </div>
                                <div class="col-md-10 form-group">
                                    <select class="choices form-select" id="mySelect" onchange="redirectToPage()">
                                        <option value=""> -pilih- </option>
                                        <?php foreach ($santri as $dts) : ?>
                                            <option value="<?= $dts->nis ?>"><?= $dts->nama ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <hr>
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>NIS</th>
                                <th>: <?= $sn->nis; ?></th>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <th>: <?= $sn->nama; ?></th>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <th>: <?= $sn->desa . ' - ' . $sn->kec . ' - ' . $sn->kab; ?>
                                </th>
                            </tr>
                            <tr>
                                <th>Formal</th>
                                <th>: <?= $sn->k_formal . ' - ' . $sn->t_formal ?></th>
                            </tr>
                            <tr>
                                <th>Madin</th>
                                <th>: <?= $sn->k_madin . ' ' . $sn->r_madin ?></th>
                            </tr>
                            <tr>
                                <th>Kamar</th>
                                <th>: <?= $sn->kamar ?></th>
                            </tr>
                            <tr>
                                <th>Komplek</th>
                                <th>: <?= $sn->komplek ?></th>
                            </tr>
                            <tr>
                                <th>Tempat Kos</th>
                                <th>: <?= kosan($sn->t_kos) ?></th>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <th>: <?= $kter[$sn->ket] ?></th>
                            </tr>
                        </table>
                    </div>
                    History Dekos Santri
                    <?php if ($dekos->num_rows() < 1) { ?>
                        <a class="btn btn-primary btn-sm pull-right tbl-confirm" value="Membuat history dekosan pertama" href="<?= base_url('bp/buatKos/' . $sn->nis) ?>">Buat</a>
                    <?php } else { ?>
                        <button class="btn btn-primary btn-sm pull-right" data-bs-toggle="modal" data-bs-target="#pindahKos">Pindah</button>
                        <div class="modal fade text-left" id="pindahKos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel4">Pindah Kos Santri</h4>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>
                                    <?= form_open('bp/gantiKos') ?>
                                    <input type="hidden" name="nis" value="<?= $sn->nis ?>">
                                    <div class="modal-body">
                                        <div class="form-group mb-2">
                                            <label for="exampleInputEmail1">Pindah Tempat</label>
                                            <select name="t_kos" class="form-control" required>
                                                <?php $tmp = array("-", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Zulfa", "Ny. Lathifah", "Ny. Ummi Kultsum");
                                                for ($a = 0; $a < count($tmp); $a++) : ?>
                                                    <option <?= $a == $sn->t_kos ? 'selected' : '' ?> value="<?= $a ?>"><?= kosan($a) ?></option>
                                                <?php endfor ?>
                                            </select>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="exampleInputEmail1">Tanggal Pindah</label>
                                            <input type="text" name="tanggal" class="form-control flatpickr-no-config" id="" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                        </button>
                                        <button type="submit" class="btn btn-primary ms-1">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Upload Data</span>
                                        </button>
                                    </div>
                                    <?= form_close() ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="table-responsive mt-2">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tempat</th>
                                    <th>Mulai</th>
                                    <th>Keluar</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($dekos->result() as $dks) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= kosan($dks->t_kos) ?></td>
                                        <td><?= $dks->masuk ?></td>
                                        <td><?= $dks->keluar ?></td>
                                        <td><a href="<?= base_url('bp/delDekos/') . $dks->id_dekos ?>" class="tombol-hapus">Hapus</a></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                </div>
                <div class="col-md-4">
                    <div class="row row-cols-1 row-cols-md-1 row-cols-xl-12">
                        <div class="col">
                            <div class="card radius-10 border-start border-0 border-3 border-info">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Tanggungan</p>
                                            <h4 class="my-1 text-info"><?= rupiah($tgn->total) ?></h4>
                                            <!-- <p class="mb-0 font-13">Jumlah Tanggunagn dalam 1 tahun</p> -->
                                        </div>
                                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                            <i class='bx bxs-cart'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card radius-10 border-start border-0 border-3 border-danger">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Belum Bayar</p>
                                            <h4 class="my-1 text-danger">
                                                <?= rupiah($tgn->total - $masuk->jml) ?></h4>
                                            <!-- <p class="mb-0 font-13">Sisa tanggungan yang belum lunas</p> -->
                                        </div>
                                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                            <i class='bx bxs-wallet'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card radius-10 border-start border-0 border-3 border-success">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Pembayaran</p>
                                            <h4 class="my-1 text-success"><?= rupiah($masuk->jml) ?></h4>
                                            <!-- <p class="mb-0 font-13">Jumlah yang sudah dibayar</p> -->
                                        </div>
                                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                            <i class='bx bxs-bar-chart-alt-2'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-3">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <?php
                                            for ($i = 1; $i <= 12; $i++) {
                                                $tnn = $tgn->ju_ap;
                                                if ($i == 5 || $i == 6) {
                                                    $tnnOk = $tgn->me_ju;
                                                } else {
                                                    $tnnOk = $tnn;
                                                }
                                            ?>
                                                <tr>
                                                    <th><?= $bulan[$i]; ?></th>
                                                    <th><?= rupiah($tnnOk); ?></th>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div> -->
                <div class="col-md-12 mt-2">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tgl Bayar</th>
                                    <th>Nominal</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                    <th>Penerima</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($bayar as $r) { ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $r->tgl; ?></td>
                                        <td><?= rupiah($r->nominal); ?></td>
                                        <td><?= $bulan[$r->bulan]; ?></td>
                                        <td><?= $r->tahun; ?></td>
                                        <td><span class="badge bg-success"><?= $r->kasir; ?></span>
                                        </td>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-5 mt-2">
                    <form action="<?= base_url('bp/addbayar') ?>" method="POST">
                        <input type="hidden" name="nis" value="<?= $sn->nis; ?>">
                        <div class="box-body">
                            <div class="form-group mb-2">
                                <label for="exampleInputEmail1">Nominal Pembyaran</label>
                                <input type="text" name="nominal" class="form-control uang" id="" placeholder="Masukan nominal" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="exampleInputPassword1">Tanggal</label>
                                <input type="text" id="" name="tgl" class="form-control flatpickr-no-config" id="exampleInputPassword1" placeholder="Tanggal" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="exampleInputPassword1">Keterengan BP</label>
                                <input type="text" name="bulan" class="form-control" id="" placeholder="Bulan BP" required>
                            </div>
                            <div class="form-group mb-2">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-save"></i> Simpan</button>
                            </div>
                        </div><!-- /.box-body -->

                    </form>
                </div>
                <div class="col-md-7">
                    <p>Hasil Input</p>
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tgl Bayar</th>
                                    <th>Nominal</th>
                                    <th>Penerima</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($hasil as $r) { ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $r->tgl; ?></td>
                                        <td><?= rupiah($r->nominal); ?></td>
                                        <td><?= $r->kasir; ?></td>
                                        <td>
                                            <a href="<?= base_url('bp/cetak/' . $r->id_bayar) ?>" target="_blank"><i class="bi bi-printer-fill text-success"></i></a>
                                            <a href="<?= base_url('bp/delBayar/' . $r->id_bayar) ?>" class="tombol-hapus"><i class="bi bi-trash text-danger"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>