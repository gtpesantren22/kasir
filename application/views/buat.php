<?php $this->load->view('head'); ?>
<div class="page-heading">
    <h3>Daftar Santri Aktif</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <button data-bs-toggle="modal" data-bs-target="#uploadData" class="btn btn-sm btn-primary">Upload Tagihan</button>
            <a href="<?= base_url('bp/template') ?>" class="btn btn-sm btn-primary">Download Template Tagihan</a>
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Tagihan</th>
                        <!-- <th>Alamat</th> -->
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($santri as $r) { ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $r->nis; ?></td>
                            <td><?= $r->nama; ?></td>
                            <!-- <td><?= $r->desa . ' - ' . $r->kec; ?></td> -->
                            <td><?= $r->k_formal . '-' . $r->t_formal; ?></td>
                            <td><?= rupiah($r->total); ?></td>
                            <td>
                                <a href="<?= base_url('bp/tanggungan/' . $r->nis) ?>" class="btn btn-warning btn-sm"><i class="bi bi-list"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</section>

<!--Disabled Backdrop Modal -->
<div class="modal fade text-left" id="uploadData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel4">Upload Data Tagihan Santri</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <?= form_open_multipart('bp/uploadTagihan') ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="exampleInputEmail1">Pilih Berkas</label>
                    <input type="file" name="berkas" class="form-control" id="" required>
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
<?php $this->load->view('foot'); ?>