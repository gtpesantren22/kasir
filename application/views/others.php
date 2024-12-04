<div class="page-heading">
    <h3>Pembayaran Lainnya</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <button class="btn btn-primary " onclick="window.location='<?= base_url('others/add') ?>'"><i class="bi bi-clipboard-heart-fill"></i> Tambah Data</button>
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Dari</th>
                        <th>Keterangan</th>
                        <th>Nominal</th>
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
                            <td><?= $r->dari; ?></td>
                            <td><?= $r->ket; ?></td>
                            <td><?= rupiah($r->nominal); ?></td>
                            <td><?= $r->tanggal; ?></td>
                            <td>
                                <a href="<?= base_url('others/print/' . $r->id_other) ?>" target="_blank"><i class="bi bi-printer text-primary"></i></a> |
                                <a href="<?= base_url('others/delBayar/' . $r->id_other) ?>" class="tombol-hapus"><i class="bi bi-trash text-danger"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</section>