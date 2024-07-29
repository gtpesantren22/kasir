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
    }

    public function index()
    {
        $this->load->view('head');
        $this->load->view('send_slipgaji');
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
                $no_hp = $row['E'];
                $pesan = '*Notifikasi Tagihan BRI Smartbilling*
Yth *' . $row['B'] . ',* 
Dengan ini, kami sampaikan bahwa Anda masih belum melunasi tagihan di *PP DARUL LUGHAH WAL KAROMAH* sebesar ' . rupiah($row['D']) . '

Anda dapat menyelesaikan tagihan Anda melalui BRIVA *' . $row['C'] . '* atau metode pembayaran lainnya melalui aplikasi BRI Smart Billing

Terima kasih. 

PP Darul Lughah Wal Karomah

_Jika Anda telah membayar tagihan tersebut, silakan abaikan pesan ini._';
                // Kirim pesan menggunakan curl
                $response = kirim_person2('f4064efa9d05f66f9be6151ec91ad846', $no_hp, $pesan);
                $responses[] = $response;
            }

            echo json_encode(array('status' => 'success', 'responses' => $responses));
        }
    }
}
