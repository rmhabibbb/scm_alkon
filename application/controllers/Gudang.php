<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Gudang extends MY_Controller
{

  function __construct()
  {
    parent::__construct();
  
        $this->data['email'] = $this->session->userdata('email');
        $this->data['id_role']  = $this->session->userdata('id_role'); 
          if (!$this->data['email'] || ($this->data['id_role'] != 2))
          {
            $this->flashmsg('<i class="glyphicon glyphicon-remove"></i> Anda harus login terlebih dahulu', 'danger');
            redirect('login');
            exit;
          }  
    
    $this->load->model('login_m');  
    $this->load->model('Alkon_m');   
    $this->load->model('AlkonKeluar_m');   
    $this->load->model('AlkonMasuk_m');   
    $this->load->model('AKKlinik_m');   
    $this->load->model('AMKlinik_m');   
    $this->load->model('Klinik_m');    
    $this->load->model('Detail_m');    
    $this->load->model('Permintaan_m');    
    $this->load->model('Stok_m');      
    
    $this->data['profil'] = $this->login_m->get_row(['email' =>$this->data['email'] ]);   
     
    date_default_timezone_set("Asia/Jakarta");


  }

public function index()
{    
    $this->data['permintaan'] = $this->Permintaan_m->get_by_order('tgl_permintaan', 'desc' ,['status' => 2]);
      $this->data['index'] = 1;
      $this->data['content'] = 'gudang/dashboard';
      $this->template($this->data,'gudang');
}


public function permintaan()
{     

  if ($this->POST('selesai')) { 
    if ($this->Permintaan_m->update($this->POST('id'),['status' => 3])) {  


      $list = $this->Detail_m->get(['id_permintaan' => $this->POST('id')]);

      foreach ($list as $a) {
        $alkon = $this->Alkon_m->get_row(['id_alkon' => $a->id_alkon]);
        $x = $alkon->stok_gudang - $a->qty;


        $this->Alkon_m->update($a->id_alkon,['stok_gudang' => $x]);
        $this->AlkonKeluar_m->insert(['id_alkon' => $a->id_alkon, 'tanggal' => date('Y-m-d H:i:s') , 'qty' => $a->qty , 'keterangan' => 'id permintaan : ' .$this->POST('id') ]);

      }

      $this->flashmsg2('Berhasil konfirmasi, Alkon sedang dikirim!', 'success');
      redirect('gudang/permintaan/'.$this->POST('id'));
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('gudang/permintaan/'.$this->POST('id'));
      exit();  
    }
  }  
  elseif ($this->uri->segment(3)) {
    $id = $this->uri->segment(3);
    $this->data['permintaan'] = $this->Permintaan_m->get_row(['id_permintaan' => $id]);
    $this->data['list_alkon'] = $this->Detail_m->get(['id_permintaan' => $id]);
    $this->data['alkons'] = $this->Alkon_m->get(['status' => 1]);
    $this->data['index'] = 2  ;
    $this->data['content'] = 'gudang/detaildb';
    $this->template($this->data,'gudang');
  }
  else {
    $this->data['permintaan2'] = $this->Permintaan_m->get_by_order('tgl_permintaan', 'desc' ,['status > ' => 2]);
    $this->data['index'] = 2  ;
    $this->data['content'] = 'gudang/permintaan';
    $this->template($this->data,'gudang');
  }
}
 
 
public function alkonmasuk()
{     

  if ($this->POST('add')) {   
      $d = [ 
        'id_alkon' =>  $this->POST('id_alkon'),
        'keterangan' => $this->POST('keterangan'),    
        'qty' => $this->POST('qty'),    
        'tanggal' => $this->POST('tanggal') 
      ];

      if ($this->AlkonMasuk_m->insert($d)) {
         $alkon = $this->Alkon_m->get_row(['id_alkon' => $this->POST('id_alkon')]);

         $x = $alkon->stok_gudang + $this->POST('qty');

         $this->Alkon_m->update($this->POST('id_alkon'), ['stok_gudang' => $x]);

         $this->flashmsg2('Data berhasil ditambah, stok '. $alkon->nama_alkon . ' sekarang di gudang : ' . $x, 'success');
          redirect('gudang/alkonmasuk/');
          exit(); 
      }else{ 
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('gudang/alkonmasuk/');
        exit();  
      }

       
  } 
  else {
    $this->data['data'] = $this->AlkonMasuk_m->get_by_order('tanggal','desc', []);
    $this->data['alkons'] = $this->Alkon_m->get(['status' => 1]);
    $this->data['index'] = 3;
    $this->data['content'] = 'gudang/alkonmasuk';
    $this->template($this->data,'gudang');
  }
}

public function alkonkeluar()
{      
    $this->data['data'] = $this->AlkonKeluar_m->get_by_order('tanggal','desc', []); 
    $this->data['index'] = 4;
    $this->data['content'] = 'gudang/alkonkeluar';
    $this->template($this->data,'gudang');
  
}
 
// PROFIL
  public function profile(){
    if ($this->POST('save')) {
      if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email') != $this->POST('emailx')) { 
        $this->flashmsg2('Email telah digunakan!', 'warning');
        redirect('gudang/profile/');
        exit();  
      }

        if ($this->login_m->update($this->POST('emailx'),['email' => $this->POST('email')])) {
          $user_session = [
            'email' => $this->POST('email')
          ];
          $this->session->set_userdata($user_session);

          $this->flashmsg2('Berhasil!', 'success');
          redirect('gudang/profile/');
          exit();  
        }else{
          $this->flashmsg2('Gagal, Coba lagi!', 'warning');
          redirect('gudang/profile/');
          exit();  
        } 
       

    } 

    if ($this->POST('gpw')) { 

      $cek = 0;
      $msg = ''; 
      if (md5($this->POST('passwordold')) != $this->data['profil']->password) {
        $msg = $msg . 'Password lama salah! <br>';
        $cek++;
      }

      if ($this->POST('passwordnew') != $this->POST('passwordnew2')) {
        $msg = $msg . 'Password baru tidak sama!';
        $cek++;
      }

      if ($cek != 0) {

        $this->flashmsg2($msg, 'warning');
        redirect('gudang/profile/');
        exit();  
      }

      $data = [ 
        'password' => md5($this->POST('passwordnew')) 
      ];
      if ($this->login_m->update($this->data['profil']->email, $data)) {
        $this->flashmsg2('Password berhasil diganti!', 'success');
        redirect('gudang/profile/');
        exit();  
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('gudang/profile/');
        exit();  
      } 
    }

    $this->data['index'] = 5;
    $this->data['content'] = 'gudang/profile';
    $this->template($this->data,'gudang');
  }
  public function proses_edit_profil(){
    if ($this->POST('edit')) {
      
      


      
    } 
    elseif ($this->POST('edit2')) { 
      
      
      $this->login_m->update($this->data['email'],$data);
  
      $this->flashmsg('PASSWORD BARU TELAH TERSIMPAN!', 'success');
      redirect('gudang/profil');
      exit();    
    }   
    else{ 
      redirect('gudang/profil');
      exit();
    } 
  }  
 
  public function cekemail(){ echo $this->login_m->cekemail2($this->input->post('email')); } 
  public function cekpasslama(){ echo $this->login_m->cekpasslama2($this->data['email'],$this->input->post('password')); } 
  public function cekpass(){ echo $this->login_m->cek_password_length2($this->input->post('password')); }
  public function cekpass2(){ echo $this->login_m->cek_passwords2($this->input->post('password'),$this->input->post('password2')); }
// PROFIL
 
}

 ?>
