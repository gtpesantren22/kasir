<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Informasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->model('Modeldata', 'model');

        $this->key = $this->db->query("SELECT * FROM settings WHERE namaset = 'key' ")->row('isiset');
        $this->url = $this->db->query("SELECT * FROM settings WHERE namaset = 'url' ")->row('isiset');
    }

    public function index()
    {
        // $data['datagaji'] = $this->getGaji($this->key);
        $data['datagaji'] = $this->model->getGajis()->result_array();

        $this->load->view('head');
        $this->load->view('send_slipgaji', $data);
        $this->load->view('foot');
    }


    public function sendProsesTest()
    {

        $config['upload_path'] = './template/assets/static/uploads/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 2048;

        $this->upload->initialize($config);

        if (!is_dir($config['upload_path'])) {
            echo json_encode(array('status' => 'error', 'message' => 'The upload path does not appear to be valid.'));
            return;
        }

        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            echo json_encode(array('status' => 'error', 'message' => $error));
        } else {
            $data = $this->upload->data();
            $file_path = './template/assets/static/uploads/' . $data['file_name'];

            // Load data from Excel file
            $spreadsheet = IOFactory::load($file_path);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // Extract relevant data starting from the 3rd row
            // $data_to_display = array_slice($sheetData, 2);
            $responses = [];
            foreach (array_slice($sheetData, 4) as $row) {
                $no_hp = $row['G'];
                $pesan = '*PP. DARUL LUGHAH WAL KAROMAH*
Sidomukti Kraksaan Probolinggo

Kepada : ' . $row['B'] . '
Email : ' . $row['E'] . '
Rekening : ' . $row['C'] . ' 
Nominal : ' . rupiah((int)$row['D']) . '
Ket : ' . $row['F'] . '

*_Rincian :_*
Honor
---------------------------
- GAPOK	: ' . rupiah($row['H']) . '
- T. FUNGSIONAL	: ' . rupiah($row['I']) . '
- T. KINERJA	: ' . rupiah($row['J']) . '
- T. BPJS	: ' . rupiah($row['K']) . '
- T. STRUKTURAL	: ' . rupiah($row['L']) . '
- T. WALI KELAS	: ' . rupiah($row['M']) . '
- T. PENYESUAIAN	: ' . rupiah($row['N']) . '
Potongan
---------------------------
- BPJS	: ' . rupiah($row['O']) . '
- Infaq TPP	: ' . rupiah($row['P']) . '
- Insijam	: ' . rupiah($row['Q']) . '
- Kalender	: ' . rupiah($row['R']) . '
- Koperasi/Cicilan	: ' . rupiah($row['S']) . '
- Lain-lain	: ' . rupiah($row['T']) . '
- Pinjaman Bank	: ' . rupiah($row['U']) . '
- Pulsa	: ' . rupiah($row['V']) . '
- SIMPOK	: ' . rupiah($row['W']) . '
- SIMWA	: ' . rupiah($row['X']) . '
- Tabungan Wajib	: ' . rupiah($row['Y']) . '
- Verval SIMPATIKA	: ' . rupiah($row['Z']) . '
- Verval TPP	: ' . rupiah($row['AA']) . '

_Terima kasih atas pengabdiannya_';
                // Kirim pesan menggunakan curl
                $response = kirim_person2('f4064efa9d05f66f9be6151ec91ad846', $no_hp, $pesan);
                $responses[] = $response;
            }

            echo json_encode(array('status' => 'success', 'responses' => $responses));
        }
    }

    public function kirimPesanGaji()
    {
        $config['upload_path'] = './template/assets/static/uploads/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 2048;

        $this->upload->initialize($config);

        if (!is_dir($config['upload_path'])) {
            echo json_encode(array('status' => 'error', 'message' => 'The upload path does not appear to be valid.'));
            return;
        }

        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            echo json_encode(array('status' => 'error', 'message' => $error));
        } else {
            $data = $this->upload->data();
            $file_path = './template/assets/static/uploads/' . $data['file_name'];

            // Load data from Excel file
            $spreadsheet = IOFactory::load($file_path);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            foreach (array_slice($sheetData, 3) as $row) {
                $no_hp = $row['G'];
                $pesan = '*PP. DARUL LUGHAH WAL KAROMAH*
Sidomukti Kraksaan Probolinggo

Kepada : ' . $row['B'] . '
Email : ' . $row['E'] . '
Rekening : ' . $row['C'] . ' 
Nominal : ' . rupiah((int)$row['D']) . '
Ket : ' . $row['F'] . '

_Terima kasih atas pengabdiannya_';

                $response = kirim_person2('f4064efa9d05f66f9be6151ec91ad846', $no_hp, $pesan);
                // $responses[] = $response;

                // echo $pesan . '<br>';
            }

            // echo json_encode(array('status' => 'success', 'responses' => $responses));
        }
    }

    public function cobaKirim()
    {
        $response = kirim_person2('f4064efa9d05f66f9be6151ec91ad846', '085236924510', 'Ahmajkhgdfk hdf fjhrfjhfsyu sjhfsfgjgdjg');
        echo $response;
    }

    public function download()
    {
        force_download('./template/assets/static/FORM-UPLOAD-SLIP-GAJI.xlsx', NULL);
    }
    public function downloadTagihan()
    {
        force_download('./template/assets/static/Form_upload_tagihan.xlsx', NULL);
    }

    public function tagihan()
    {
        $this->load->view('head');
        $this->load->view('send_tagihan');
        $this->load->view('foot');
    }

    public function sendTagihan()
    {

        $config['upload_path'] = './template/assets/static/uploads/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 2048;

        $this->upload->initialize($config);

        if (!is_dir($config['upload_path'])) {
            echo json_encode(array('status' => 'error', 'message' => 'The upload path does not appear to be valid.'));
            return;
        }

        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            echo json_encode(array('status' => 'error', 'message' => $error));
        } else {
            $data = $this->upload->data();
            $file_path = './template/assets/static/uploads/' . $data['file_name'];

            // Load data from Excel file
            $spreadsheet = IOFactory::load($file_path);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // Extract relevant data starting from the 3rd row
            // $data_to_display = array_slice($sheetData, 2);
            $responses = [];
            foreach (array_slice($sheetData, 4) as $row) {
                $nis = $row['E'];
                $no_hp = $this->db->query("SELECT hp FROM tb_santri WHERE nis = '$nis' ")->row();
                $pesan = '*Notifikasi Tagihan BRI di PP DARUL LUGHAH WAL KAROMAH*
Yth *' . $row['B'] . ',* 
Dengan ini, kami sampaikan bahwa Anda masih belum melunasi tagihan *BIAYA PENDIDIKAN (BP)* sebesar ' . rupiah($row['D']) . '

Anda dapat menyelesaikan tagihan Anda melalui BRIVA *' . $row['C'] . '* atau metode pembayaran lainnya di kantor Bendahara Pesantren.

Terima kasih. 

PP Darul Lughah Wal Karomah

_Jika Anda telah membayar tagihan tersebut, silakan abaikan pesan ini._';
                // Kirim pesan menggunakan curl
                $response = kirim_person2('f4064efa9d05f66f9be6151ec91ad846', $no_hp->hp, $pesan);
                $responses[] = $response;
            }

            echo json_encode(array('status' => 'success', 'responses' => $responses));
        }
    }



    function getGaji($apiKey)
    {
        $url = $this->url . "list_gaji?key=" . urlencode($apiKey);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Debugging jika error
        if ($error) {
            echo "cURL Error: " . $error;
            return null;
        } elseif ($httpCode !== 200) {
            echo "HTTP Error Code: " . $httpCode;
            return null;
        }

        return json_decode($response, true);
    }

    public function getListGaji($gaji_id, $key)
    {
        $url = $this->url . "gaji_detail?key=" . urlencode($key);

        // Pastikan data dikirim dengan format yang benar
        $data = http_build_query([
            "gaji_id" => $gaji_id
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Debugging
        if ($error) {
            echo "cURL Error: " . $error;
            return null;
        } elseif ($httpCode !== 200) {
            echo "HTTP Error Code: " . $httpCode;
            return null;
        }

        return json_decode($response, true);
    }

    public function getRincian($gaji_id, $guru_id, $key)
    {
        $url = $this->url . "gaji_rinci?key=" . urlencode($key);

        // Pastikan data dikirim dengan format yang benar
        $data = http_build_query([
            "gaji_id" => $gaji_id,
            "guru_id" => $guru_id
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Debugging
        if ($error) {
            echo "cURL Error: " . $error;
            return null;
        } elseif ($httpCode !== 200) {
            echo "HTTP Error Code: " . $httpCode;
            return null;
        }

        return json_decode($response, true);
    }

    public function getPotongan($gaji_id, $guru_id, $key)
    {

        $url = $this->url . "potong_rinci?key=" . urlencode($key);

        // Pastikan data dikirim dengan format yang benar
        $data = http_build_query([
            "gaji_id" => $gaji_id,
            "guru_id" => $guru_id
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Debugging
        if ($error) {
            echo "cURL Error: " . $error;
            return null;
        } elseif ($httpCode !== 200) {
            echo "HTTP Error Code: " . $httpCode;
            return null;
        }

        return json_decode($response, true);
    }

    public function detail_gaji($id)
    {
        $data['datagaji'] = $this->model->getBy('gaji_detail', 'gaji_id', $id)->result();
        $data['gajiId'] = $id;

        $this->load->view('head');
        $this->load->view('detail_gaji', $data);
        $this->load->view('foot');
    }

    public function kirim_slip()
    {
        $id = $this->input->post('id', true);
        // $data = $this->getListGaji($id, $this->key);
        $data = $this->model->getListGaji($id)->result();

        echo json_encode(['data' => $data]);
    }

    // public function buatslip($gaji_id, $guru_id)
    public function buatslip()
    {
        $gaji_id = $this->input->post('gaji_id', true);
        $guru_id = $this->input->post('guru_id', true);

        // $data['data'] = $this->getRincian($gaji_id, $guru_id, $this->key);
        // $data['potongan'] = $this->getPotongan($gaji_id, $guru_id, $this->key);

        $data['data'] = $this->model->getRincian($gaji_id, $guru_id)->row_array();
        $data['tambahan'] = $this->model->getTambahan($gaji_id, $guru_id)->result_array();
        $data['potongan'] = $this->model->getPotongan($gaji_id, $guru_id)->result_array();

        $this->load->view('slip_nota', $data);
    }
    public function newslip($gaji_id, $guru_id)
    {
        $data['detail'] = $this->model->getBy2('gaji_detail', 'gaji_id', $gaji_id, 'guru_id',  $guru_id)->row_array();
        $data['data'] = $this->model->getRincian($gaji_id, $guru_id)->row_array();
        $data['tambahan'] = $this->model->getTambahan($gaji_id, $guru_id)->result_array();
        $data['potongan'] = $this->model->getPotongan($gaji_id, $guru_id)->result_array();

        $this->load->view('slip_nota_new', $data);
    }

    // File: application/controllers/Informasi.php

    public function saveImage()
    {
        // Pastikan request berasal dari AJAX
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $imgData = $this->input->post('image'); // Ambil data gambar dari request
            $gaji_id = $this->input->post('gaji_id'); // Ambil ID gaji
            $guru_id = $this->input->post('guru_id'); // Ambil ID guru
            $nama = $this->input->post('nama'); // Ambil ID guru
            $satminkal = $this->input->post('satminkal'); // Ambil ID guru
            $hp = $this->input->post('hp'); // Ambil ID guru

            // Bersihkan data gambar
            $imgData = str_replace('data:image/png;base64,', '', $imgData);
            $imgData = str_replace(' ', '+', $imgData);
            $imageData = base64_decode($imgData);

            // Buat nama file unik berdasarkan ID gaji dan ID guru
            $filename = 'slip_gaji_' . $gaji_id . '_' . $guru_id . '_' . time() . '.png';
            $filePath = FCPATH . 'template/assets/static/images/nota/' . $filename; // Path lengkap penyimpanan

            // Simpan file gambar
            if (file_put_contents($filePath, $imageData)) {
                $dataSave = [
                    'gaji_id' => $gaji_id,
                    'guru_id' => $guru_id,
                    'nama' => $nama,
                    'satminkal' => $satminkal,
                    'hp' => $hp,
                    'nota' => $filename,
                    'status' => 400,
                ];
                $saveResult = $this->model->simpan('gaji_detail', $dataSave);
                if ($saveResult) {
                    echo json_encode(['status' => 'success', 'Simpan data berhasil']);
                } else {
                    // Bisa tambahkan log error atau informasi lebih detail jika perlu
                    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke database']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan gambar']);
            }
        }
    }

    public function saveNewImage()
    {
        // Pastikan request berasal dari AJAX
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $imgData = $this->input->post('image'); // Ambil data gambar dari request
            $gaji_id = $this->input->post('gaji_id'); // Ambil ID gaji
            $guru_id = $this->input->post('guru_id'); // Ambil ID guru
            $id_detail = $this->input->post('id_detail'); // Ambil ID guru

            // Bersihkan data gambar
            $imgData = str_replace('data:image/png;base64,', '', $imgData);
            $imgData = str_replace(' ', '+', $imgData);
            $imageData = base64_decode($imgData);

            // Buat nama file unik berdasarkan ID gaji dan ID guru
            $filename = 'slip_gaji_' . $gaji_id . '_' . $guru_id . '_' . time() . '.png';
            $path = FCPATH . 'template/assets/static/images/nota/';
            $filePath = $path . $filename; // Path lengkap penyimpanan

            $oldImage = $this->model->getBy('gaji_detail', 'id_detail', $id_detail)->row('nota');

            // Hapus gambar lama jika ada dan file tersebut masih ada di folder
            if ($oldImage && file_exists($path . $oldImage)) {
                unlink($path . $oldImage);
            }

            // Simpan file gambar
            if (file_put_contents($filePath, $imageData)) {
                $dataSave = [
                    'nota' => $filename,
                ];
                $this->model->edit('gaji_detail', 'id_detail', $id_detail, $dataSave);
                if ($this->db->afected_rows() > 0) {
                    echo json_encode(['status' => 'success', 'message' => 'Simpan data berhasil']);
                } else {
                    // Bisa tambahkan log error atau informasi lebih detail jika perlu
                    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke database']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan gambar']);
            }
        }
    }

    public function cekNota()
    {
        $id = $this->input->post('id', true);
        $data = $this->model->getBy('gaji_detail', 'id_detail', $id)->row();
        echo json_encode($data);
    }
    public function resend($id)
    {
        $data = $this->model->getBy('gaji_detail', 'id_detail', $id)->row();
        $kirim = kirim_media('f4064efa9d05f66f9be6151ec91ad846', $data->hp, base_url('template/assets/static/images/nota/' . $data->nota), 0, 'Slip gaji');
        if ($kirim && $kirim['code'] == 200) {
            $this->model->edit('gaji_detail', 'id_detail', $id, ['status' => 200]);
            $this->session->set_flashdata('ok', 'Pengriman pesan berhasil');
            redirect('informasi/detail_gaji/' . $data->gaji_id);
        } else {
            $this->session->set_flashdata('error', 'Pengriman pesan gagal');
            redirect('informasi/detail_gaji/' . $data->gaji_id);
        }
    }
}
