<?php 
class AlkonMasuk_m extends MY_Model
{

  function __construct()
  {
    parent::__construct();
    $this->data['primary_key'] = 'id';
    $this->data['table_name'] = 'alkon_masuk';
  }

   public function get_laporan($m,$y){ 
    $query = $this->db->query('select sum(qty) as qty from alkon_masuk where month(tanggal) = ' . $m . ' and year(tanggal) = ' . $y);
    return $query->row()->qty;
  }
}

 ?>
