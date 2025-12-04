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
            <h4b>
                <button onclick="window.location.href='<?= base_url('informasi/generateSlip/' . $gajiId) ?>'" class="btn btn-sm btn-success pull-right"><i class="bi bi-envelope"></i> Download Data Gaji</button>
            </h4b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-xl-12">
                    <table class="table table-xs table-striped table-bordered table-hover" id="table1">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Nama Guru</td>
                                <td>Satminkal</td>
                                <td>No. HP</td>
                                <td>Status</td>
                                <td>Nota</td>
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
                                    <td><?= $gaji->nama ?></td>
                                    <td><?= $gaji->satminkal ?></td>
                                    <td><?= $gaji->hp ?></td>
                                    <td><?= $gaji->status == 200 ? "<i class='bi bi-check-circle text-success'> sukses</i>" : "<i class='bi bi-times-circle text-danger'> gagal</i>" ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm cek-nota" data-id="<?= $gaji->id_detail ?>"><i class="bi bi-search">Cek Nota</i></button>
                                        <a class="btn btn-warning btn-sm" href="<?= base_url('informasi/newslip/' . $gaji->gaji_id . '/' . $gaji->guru_id) ?>"><i class="bi bi-reload">Buat Ulang</i></a>
                                        <a class="btn btn-success btn-sm" href="<?= base_url('informasi/downloadSlip/' . $gaji->nota) ?>"><i class="bi bi-download"></i></a>
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm" onclick="window.location='<?= base_url('informasi/sendnewslip/' . $gaji->gaji_id . '/' . $gaji->guru_id) ?>'"><i class="bi bi-send"></i> Kirim Ulang</button>
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

