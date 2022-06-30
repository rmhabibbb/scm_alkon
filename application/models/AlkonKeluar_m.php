<?php 
class AlkonKeluar_m extends MY_Model
{

  function __construct()
  {
    parent::__construct();
    $this->data['primary_key'] = 'id';
    $this->data['table_name'] = 'alkon_keluar';
  }

  public function get_d($m, $y , $id){
  	$query = $this->db->query('SELECT sum(qty) as qty FROM `alkon_keluar` WHERE MONTH(tanggal) = '.$m.' and year(tanggal) = '. $y . ' and id_alkon = ' .$id );
		return $query->row()->qty;
  }

  public function get_laporan($m,$y){ 
    $query = $this->db->query('select sum(qty) as qty from alkon_keluar where month(tanggal) = ' . $m . ' and year(tanggal) = ' . $y);
    return $query->row()->qty;
  }
}

 ?>
