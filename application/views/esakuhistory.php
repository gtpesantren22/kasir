<div class="page-heading">
    <h3>History Pembayaran E-Saku</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Semua Transaksi Pembayaran</h5>
            <?php if ($this->session->userdata('printername')) : ?>
                <span class="badge bg-primary">Printer Aktif: <?= $this->session->userdata('printername'); ?></span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="historyTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tgl Bayar</th>
                            <th>Nama Santri</th>
                            <th>Kelas</th>
                            <th>Nominal</th>
                            <th>Penerima</th>
                            <th>Ket</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($hasil as $r) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $r->tgl; ?></td>
                                <td><?= $r->nama ?? '-'; ?></td>
                                <td><?= trim(($r->k_formal ?? '') . ' ' . ($r->t_formal ?? '')); ?></td>
                                <td><?= rupiah($r->nominal); ?></td>
                                <td><?= $r->kasir; ?></td>
                                <td><?= $r->ket; ?></td>
                                <td>
                                    <a href="<?= base_url('esaku/delBayar/' . $r->id_bayar) ?>" class="tombol-hapus" title="Hapus"><i class="bi bi-trash text-danger"></i></a>
                                    <a onclick="cetakStruk(<?= $r->id_bayar ?>)" title="Cetak Struk" style="cursor: pointer;"><i class="bi bi-printer-fill text-success"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/qz-tray/qz-tray.js"></script>

<script>
    $(document).ready(function() {
        $('#historyTable').DataTable({
            "order": [],
            "pageLength": 10,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });

        // Event delegation agar SweetAlert tetap aktif di halaman berikutnya pada DataTable
        $(document).on('click', '.tombol-hapus', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');

            Swal.fire({
                title: 'Yakin ?',
                text: 'Data ini akan dihapus permanent',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus Data!'
            }).then((result) => {
                if (result.value) {
                    document.location.href = href;
                }
            });
        });
    });

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