<div class="modal fade text-left" id="modal-kunci" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel4">Pengiriman Slip Gaji</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <b class="">Proses pengiriman slip gaji</b><br>
                    <strong class="text-danger"><i class='bx bx-info-circle'></i> Proses ini akan membutuhkan waktu sedikit lama!</strong><br><br>
                    <div class="row mb-2">
                        <div class="col">
                            <text id="total-data"></text>
                        </div>
                        <div class="col">
                            <input type="number" class="form-control" id="no-awal" placeholder="Batas Awal" aria-label="Batas Awal">
                        </div>
                        <div class="col">
                            <input type="number" class="form-control" id="jml-data" placeholder="Batas Akhir" aria-label="Batas Akhir">
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary" id="proses-kunci" data-id="<?= $gajiId ?>"><span class="tf-icons bx bx-bolt-circle bx-18px me-2"></span>Lanjutkan Proses!</button>
                        </div>
                    </div>
                    <br>
                </div>

                <div id="view-hasil" class="mt-2"></div>

                <div id="slip-container"></div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade text-left" id="modal-nota" tabindex="-1" role="dialog" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel4">Slip Gaji</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img id="notaImage" src="" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on("click", "#button-kirim", function() {
        let idData = $(this).data("id");
        $('#button-kirim').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "<?= base_url('informasi/kirim_slip'); ?>",
            dataType: "json",
            data: {
                id: idData
            },
            success: function(response) {
                $('#total-data').text('Total Data : ' + response.data.length);
                $('#modal-kunci').modal('show')
                $('#button-kirim').prop('disabled', false);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + " " + xhr.statusText);
            }
        });
        // $("#proses-kunci").attr("data-id", idData);
    });

    $('#proses-kunci').on('click', function() {
        var id = $(this).data('id');
        var $button = $('#proses-kunci');
        var noAwal = parseInt($('#no-awal').val(), 10); // Konversi ke integer
        var jmlData = parseInt($('#jml-data').val(), 10); // Konversi ke integer

        $button.prop('disabled', true);
        $('#no-awal').prop('disabled', true);
        $('#jml-data').prop('disabled', true);
        $button.text('Processing...');
        $.ajax({
            type: "POST",
            url: "<?= base_url('informasi/kirim_slip'); ?>",
            dataType: "json",
            data: {
                id: id
            },
            success: function(response) {
                // console.log(data);
                const hasil = $('#view-hasil');
                let berhasil = 0;
                let gagal = 0;
                let persen = 0;
                var total = jmlData - noAwal;
                let totalData = response.data.length;

                function updateProgress() {
                    // const total = Number(total); 
                    if (isNaN(total) || total === 0) {
                        persen = 0; // Jika total tidak valid atau 0, set persen ke 0
                    } else {
                        // Hitung persentase
                        processed = berhasil + gagal; // Total yang sudah diproses
                        persen = (processed / total) * 100;
                    }

                    hasil.html(`
                            <div class="text-center">
                                <strong class="mb-2">Proses kunci data ...</strong>
                                <div class="progress mb-3" style="height: 17px;">
                                    <div class="progress-bar" role="progressbar" style="width: ${persen}%;" aria-valuenow="${persen}" aria-valuemin="0" aria-valuemax="100">${persen.toFixed(2)}%</div>
                                </div>
                            </div>
                            <strong class="mb-1">Total success : ${berhasil}</strong><br>
                            <strong class="mb-1 text-danger">Total error : ${gagal}</strong><br>
                        `);
                }

                const ajaxRequests = response.data.slice(noAwal, jmlData).map((item, index) => {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            $.ajax({
                                url: '<?= base_url("informasi/buatslip") ?>',
                                type: 'POST',
                                data: {
                                    gaji_id: item.gaji_id,
                                    guru_id: item.guru_id,
                                },
                                dataType: 'html', // Ubah ke 'html' karena controller mengembalikan view HTML
                                success: function(res) {
                                    // Tampilkan view slip gaji di dalam #slip-container
                                    $('#slip-container').html(res);

                                    // Tunggu 1 detik untuk memastikan view selesai dimuat
                                    setTimeout(() => {
                                        // Konversi slip gaji ke gambar
                                        html2canvas(document.querySelector("#capture")).then(canvas => {
                                            let imageData = canvas.toDataURL("image/png");

                                            // Kirim gambar ke server untuk disimpan
                                            $.ajax({
                                                url: "<?= base_url('informasi/saveImage') ?>",
                                                type: "POST",
                                                data: {
                                                    image: imageData,
                                                    gaji_id: item.gaji_id,
                                                    guru_id: item.guru_id,
                                                    nama: item.nama,
                                                    satminkal: item.satminkal,
                                                    hp: item.hp,
                                                },
                                                dataType: "json",
                                                success: function(responsei) {
                                                    berhasil++;
                                                    console.log("Respon dari saveImage:", responsei);
                                                    updateProgress();
                                                    resolve(); // Selesaikan Promise setelah gambar disimpan
                                                },
                                                error: function() {
                                                    gagal++;
                                                    updateProgress();
                                                    console.error("Error saat menyimpan gambar");
                                                    resolve(); // Tetap selesaikan Promise meskipun ada error
                                                }
                                            });
                                        });
                                    }, 1000); // Tunggu 1 detik sebelum konversi ke gambar
                                },
                                error: function() {
                                    console.error("Error saat memproses slip gaji");
                                    resolve(); // Tetap selesaikan Promise meskipun ada error
                                }
                            });
                        }, index * 3000);
                    });
                });


                Promise.all(ajaxRequests)
                    .then(function() {
                        console.log('Semua permintaan AJAX selesai');
                        window.location.reload()
                    })
                    .catch(function(error) {
                        console.error('Ada permintaan AJAX yang gagal', error);
                    });


            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });


    $(document).on("click", ".cek-nota", function() {
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '<?= base_url('informasi/cekNota') ?>',
            dataType: 'json',
            data: {
                id: id
            },
            success: function(response) {
                $('#modal-nota').modal('show');
                $("#notaImage").attr("src", '<?= base_url('template/assets/static/images/nota/') ?>' + response.nota);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    })
</script>