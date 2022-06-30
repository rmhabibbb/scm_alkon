<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Pimpinan extends MY_Controller
{

  function __construct()
  {
    parent::__construct();
  
        $this->data['email'] = $this->session->userdata('email');
        $this->data['id_role']  = $this->session->userdata('id_role'); 
          if (!$this->data['email'] || ($this->data['id_role'] != 4))
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
      redirect('pimpinan/laporan');
}

public function laporan()
{     
      $this->data['index'] = 2;
      $this->data['content'] = 'pimpinan/laporan';
      $this->template($this->data,'pimpinan');
}

public function detaillaporan()
{     
      $m = $this->uri->segment(3);
      $y = $this->uri->segment(4);

      $this->data['laporan_masuk'] = $this->AlkonMasuk_m->get(['month(tanggal)' => $m , 'year(tanggal)' => $y]);
      $this->data['laporan_keluar'] = $this->AlkonKeluar_m->get(['month(tanggal)' => $m , 'year(tanggal)' => $y]);

      $this->data['m'] = $m;
      $this->data['y'] = $y;
      $this->data['index'] = 2;
      $this->data['content'] = 'pimpinan/detaillaporan';
      $this->template($this->data,'pimpinan');
}

 
public function alkon()
{     

  if ($this->POST('add')) { 
 
 

      $d = [ 
        'nama_alkon' =>  $this->POST('nama'),
        'deskripsi' => $this->POST('deskripsi'),    
        'stok_gudang' => 0 ,
        'status' => 1
      ];

      if ($this->Alkon_m->insert($d)) {
         $this->flashmsg2('Data Alkon berhasil ditambah', 'success');
          redirect('pimpinan/alkon/');
          exit(); 
      }else{ 
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('pimpinan/alkon/');
        exit();  
      }

       
  }
  elseif ($this->POST('edit')) { 
  

      $d = [ 
        'nama_alkon' =>  $this->POST('nama'),
        'deskripsi' => $this->POST('deskripsi')
      ];

      if ($this->Alkon_m->update($this->POST('id'), $d)) {
        $this->flashmsg2('Data Alkon berhasil diedit.', 'success');
        redirect('pimpinan/alkon/');
        exit(); 
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('pimpinan/alkon/');
        exit();  
    }
 
  } 
  elseif ($this->POST('delete')) {
    if ($this->Alkon_m->update($this->POST('id'), ['status' => 0])) {
      $this->flashmsg2('Data alkon berhasil dihapus.', 'success');
      redirect('pimpinan/alkon/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('pimpinan/alkon/');
      exit();  
    }
  }
  else {
    $this->data['alkons'] = $this->Alkon_m->get(['status' => 1]);
    $this->data['index'] = 3;
    $this->data['content'] = 'pimpinan/alkon';
    $this->template($this->data,'pimpinan');
  }
}

 
 
// PROFIL
  public function profile(){
    if ($this->POST('save')) {
      if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email') != $this->POST('emailx')) { 
        $this->flashmsg2('Email telah digunakan!', 'warning');
        redirect('pimpinan/profile/');
        exit();  
      }

        if ($this->login_m->update($this->POST('emailx'),['email' => $this->POST('email')])) {
          $user_session = [
            'email' => $this->POST('email')
          ];
          $this->session->set_userdata($user_session);

          $this->flashmsg2('Berhasil!', 'success');
          redirect('pimpinan/profile/');
          exit();  
        }else{
          $this->flashmsg2('Gagal, Coba lagi!', 'warning');
          redirect('pimpinan/profile/');
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
        redirect('pimpinan/profile/');
        exit();  
      }

      $data = [ 
        'password' => md5($this->POST('passwordnew')) 
      ];
      if ($this->login_m->update($this->data['profil']->email, $data)) {
        $this->flashmsg2('Password berhasil diganti!', 'success');
        redirect('pimpinan/profile/');
        exit();  
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('pimpinan/profile/');
        exit();  
      } 
    }

    $this->data['index'] = 8;
    $this->data['content'] = 'pimpinan/profile';
    $this->template($this->data,'pimpinan');
  }
  public function proses_edit_profil(){
    if ($this->POST('edit')) {
      
      


      
    } 
    elseif ($this->POST('edit2')) { 
      
      
      $this->login_m->update($this->data['email'],$data);
  
      $this->flashmsg('PASSWORD BARU TELAH TERSIMPAN!', 'success');
      redirect('pimpinan/profil');
      exit();    
    }   
    else{ 
      redirect('pimpinan/profil');
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
