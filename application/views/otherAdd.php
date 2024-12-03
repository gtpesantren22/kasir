<div class="page-heading">
    <h3>Pemasukan Lainnya</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <?= form_open('others/save') ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="basicInput">Diterima dari</label>
                    <input type="text" name="dari" class="form-control" id="" placeholder="Diterima dari...." required>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" required name="ket"></textarea>
                </div>
                <div class="form-group mb-2">
                    <label for="exampleInputEmail1">Nominal Pembayaran</label>
                    <input type="text" name="nominal" class="form-control uang" id="" placeholder="Masukan nominal" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tanggal Setor</label>
                    <input type="text" id="" name="tanggal" class="form-control flatpickr-no-config" id="" placeholder="Tanggal" required>
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