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
                        <th>Nama</th>
                        <th>Briva</th>
                        <th>Nominal</th>
                        <th>Kelas</th>
                        <th>Act</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data as $a) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $a->nama ?></td>
                            <td><?= $a->briva ?></td>
                            <td>Rp. <?= number_format($a->total, 0, '.', '.') ?></td>
                            <td><?= $a->k_formal . ' ' . $a->t_formal ?></td>
                            <td>
                                <a href="<?= base_url('bp/discrb/' . $a->nis); ?>"><button class="btn btn-info btn-sm">Discrb</button></a>
                            </td>
                        </tr>
                        <!-- Modal -->
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

</section>