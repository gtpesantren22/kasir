<div class="page-heading">
    <h3>Pemasukan Setoran</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-primary " onclick="window.location='<?= base_url('setor/add') ?>'"><i class="bi bi-clipboard-heart-fill"></i> Tambah Data</button>
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Lembaga</th>
                        <th>Uraian</th>
                        <th>Nominal</th>
                        <th>Penyetor</th>
                        <th>Tgl Bayar</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($setor as $r) { ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $r->lembaga; ?></td>
                            <td><?= $r->uraian; ?></td>
                            <td><?= rupiah($r->nominal); ?></td>
                            <td><?= $r->penyetor; ?></td>
                            <td><?= $r->tgl_setor; ?></td>
                            <td>
                                <a href="<?= base_url('setor/edit/' . $r->id_setor) ?>"><i class="bi bi-pencil-square text-warning"></i></a> |
                                <a href="<?= base_url('setor/delBayar/' . $r->id_setor) ?>" class="tombol-hapus"><i class="bi bi-trash text-danger"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</section>

<!--Disabled Backdrop Modal -->
<div class="modal fade text-left" id="backdrop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel4">Tambah Data Setoran</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <?= form_open('setor/save') ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="basicInput">Lembaga</label>
                    <select class="form-select" id="" required name="lembaga">
                        <option>-pilih-</option>
                        <?php foreach ($lembaga as $ar) : ?>
                            <option value="<?= $ar->nama ?>"><?= $ar->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1" class="form-label">Uraian</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" required name="uraian"></textarea>
                </div>
                <div class="form-group mb-2">
                    <label for="exampleInputPassword1">Bulan</label>
                    <select name="bulan" class="form-control" required>
                        <option value=""> -pilih bulan- </option>
                        <?php
                        for ($i = 1; $i <= 12; $i++) { ?>
                            <option value="<?= $i; ?>"><?= $bulan[$i]; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="exampleInputEmail1">Nominal Pembayaran</label>
                    <input type="text" name="nominal" class="form-control uang" id="" placeholder="Masukan nominal" required>
                </div>
                <div class="form-group mb-2">
                    <label for="exampleInputPassword1">Tanggal</label>
                    <input type="text" id="" name="tgl" class="form-control flatpickr-no-config" id="exampleInputPassword1" placeholder="Tanggal" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-primary ms-1" data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Simpan Data</span>
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>