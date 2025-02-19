<style>
    .progress {
        height: 20px;
        background-color: #f3f3f3;
        border-radius: 5px;
        overflow: hidden;
        position: relative;
    }

    .progress-bar {
        height: 100%;
        background-color: #007bff;
        text-align: center;
        line-height: 20px;
        color: white;
        transition: width 0.4s ease;
    }

    #response-container {
        margin-top: 10px;
    }
</style>

<div class="page-heading">
    <h3>Informasi</h3>
    <p class="text-subtitle text-muted">Fitur pengiriman informasi melalui WhatsApp secara massal.</p>
</div>
<section>
    <div class="card">
        <div class="card-header">
            <h4>Kirim informasi slip gaji</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-xl-12">
                    <table class="table table-xs table-bordered">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Bulan</td>
                                <td>#</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($datagaji as $gaji):
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= bulan($gaji['bulan']) . ' ' . $gaji['tahun']; ?></td>
                                    <td>
                                        <?php if ($gaji['status'] === 'kunci') { ?>
                                            <button onclick="window.location='<?= base_url('informasi/detail_gaji/' . $gaji['gaji_id']) ?>'" class="btn btn-sm btn-info"><i class="bi bi-search"></i> Cek Detail</button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</section>