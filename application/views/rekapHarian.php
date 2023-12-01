<div class="page-heading">
    <h3>Rekapan Hari ini : <?= tanggalIndo(date('Y-m-d')) ?></h3>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover ">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Ket</th>
                            <th>Nominal Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md">
                                        <img src="<?= base_url('template/') ?>assets/compiled/jpg/5.jpg">
                                    </div>
                                    <p class="font-bold ms-3 mb-0">Pembayaran BP</p>
                                </div>
                            </td>
                            <td class="col-auto">
                                <p class=" mb-0">Pemasukan</p>
                            </td>
                            <td class="col-auto">
                                <p class=" mb-0"><?= rupiah($bp->jml) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md">
                                        <img src="<?= base_url('template/') ?>assets/compiled/jpg/2.jpg">
                                    </div>
                                    <p class="font-bold ms-3 mb-0">Setoran</p>
                                </div>
                            </td>
                            <td class="col-auto">
                                <p class=" mb-0">Pemasukan</p>
                            </td>
                            <td class="col-auto">
                                <p class=" mb-0"><?= rupiah($setoran->jml) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md">
                                        <img src="<?= base_url('template/') ?>assets/compiled/jpg/5.jpg">
                                    </div>
                                    <p class="font-bold ms-3 mb-0">Pengeluaran</p>
                                </div>
                            </td>
                            <td class="col-auto">
                                <p class=" mb-0">Pengeluaran</p>
                            </td>
                            <td class="col-auto">
                                <p class=" mb-0"><?= rupiah($keluar->jml) ?></p>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th><?= rupiah(($bp->jml + $setoran->jml) - $keluar->jml) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5>Pemasukan BP</h5>
            <table class="table table-striped" id="">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($bpData as $a) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $a->nama ?></td>
                            <td><?= $a->tgl ?></td>
                            <td>Rp. <?= number_format($a->nominal, 0, '.', '.') ?></td>
                            <td><?= $a->kasir ?></td>
                        </tr>
                        <!-- Modal -->
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5>Pemasukan Setoran</h5>
            <table class="table table-striped" id="">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Bayar</th>
                        <th>Santri</th>
                        <th>Bulan</th>
                        <th>Nominal</th>
                        <th>Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($setoranData as $r) { ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $r->tgl; ?></td>
                            <td><?= $r->nama; ?></td>
                            <td><?= $bulan[$r->bulan]; ?></td>
                            <td><?= rupiah($r->nominal); ?></td>
                            <td><?= $r->kasir; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5>Pengeluaran</h5>
            <table class="table table-striped" id="">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Ket</th>
                        <th>Nominal</th>
                        <th>Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($keluarData as $r2) { ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $r2->tanggal; ?></td>
                            <td><?= $r2->ket; ?></td>
                            <td><?= rupiah($r2->nominal); ?></td>
                            <td><?= $r2->penerima; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>