<?php 
class Permintaan_m extends MY_Model
{

  function __construct()
  {
    parent::__construct();
    $this->data['primary_key'] = 'id_permintaan';
    $this->data['table_name'] = 'permintaan';
  }
}

 ?>
