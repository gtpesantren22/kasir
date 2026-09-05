<?php
$this->load->view('head');
?>
<div class="page-heading">
    <h3>History Pembayaran E-Saku</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="historyData">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Bayar</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Nominal</th>
                        <th>Penerima</th>
                        <th>Ket</th>
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
        $('#historyData').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('esaku/historyData'); ?>",
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
                    "data": 7
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        var id = row[1];
                        return `
                        <a href="<?= base_url('esaku/delBayar/') ?>${id}" class="tombol-hapus" title="Hapus"><i class="bi bi-trash text-danger"></i></a>
                    `;
                    }
                }
            ],
            "pageLength": 10,
            "searchDelay": 500
        });

        // Event delegation agar SweetAlert konfirmasi hapus bekerja pada baris yang dimuat secara server-side
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
</script>
