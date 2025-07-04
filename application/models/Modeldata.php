<?php

class Modeldata extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->sentral = $this->load->database('sentral', true);
        $this->santri = $this->load->database('santri', true);
        $this->flat = $this->load->database('flat', true);
    }

    // Defaul functions
    function getBy($table, $where1, $dtwhere1)
    {
        $this->db->where($where1, $dtwhere1);
        return $this->db->get($table);
    }
    function getBy2($table, $where1, $dtwhere1, $where2, $dtwhere2)
    {
        $this->db->where($where1, $dtwhere1);
        $this->db->where($where2, $dtwhere2);
        return $this->db->get($table);
    }

    function simpan($table, $data)
    {
        $this->db->insert($table, $data);
    }

    function hapus($table, $where, $dtwhere)
    {
        $this->db->where($where, $dtwhere);
        $this->db->delete($table);
    }
    function hapus2($table, $where, $dtwhere, $where2, $dtwhere2)
    {
        $this->db->where($where, $dtwhere);
        $this->db->where($where2, $dtwhere2);
        $this->db->delete($table);
    }

    function getByOrd($table, $where1, $dtwhere1, $ord, $lst)
    {
        $this->db->where($where1, $dtwhere1);
        $this->db->order_by($ord, $lst);
        return $this->db->get($table);
    }

    function edit($table, $where, $dtwhere, $data)
    {
        $this->db->where($where, $dtwhere);
        $this->db->update($table, $data);
    }
    function edit2($table, $where, $dtwhere, $where2, $dtwhere2, $data)
    {
        $this->db->where($where, $dtwhere);
        $this->db->where($where2, $dtwhere2);
        $this->db->update($table, $data);
    }
    function getBySum($table, $where1, $dtwhere1, $sum)
    {
        $this->db->select_sum($sum, 'jml');
        $this->db->where($where1, $dtwhere1);
        return $this->db->get($table);
    }
    function getBy2Sum($table, $where, $dtwhere, $where1, $dtwhere1, $sum)
    {
        $this->db->select_sum($sum, 'jml');
        $this->db->where($where, $dtwhere);
        $this->db->where($where1, $dtwhere1);
        return $this->db->get($table);
    }


    // <==============================================================================>

    // Sentral Functions
    function getByJoinSentral($table1, $table2, $on1, $on2, $where1, $dtwhere1)
    {
        $this->sentral->from($table1);
        $this->sentral->join($table2, 'ON ' . $table1 . '.' . $on1 . ' = ' . $table2 . '.' . $on2);
        $this->sentral->where($where1, $dtwhere1);
        return $this->sentral->get();
    }

    function getBySentral($table, $where1, $dtwhere1)
    {
        $this->sentral->where($where1, $dtwhere1);
        return $this->sentral->get($table);
    }
    function getBy2Sentral($table, $where1, $dtwhere1, $where2, $dtwhere2)
    {
        $this->sentral->where($where1, $dtwhere1);
        $this->sentral->where($where2, $dtwhere2);
        return $this->sentral->get($table);
    }

    function masukSentral($nis, $tahun)
    {
        return $this->sentral->query("SELECT SUM(nominal) AS jml FROM pembayaran WHERE nis = '$nis' AND tahun = '$tahun' GROUP BY nis ");
    }

    // <==============================================================================>

    // Santri Functions
    function editSantri($table, $where, $dtwhere, $data)
    {
        $this->santri->where($where, $dtwhere);
        $this->santri->update($table, $data);
    }

    // <==============================================================================>

    // FLats Functions
    function getGajis()
    {
        $this->flat->from('gaji');
        $this->flat->order_by('tahun', 'DESC');
        $this->flat->order_by('bulan', 'DESC');
        return $this->flat->get();
    }

    function getListGaji($id)
    {
        $this->flat->select('gaji_detail.*, gaji.bulan, gaji.tahun');
        $this->flat->from('gaji_detail');
        $this->flat->join('gaji', 'gaji_detail.gaji_id=gaji.gaji_id');
        $this->flat->where('gaji_detail.gaji_id', $id);
        return $this->flat->get();
    }
    function getRincian($id, $guru_id)
    {
        $this->flat->from('gaji_detail');
        $this->flat->where('gaji_id', $id);
        $this->flat->where('guru_id', $guru_id);
        return $this->flat->get();
    }
    function getTambahan($id, $guru_id)
    {
        $this->flat->from('tambahan_detail');
        $this->flat->where('gaji_id', $id);
        $this->flat->where('guru_id', $guru_id);
        return $this->flat->get();
    }

    function getPotongan($id, $guru_id)
    {
        $gaji = $this->flat->get_where('gaji', ['gaji_id' => $id])->row();

        $this->flat->where('bulan', $gaji->bulan);
        $this->flat->where('tahun', $gaji->tahun);
        $this->flat->where('guru_id', $guru_id);
        return $this->flat->get('potongan');
    }
}
