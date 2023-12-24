<?php

class Modeldata extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->sentral = $this->load->database('sentral', true);
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
}
