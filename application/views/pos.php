<?php $this->load->view('head'); ?>
<div class="page-heading">
    <h3>Daftar Pos BP</h3>
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
                        <th>ID Piutang</th>
                        <th>Nama Pos</th>
                        <th>Ket</th>
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
                            <td><?= $r->id_piutang; ?></td>
                            <td><?= $r->nama_pos; ?></td>
                            <td><?= $r->ket; ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editData<?= $r->id_pos ?>"><i class="bi bi-pencil-square"></i></button>
                                <div class="modal fade text-left" id="editData<?= $r->id_pos ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel4">Edit Pos Tagihan</h4>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <i data-feather="x"></i>
                                                </button>
                                            </div>
                                            <?= form_open('bp/editPos') ?>
                                            <input type="hidden" name="id" value="<?= $r->id_pos ?>">
                                            <div class="modal-body">
                                                <div class="form-group mb-2">
                                                    <label for="">Akun Piutang</label>
                                                    <select name="piutang" id="" class="form-select" required>
                                                        <option value="">Pilih Akun Piutang</option>
                                                        <?php foreach ($piutang as $akun) { ?>
                                                            <option <?= $akun->id_piutang == $r->id_piutang ? 'selected' : '' ?> value="<?= $akun->id_piutang ?>"><?= $akun->id_piutang . ' - ' . $akun->nama_piutang ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label for="exampleInputEmail1">Nama Piutang</label>
                                                    <input type="text" name="nama" class="form-control" id="" placeholder="Nama Piutang" required value="<?= $r->nama_pos ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleFormControlTextarea1" class="form-label">ket</label>
                                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" required name="ket"><?= $r->ket ?></textarea>
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
                                <a href="<?= base_url('bp/delPos/' . $r->id_pos) ?>" class="btn btn-danger btn-sm tombol-hapus"><i class="bi bi-trash"></i></a>
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
                <h4 class="modal-title" id="myModalLabel4">Tambah Pos Tagihan</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <?= form_open('bp/savePos') ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="exampleInputEmail1">Akun Piutang</label>
                    <select name="piutang" id="" class="form-select" required>
                        <option value="">Pilih Akun Piutang</option>
                        <?php foreach ($piutang as $akun) { ?>
                            <option value="<?= $akun->id_piutang ?>"><?= $akun->id_piutang . ' - ' . $akun->nama_piutang ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="exampleInputEmail1">Nama Pos</label>
                    <input type="text" name="nama" class="form-control" id="" placeholder="Nama Piutang" required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1" class="form-label">Ket</label>
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