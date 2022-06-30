<?php 
class Klinik_m extends MY_Model
{

  function __construct()
  {
    parent::__construct();
    $this->data['primary_key'] = 'id_klinik';
    $this->data['table_name'] = 'klinik';
  }
}

 ?>
