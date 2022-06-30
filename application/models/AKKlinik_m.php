<?php 
class AKKlinik_m extends MY_Model
{

  function __construct()
  {
    parent::__construct();
    $this->data['primary_key'] = 'id';
    $this->data['table_name'] = 'alkon_keluar_klinik';
  }

  public function get_d($m, $y , $id, $k){
  	$query = $this->db->query('SELECT sum(qty) as qty FROM `alkon_keluar_klinik` WHERE MONTH(tanggal) = '.$m.' and year(tanggal) = '. $y . ' and id_alkon = ' .$id . ' and id_klinik = ' .$k);
		return $query->row()->qty;
  }
}

 ?>
