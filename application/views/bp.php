<?php
$this->load->view('head');
?>
<div class="page-heading">
    <h3>Biaya Pendidikan</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="bpData">
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
                </tbody>
            </table>
        </div>
    </div>

</section>
<?php
$this->load->view('foot');
?>
<script>
    $(document).ready(function() {
        $('#bpData').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('bp/bpData'); ?>",
                "type": "POST",

            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; // Nomor urut
                    }
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
                        var nis = row[2];
                        return `
                        <a href="<?= base_url('bp/discrb/') ?>${nis}" class="btn btn-success btn-sm">
                            Detail <span class="bi bi-pen"></span>
                        </a>
                    `;
                    }
                }
            ],
            "pageLength": 10,
            "searchDelay": 500
        });
    })
</script>