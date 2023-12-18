<?php $this->load->view('head'); ?>
<div class="page-heading">
    <h3>Daftar Jenis Tagihan</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#tambahData"><i class="bi bi-clipboard-heart-fill"></i> Tambah Data</button>
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pos</th>
                        <th>Nama Tagihan</th>
                        <th>Level</th>
                        <th>Nominal</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data as $r) { ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $r->id_pos; ?></td>
                            <td><?= $r->id_jenis . ' - ' . $r->nama_tagihan; ?></td>
                            <td><?= $r->jenjang; ?></td>
                            <td><?= rupiah($r->nominal); ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editData<?= $r->id_jenis ?>"><i class="bi bi-pencil-square"></i></button>
                                <div class="modal fade text-left" id="editData<?= $r->id_jenis ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel4">Edit Pos Tagihan</h4>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <i data-feather="x"></i>
                                                </button>
                                            </div>
                                            <?= form_open('bp/editJenis') ?>
                                            <input type="hidden" name="id" value="<?= $r->id_jenis ?>">
                                            <div class="modal-body">
                                                <div class="form-group mb-2">
                                                    <label for="exampleInputEmail1">Pos Tagihan</label>
                                                    <select name="pos" id="" class="form-select" required>
                                                        <option value="">-Pilih Pos-</option>
                                                        <?php foreach ($pos as $akun) { ?>
                                                            <option <?= $akun->id_pos == $r->id_pos ? 'selected' : '' ?> value="<?= $akun->id_pos ?>"><?= $akun->id_pos . ' - ' . $akun->nama_pos ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label for="exampleInputEmail1">Nama Tagihan</label>
                                                    <input type="text" name="nama" class="form-control" id="" placeholder="Nama Piutang" value="<?= $r->nama_tagihan ?>" required>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label for="exampleInputEmail1">Jenjang</label>
                                                    <input type="text" name="jenjang" class="form-control" id="" placeholder="Jenjang Tagihan" value="<?= $r->jenjang ?>" required>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label for="exampleInputEmail1">Nominal</label>
                                                    <input type="text" name="nominal" class="form-control uang" id="" placeholder="Nominal Tagihan" <?= $r->nominal ?> value="0" required>
                                                    <small class="text-danger">*) Berlaku untuk tagihan yang nominalnya sama seluruh santri</small>
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
                                <a href="<?= base_url('bp/delJenis/' . $r->id_jenis) ?>" class="btn btn-danger btn-sm tombol-hapus"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</section>

<!--Disabled Backdrop Modal -->
<div class="modal fade text-left" id="tambahData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel4">Tambah Jenis Tagihan</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <?= form_open('bp/saveJenis') ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="exampleInputEmail1">Pos Tagihan</label>
                    <select name="pos" id="" class="form-select" required>
                        <option value="">-Pilih Pos-</option>
                        <?php foreach ($pos as $akun) { ?>
                            <option value="<?= $akun->id_pos ?>"><?= $akun->id_pos . ' - ' . $akun->nama_pos ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="exampleInputEmail1">Nama Tagihan</label>
                    <input type="text" name="nama" class="form-control" id="" placeholder="Nama Piutang" required>
                </div>
                <div class="form-group mb-2">
                    <label for="exampleInputEmail1">Jenjang</label>
                    <input type="text" name="jenjang" class="form-control" id="" placeholder="Jenjang Tagihan" required>
                </div>
                <div class="form-group mb-2">
                    <label for="exampleInputEmail1">Nominal</label>
                    <input type="text" name="nominal" class="form-control uang" id="" placeholder="Nominal Tagihan" value="0" required>
                    <small class="text-danger">*) Berlaku untuk tagihan yang nominalnya sama seluruh santri</small>
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