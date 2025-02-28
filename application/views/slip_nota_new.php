<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f3f4f6;
        /* Latar belakang halaman */
    }

    .struk-container {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        width: 768px;
        /* Sesuaikan dengan ukuran struk */
    }

    .background-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Pastikan gambar menutupi seluruh area */
        z-index: 1;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        /* Overlay putih semi-transparan */
        z-index: 2;
    }

    .content {
        position: relative;
        z-index: 3;
        /* Letakkan di atas overlay dan gambar */
        padding: 1.5rem;
        /* Sesuaikan dengan padding yang diinginkan */
    }
</style>

<div id="capture" class="slip-gaji">

    <body class="flex justify-center items-center min-h-screen">
        <div class="struk-container">
            <!-- Gambar Latar Belakang -->
            <img src="<?= base_url('template/assets/static/images/bg/Watermark6.jpeg') ?>" alt="Background" class="background-image">

            <!-- Overlay untuk memudahkan membaca teks -->
            <div class="overlay"></div>

            <!-- Konten Struk -->
            <div class="content">
                <!-- Header -->
                <div class="text-center mb-2">
                    <h1 class="text-xl font-bold text-blue-600">SLIP GAJI</h1>
                    <h1 class="text-md font-bold text-blue-600">PP. DARUL LUGHAH WAL KAROMAH</h1>
                    <p class="text-sm text-gray-600">Jl. Pandjaitan No. 12 Sidomukti-Kraksaan-Probolinggo</p>
                </div>

                <!-- Informasi Transaksi -->
                <div class="border-b border-gray-300 pb-3 mb-1">
                    <h3 class="font-semibold text-gray-800 mb-2">Kpd. Yth.</h3>
                    <div class="grid grid-cols-2 gap-4"> <!-- Membuat dua kolom dengan gap 1rem -->
                        <!-- Kolom 1 -->
                        <div>
                            <table class="w-full">
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">Nama</td>
                                    <td class="text-sm">: <?= $data['nama'] ?></td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">Satminkal</td>
                                    <td class="text-sm">: <?= $data['satminkal'] ?></td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">SIK</td>
                                    <td class="text-sm">: <?= $data['sik'] ?></td>
                                </tr>
                            </table>
                        </div>
                        <!-- Kolom 2 -->
                        <div>
                            <table class="w-full">
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">Email</td>
                                    <td class="text-sm">: <?= $data['email'] ?></td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">No. Rek.</td>
                                    <td class="text-sm">: <?= $data['rekening'] ?></td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">Ket</td>
                                    <td class="text-sm">: HR Flat Februari 2025</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Detail Transaksi -->
                <div class="border-b border-gray-300 pb-3 mb-1">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Rincian Honor</h3>
                            <table class="w-full">
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">Gaji Pokok</td>
                                    <td class="text-sm">: <?= $data['sik'] == 'PTY' ? rupiah($data['gapok']) : rupiah(0) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">Honor Insentif</td>
                                    <td class="text-sm">: <?= $data['sik'] == 'PTTY' ? rupiah($data['gapok']) : rupiah(0) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">T. Fungsional</td>
                                    <td class="text-sm">: <?= rupiah($data['fungsional']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">T. Kinerja</td>
                                    <td class="text-sm">: <?= rupiah($data['kinerja']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">T. BPJS</td>
                                    <td class="text-sm">: <?= rupiah($data['bpjs']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">T. Struktural</td>
                                    <td class="text-sm">: <?= rupiah($data['struktural']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">T. Wali Kelas</td>
                                    <td class="text-sm">: <?= rupiah($data['walas']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-medium text-gray-700">T. Penyesuaian</td>
                                    <td class="text-sm">: <?= rupiah($data['penyesuaian']) ?></td>
                                </tr>
                                <?php foreach ($tambahan as $tb): ?>
                                    <tr>
                                        <td class="text-sm font-medium text-gray-700"><?= $tb['nama'] ?></td>
                                        <td class="text-sm">: <?= rupiah($tb['nominal']) ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </table>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Rincian Potongan</h3>
                            <table class="w-full">
                                <?php
                                $totalPotong = 0;
                                foreach ($potongan as $pt):
                                    $totalPotong += $pt['nominal'];
                                ?>
                                    <tr>
                                        <td class="text-sm font-medium text-gray-700"><?= $pt['ket'] ?></td>
                                        <td class="text-sm">: <?= rupiah($pt['nominal']) ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="border-b border-gray-300 pb-3 mb-2">
                    <div class="grid grid-cols-2 gap-4">
                        <?php $totalHonor = $data['gapok'] + $data['fungsional'] + $data['kinerja'] + $data['bpjs'] + $data['struktural'] + $data['walas'] + $data['penyesuaian'] + $data['tambahan'] ?>
                        <div>
                            <table class="w-full">
                                <tr>
                                    <td class="text-lg font-medium text-gray-700">Total Honor</td>
                                    <td class="text-lg font-medium text-gray-700">: <?= rupiah($totalHonor); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-lg font-medium text-gray-700">Total Potongan</td>
                                    <td class="text-lg font-medium text-gray-700">: <?= rupiah($totalPotong); ?></td>
                                </tr>

                            </table>
                        </div>
                        <div>
                            <!-- Jumlah diterima : -->
                            <div class="p-3 bg-blue-50 rounded-lg border border-blue-200 flex justify-between items-center">
                                <p class="text-xl font-bold text-blue-600">Diterima </p>
                                <p class="text-xl font-bold text-blue-600"><?= rupiah($totalHonor - $totalPotong) ?></p>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center text-sm text-gray-600">
                    <p class="mb-2">Semoga Barokah</p>
                    <p class="text-xs text-gray-500">Terimaksih atas pengabdiannya.</p>
                </div>
            </div>
        </div>

</div>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    window.onload = function() {
        var gaji_id = <?= json_encode($data['gaji_id']) ?>;
        var guru_id = <?= json_encode($data['guru_id']) ?>;
        var id_detail = <?= json_encode($detail['id_detail']) ?>;
        setTimeout(() => { // Beri jeda agar halaman sepenuhnya dimuat
            html2canvas(document.querySelector("#capture")).then(canvas => {
                let imageData = canvas.toDataURL("image/png"); // Ubah canvas ke base64
                $.ajax({
                    url: "<?= base_url('informasi/saveNewImage') ?>",
                    type: "POST",
                    data: {
                        image: imageData,
                        gaji_id: gaji_id,
                        guru_id: guru_id,
                        id_detail: id_detail
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            window.history.back();
                            console.error(response.message);
                        } else {
                            console.error(response.message);
                        }
                    },
                    error: function() {
                        console.error("Terjadi kesalahan AJAX");
                    }
                });
            });
        }, 1000);
    };
</script>