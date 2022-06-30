<?php 
class Alkon_m extends MY_Model
{

  function __construct()
  {
    parent::__construct();
    $this->data['primary_key'] = 'id_alkon';
    $this->data['table_name'] = 'alkon';
  }
}

 ?>
