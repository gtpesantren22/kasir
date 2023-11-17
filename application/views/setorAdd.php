<div class="page-heading">
    <h3>Pemasukan Setoran</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <?= form_open('setor/save') ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="basicInput">Lembaga</label>
                    <select class="form-select choices" id="" required name="lembaga">
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
                    <select name="bulan" class="form-select" required>
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
                    <label for="">Tanggal Setor</label>
                    <input type="text" id="" name="tgl" class="form-control flatpickr-no-config" id="" placeholder="Tanggal" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Penyetor</label>
                    <input type="text" id="" name="penyetor" class="form-control" id="" placeholder="Nama Penyetor" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary ms-1" data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Simpan Data</span>
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>

</section>