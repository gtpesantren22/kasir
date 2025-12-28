<div class="page-heading">
    <h3>E Saku</h3>
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
                                    <select class="choices form-select" id="mySelect" onchange="redirectToPage3()">
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
                <div class="col-md-7">
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

                </div>
                <div class="col-md-5">
                    <form action="<?= base_url('esaku/addbayar') ?>" method="POST">
                        <input type="hidden" name="nis" value="<?= $sn->nis; ?>">
                        <div class="box-body">
                            <div class="form-group mb-2">
                                <label for="exampleInputEmail1">Nominal Uang</label>
                                <input type="text" name="nominal" class="form-control uang" id="" placeholder="Masukan nominal" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="exampleInputPassword1">Tanggal</label>
                                <input type="text" id="" name="tgl" class="form-control flatpickr-no-config" id="exampleInputPassword1" placeholder="Tanggal" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="exampleInputPassword1">Keterengan</label>
                                <input type="text" name="ket" class="form-control" id="" placeholder="Keterangan" value="Uang saku + Admin" readonly>
                            </div>
                            <div class="form-group mb-2">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-save"></i> Simpan</button>
                            </div>
                        </div><!-- /.box-body -->

                    </form>
                </div>

                <div class="col-md-10">
                    <p>Hasil Input</p>
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tgl Bayar</th>
                                    <th>Nominal</th>
                                    <th>Penerima</th>
                                    <th>Ket</th>
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
                                        <td><?= $r->ket; ?></td>
                                        <td>
                                            <!-- <a href="<?= base_url('esaku/cetak/' . $r->id_bayar) ?>"><i class="bi bi-printer-fill text-success"></i></a> -->
                                            <a href="<?= base_url('esaku/delBayar/' . $r->id_bayar) ?>" class="tombol-hapus"><i class="bi bi-trash text-danger"></i></a>
                                            <a onclick="cetakStruk(<?= $r->id_bayar ?>)"><i class="bi bi-printer-fill text-success"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-2">
                    <p>Printer Aktif</p>
                    <span class="badge bg-primary mb-2"><?= $this->session->userdata('printername') ?? '-' ?></span>
                    <form action="<?= base_url('esaku/changePrinter') ?>" method="post">
                        <input type="hidden" name="nis" value="<?= $sn->nis; ?>">
                        <div class="form-group">
                            <!-- <label for="">Pilih</label> -->
                            <select name="printer" id="" class="form-control">
                                <option value="">-pilih jenis printer-</option>

                                <?php
                                foreach ($printers as $printer) {
                                    echo "<option value='$printer->nama'>$printer->nama</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success btn-sm" type="submit">Ganti</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/qz-tray/qz-tray.js"></script>

<script>
    async function cetakStruk(id) {
        const res = await fetch("<?= base_url('esaku/data_cetak/') ?>" + id);
        const d = await res.json();

        try {
            if (!qz.websocket.isActive()) {
                await qz.websocket.connect();
            }

            const printer = await qz.printers.find(d.printername);
            const cfg = qz.configs.create(printer);

            const esc = [
                '\x1B\x40', // INIT (reset printer)

                // ===== JUDUL =====
                '\x1B\x61\x01', // center
                '\x1D\x21\x11', // double width + height
                d.judul + '\n',
                '\x1D\x21\x00', // normal size

                d.pondok + '\n',
                d.alamat + '\n',
                '-'.repeat(48) + '\n',

                // ===== HEADER =====
                '\x1B\x61\x00', // left
                'Tanggal : ' + d.tanggal + '\n',
                'Kasir   : ' + d.kasir + '\n',
                'Ket     : Top Up Uang Saku\n',
                '\n',

                // ===== DATA SANTRI =====
                '\x1B\x45\x01', // bold ON
                'Diterima dari:\n',
                '\x1B\x45\x00', // bold OFF

                'Nama   : ' + d.nama + '\n',
                'Alamat : ' + d.alamat_santri + '\n',
                'Kelas  : ' + d.kelas + '\n',
                '\n',

                // ===== RINCIAN =====
                '\x1B\x45\x01',
                'Rincian:\n',
                '\x1B\x45\x00',

                'Tgl Bayar : ' + d.tgl_bayar + ' ' + d.waktu + '\n',
                'Nominal   : Rp ' + d.nominal + '\n',
                'Penerima  : ' + d.kasir + '\n',
                'Ket       : ' + d.ket + '\n',
                '\n',

                // ===== CATATAN =====
                '\x1B\x45\x01',
                'Catatan:\n',
                '\x1B\x45\x00',

                'Bukti pembayaran ini DISIMPAN oleh wali santri\n',
                'sebagai bukti Top Up uang saku\n',
                'Ponpes Darul Lughah Wal Karomah\n',
                'Tahun ' + d.tahun + '\n',
                '\n',

                'Contact Person:\n',
                '\x1B\x45\x01',
                '0822-3281-1074 (Ustdh. Siti Wardah)\n',
                '0851-6715-8792 (Petugas)\n',
                '\x1B\x45\x00',
                '\n',

                // ===== TTD =====
                '\x1B\x61\x01',
                'Kraksaan, ' + d.tanggal.split(' ')[0] + '\n',
                '\n',
                'Petugas Uang Saku\n',

                // ===== FEED & CUT =====
                '\x1B\x64\x05', // feed 3 lines (PENTING!)
                '\x1D\x56\x00' // cut
            ];

            await qz.print(cfg, esc);

        } catch (err) {
            alert('QZ Tray belum aktif');
            console.error(err);
        }
    }
</script>