<div class="page-heading">
    <h3>Biaya Pendidikan</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Bayar</th>
                        <th>Santri</th>
                        <th>Bulan</th>
                        <th>Nominal</th>
                        <th>Penerima</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($bayar as $r) { ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $r->tgl; ?></td>
                            <td><?= $r->nama; ?></td>
                            <td><?= $bulan[$r->bulan]; ?></td>
                            <td><?= rupiah($r->nominal); ?></td>
                            <td><?= $r->kasir; ?></td>
                            <td><a href="<?= base_url('bp/delBayar/' . $r->id_bayar) ?>" onclick="return confirm('Yakin akan dihapus ?')"><i class="bi bi-trash text-danger"></i></a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</section>