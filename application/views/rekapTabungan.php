<?php
$this->load->view('head');
?>
<div class="page-heading">
    <h3>Rekap Tabungan</h3>
</div>
<section class="section">
    <div class="card">
        <div class="card-body">
            <form action="" id="form-rekap">
                <div class="row">
                    <div class="row mb-1">
                        <label for="input36" class="col-sm-3 col-form-label">Dari Tanggal</label>
                        <div class="col-sm-9">
                            <input type="text" name="tgl" id="dari" class="form-control flatpickr-no-config" required>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label for="input36" class="col-sm-3 col-form-label">Sampai Tanggal</label>
                        <div class="col-sm-9">
                            <input type="text" name="tgl" id="sampai" class="form-control flatpickr-no-config" required>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label for="input36" class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button class="btn btn-sm btn-success" type="submit">Tampilkan</button>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <div id="showRekap"></div>
        </div>
    </div>

</section>


<?php
$this->load->view('foot');
?>

<script>
    $(document).ready(function() {
        $.ajax({
            type: "POST",
            url: "<?= base_url('tabungan/tampil_rekap'); ?>",
            data: {
                'dari': '<?= date('Y-m-d'); ?>',
                'sampai': '<?= date('Y-m-d'); ?>',
            },
            success: function(data) {
                $('#showRekap').html(data);
            }
        });

    })

    $('#form-rekap').on('submit', function(e) {
        e.preventDefault();

        var dari = $('#dari').val();
        var sampai = $('#sampai').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('tabungan/tampil_rekap'); ?>",
            data: {
                'dari': dari,
                'sampai': sampai,
            },
            success: function(data) {
                $('#showRekap').empty();
                $('#showRekap').html(data);
            }
        });
    })
</script>