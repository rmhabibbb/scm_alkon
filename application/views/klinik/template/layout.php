<?php
$data =[ 
  'index' => $index
];
$this->load->view('klinik/template/header',$data);
$this->load->view('klinik/template/sidebar',$data);
$this->load->view('klinik/template/navbar');
$this->load->view($content);
$this->load->view('klinik/template/footer');
 ?>
