<?php
$data =[ 
  'index' => $index
];
$this->load->view('gudang/template/header',$data);
$this->load->view('gudang/template/sidebar',$data);
$this->load->view('gudang/template/navbar');
$this->load->view($content);
$this->load->view('gudang/template/footer');
 ?>
