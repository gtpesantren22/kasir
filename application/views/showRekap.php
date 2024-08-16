<div class="row mt-3">
    <h3><?= $judul ?></h3>
    <div class="col-md-12 mt-3">
        <div class="row">
            <div class="col-md-4">
                <div class="card radius-10 bg-primary bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">TABUNGAN</p>
                                <h4 class="my-1 text-white"><?= rupiah($total->debit); ?></h4>
                            </div>
                            <div class="text-white ms-auto font-35"><i class='bi bi-money'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card radius-10 bg-danger bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">PENGELUARAN</p>
                                <h4 class="my-1 text-white"><?= rupiah($total->kredit); ?></h4>
                            </div>
                            <div class="text-white ms-auto font-35"><i class='bi bi-money'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card radius-10 bg-success bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">SALD0</p>
                                <h4 class="my-1 text-white"><?= rupiah($total->debit - $total->kredit); ?></h4>
                            </div>
                            <div class="text-white ms-auto font-35"><i class='bi bi-money'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="table-responsive">
            <table id="tabunganData" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Santri</th>
                        <th>Nominal</th>
                        <th>Tanggal</th>
                        <th>Ket</th>
                        <th>Kasir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($data as $hasil) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $hasil->nama ?></td>
                            <td><?= rupiah($hasil->nominal) ?></td>
                            <td><?= $hasil->tanggal ?></td>
                            <td><?= $hasil->jenis == 'masuk' ? "<span class='badge bg-success'>masuk</span>" : "<span class='badge bg-danger'>keluar</span>" ?></td>
                            <td><?= $hasil->kasir ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--Disabled Backdrop Modal -->
<div class="modal fade text-left" id="modalRincian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel4">Tambah Data Setoran</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered rincianTabungan" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Ket</th>
                                <th>Status</th>
                                <th>Act</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>