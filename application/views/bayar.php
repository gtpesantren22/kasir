<?php $this->load->view('head'); ?>
<div class="page-heading">
    <h3>Biaya Pendidikan</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="bayarData">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Bayar</th>
                        <th>No. Briva</th>
                        <th>Santri</th>
                        <th>Ket Bulan</th>
                        <th>Nominal</th>
                        <th>Penerima</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

</section>
<?php $this->load->view('foot'); ?>
<script>
    $(document).ready(function() {
        $('#bayarData').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('bp/bayarData'); ?>",
                "type": "POST",

            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; // Nomor urut
                    }
                },
                {
                    "data": 2
                },
                {
                    "data": 7
                },
                {
                    "data": 3
                },
                {
                    "data": 4
                },
                {
                    "data": 5,
                    "render": function(data, type, row) {
                        return 'Rp. ' + new Intl.NumberFormat('id-ID', {
                            style: 'decimal',
                            minimumFractionDigits: 0
                        }).format(data);
                    }
                },
                {
                    "data": 6
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        var id = row[1];
                        return `
                        <a href="<?= base_url('bp/delBayar/') ?>${id}" onclick="return confirm('Yakin akan dihapus ?')"><i class="bi bi-trash text-danger"></i></a>
                    `;
                    }
                }
            ],
            "pageLength": 10,
            "searchDelay": 500
        });
    })
</script>