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
            <h4>Kirim tagihan santri</h4>
        </div>
        <div class="card-body">
            <p>--> Download template exel untuk mengirim pesan. <a href="<?= base_url('informasi/downloadTagihan') ?>">Download disini!</a></p>

            <form id="upload_form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="">Pilih berkas</label>
                    <input type="file" name="file" id="file" class="form-control">
                </div>
                <div class="form-group">
                    <button type="button" id="upload_btn" class="btn btn-success">Upload and Send Messages</button>
                </div>

            </form>

            <!-- <form action="<?= base_url('informasi/kirimPesanGaji') ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="">Pilih berkas</label>
                    <input type="file" name="file" class="form-control">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Upload and Send Messages</button>
                </div>

            </form> -->


            <div class="progress progress-success  mt-5" id="progress-container" style="display: none;">
                <div class="progress-bar progress-bar-striped" role="progressbar" saria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>

            <div id="response-container"></div>
        </div>
    </div>

</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#upload_btn').on('click', function() {
            var formData = new FormData();
            formData.append('file', $('#file')[0].files[0]);

            $('#progress-container').show();
            var interval = setInterval(function() {
                var progressBar = $('.progress-bar');
                var width = parseInt(progressBar.attr('aria-valuenow'));
                if (width < 90) {
                    progressBar.attr('aria-valuenow', width + 10);
                    progressBar.css('width', (width + 10) + '%');
                    progressBar.html((width + 10) + '%');
                }
            }, 500);

            $.ajax({
                url: '<?= base_url('informasi/sendTagihan') ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    clearInterval(interval);
                    var progressBar = $('.progress-bar');
                    progressBar.attr('aria-valuenow', 100);
                    progressBar.css('width', '100%');
                    progressBar.html('Upload Complete');

                    var result = JSON.parse(response);
                    // console.log(result);
                    if (result.status === 'success') {
                        var responseHtml = '<h3>Messages Sent:</h3><ul>';
                        result.responses.forEach(function(respString) {
                            try {
                                var resp = JSON.parse(respString); // Menguraikan string JSON menjadi objek
                                if (resp && resp.code) {
                                    var code = resp.code;
                                    var to = resp.query.to;
                                    if (code == 200) {
                                        code = 'sukses';
                                    } else {
                                        code = 'gagal';
                                    }
                                    responseHtml += '<li>Status : ' + code + ' || Kepada: ' + to + '</li>';
                                } else {
                                    responseHtml += '<li>Status Code: Not available</li>';
                                }
                            } catch (e) {
                                responseHtml += '<li>Status Code: Error parsing JSON</li>';
                            }
                        });
                        responseHtml += '</ul>';
                        $('#response-container').html(responseHtml);
                    } else {
                        $('#response-container').html('<p>Error: ' + result.message.error + '</p>');
                    }
                },
                error: function() {
                    clearInterval(interval);
                    var progressBar = $('.progress-bar');
                    progressBar.attr('aria-valuenow', 0);
                    progressBar.css('width', '0%');
                    progressBar.html('Upload Failed');
                    $('#response-container').html('<p>Upload failed.</p>');
                }
            });
        });
    });
</script>