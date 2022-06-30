<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Klinik extends MY_Controller
{

  function __construct()
  {
    parent::__construct();
  
        $this->data['email'] = $this->session->userdata('email');
        $this->data['id_role']  = $this->session->userdata('id_role'); 
          if (!$this->data['email'] || ($this->data['id_role'] != 3))
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
    $this->load->model('Permintaan_m');
    $this->load->model('Detail_m');    
    $this->load->model('Stok_m');      
    
    $this->data['profil'] = $this->login_m->get_row(['email' =>$this->data['email'] ]);   
    $this->data['kl'] = $this->Klinik_m->get_row(['email' =>$this->data['email'] ]);   
    
    date_default_timezone_set("Asia/Jakarta");

    if (date('d') > date('d', strtotime('last day of -1 month')))
    {
        $prevmonth = date('m', strtotime('last day of -1 month'));
        $year = date('Y', strtotime('last day of -1 month'));
    }
    else
    {
        $prevmonth = date('m', strtotime('-1 month'));
        $year = date('Y', strtotime('-1 month'));
    }

    $list_alkon = $this->Alkon_m->get(['status' => 1]); 
 
    $this->data['cek'] = 0;
    $this->data['msgrop'] = "";
    foreach ($list_alkon as $a) {
      $alkon = $this->Stok_m->get_row(['id_alkon' => $a->id_alkon, 'id_klinik' => $this->data['kl']->id_klinik]);
      $d =  $this->AKKlinik_m->get_d($prevmonth,$year,$a->id_alkon, $this->data['kl']->id_klinik);
      $ss = round($d/31 * 14);

      $rop = round(2*($ss)); 
      if ($alkon->stok <= $rop) {
        $this->data['msgrop'] = $this->data['msgrop'] .  "Stok " . $a->nama_alkon . " [" .$alkon->stok ."] kurang dari Reorder Point [".$rop."]<br>";
        $this->data['cek']++;
      }

    } 
 
  }

public function index()
{    
      $this->data['index'] = 1;
      $this->data['content'] = 'klinik/dashboard';
      $this->template($this->data,'klinik');
}


public function permintaan()
{     

  if ($this->POST('add')) {  
    $data = [  
      'tgl_permintaan' => date('Y-m-d H:i:s'), 
      'id_klinik' => $this->data['kl']->id_klinik, 
      'status' => 0
    ];

    if ($this->Permintaan_m->insert($data)) {
      $id = $this->db->insert_id();
      $this->flashmsg2('Form Permintaan berhasil dibuat', 'success');
      redirect('klinik/permintaan/'.$id);
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('klinik/permintaan/');
      exit();  
    }
  }elseif ($this->POST('add_alkon')) { 
        
 
     
    $data = [
      'id_permintaan' => $this->POST('id_permintaan'),  
      'id_alkon' => $this->POST('id_alkon'),  
      'qty' => $this->POST('qty') 
    ];

    if ($this->Detail_m->insert($data)) { 
      $this->flashmsg2('berhasil ditambah', 'success');
      redirect('klinik/permintaan/'.$this->POST('id_permintaan'));
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('klinik/permintaan/'.$this->POST('id_permintaan'));
      exit();  
    }
  } elseif ($this->POST('selesai')) { 
        
  
    if ($this->Permintaan_m->update($this->POST('id'),['status' => 1])) { 
      $this->flashmsg2('Permintaan berhasil dikirim, sedang menunggu verifikasi admin', 'success');
      redirect('klinik/permintaan/'.$this->POST('id'));
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('klinik/permintaan/'.$this->POST('id'));
      exit();  
    }
  } 
  elseif ($this->POST('delete')) {
    if ($this->Permintaan_m->delete($this->POST('id'))) {
      $this->flashmsg2('Permintaan berhasil dihapus.', 'success');
      redirect('klinik/permintaan/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('klinik/permintaan/');
      exit();  
    }
  }elseif ($this->POST('delete_alkon')) {
    if ($this->Detail_m->delete($this->POST('id'))) {
      $this->flashmsg2('alkon berhasil dihapus.', 'success');
      redirect('klinik/permintaan/'.$this->POST('id_permintaan'));
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('klinik/permintaan/'.$this->POST('id_permintaan'));
      exit();  
    }
  }elseif ($this->POST('konfirmasi')) { 
    if ($this->Permintaan_m->update($this->POST('id'),['status' => 4])) {  


      $list = $this->Detail_m->get(['id_permintaan' => $this->POST('id')]);

      foreach ($list as $a) {
        $alkon = $this->Stok_m->get_row(['id_alkon' => $a->id_alkon, 'id_klinik' => $this->data['kl']->id_klinik]);
        $x = $alkon->stok + $a->qty;


        if ($alkon) {
          $this->Stok_m->update($alkon->id,['stok' => $x]);
        }else{
          $this->Stok_m->insert(['id_klinik' => $this->data['kl']->id_klinik, 'id_alkon' => $a->id_alkon, 'stok' => $x]);
        }
        
        $this->AMKlinik_m->insert(['id_alkon' => $a->id_alkon, 'tanggal' => date('Y-m-d H:i:s') , 'qty' => $a->qty , 'keterangan' => 'id permintaan : ' .$this->POST('id'), 'id_klinik' => $this->data['kl']->id_klinik ]);

      }

      $this->flashmsg2('Berhasil konfirmasi, Permintaan selesai!', 'success');
      redirect('klinik/permintaan/'.$this->POST('id'));
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('klinik/permintaan/'.$this->POST('id'));
      exit();  
    }
  }  
  elseif ($this->uri->segment(3)) {
    $id = $this->uri->segment(3);
    $this->data['permintaan'] = $this->Permintaan_m->get_row(['id_permintaan' => $id]);
    $this->data['list_alkon'] = $this->Detail_m->get(['id_permintaan' => $id]);
    $this->data['alkons'] = $this->Alkon_m->get(['status' => 1]);
    $this->data['index'] = 2  ;
    $this->data['content'] = 'klinik/detaildb';
    $this->template($this->data,'klinik');
  }
  else {
    $this->data['permintaan'] = $this->Permintaan_m->get_by_order('tgl_permintaan', 'desc' ,[]);
    $this->data['index'] = 2  ;
    $this->data['content'] = 'klinik/permintaan';
    $this->template($this->data,'klinik');
  }
}
 

 

public function stok()
{     
 
    $this->data['alkons'] = $this->Alkon_m->get(['status' => 1]);
    $this->data['index'] = 5;
    $this->data['content'] = 'klinik/stok';
    $this->template($this->data,'klinik');
  
}

public function alkonmasuk()
{      
    $this->data['data'] = $this->AMKlinik_m->get_by_order('tanggal','desc', []);
    $this->data['index'] = 3;
    $this->data['content'] = 'klinik/alkonmasuk';
    $this->template($this->data,'klinik');
 
}

public function alkonkeluar()
{       
  if ($this->POST('add')) { 
      

      if ($this->POST('qty') > $this->Stok_m->get_row(['id_alkon' => $this->POST('id_alkon') , 'id_klinik' => $this->data['kl']->id_klinik] )->stok) {
        $this->flashmsg2('Stok tidak mencukupi', 'warning');
        redirect('klinik/alkonkeluar/');
        exit(); 
      }
      $d = [ 
        'id_alkon' =>  $this->POST('id_alkon'),
        'id_klinik' => $this->data['kl']->id_klinik,
        'keterangan' => $this->POST('keterangan'),    
        'qty' => $this->POST('qty'),    
        'tanggal' => $this->POST('tanggal') 
      ];

      if ($this->AKKlinik_m->insert($d)) {
         $alkon = $this->Stok_m->get_row(['id_alkon' => $this->POST('id_alkon') , 'id_klinik' => $this->data['kl']->id_klinik] );

         $x = $alkon->stok - $this->POST('qty');

         $this->Stok_m->update($alkon->id, ['stok' => $x]);

         $this->flashmsg2('Berhasil, stok '. $alkon->nama_alkon . ' sekarang di gudang : ' . $x, 'success');
          redirect('klinik/alkonkeluar/');
          exit(); 
      }else{ 
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('klinik/alkonkeluar/');
        exit();  
      }

       
  } 
  else {
    $this->data['data'] = $this->AKKlinik_m->get_by_order('tanggal','desc', []); 
    $this->data['alkons'] = $this->Alkon_m->get(['status' => 1]);
    $this->data['index'] = 4;
    $this->data['content'] = 'klinik/alkonkeluar';
    $this->template($this->data,'klinik');
  } 
}
 
// PROFIL
  public function profile(){
    if ($this->POST('save')) {
      if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email') != $this->POST('emailx')) { 
        $this->flashmsg2('Email telah digunakan!', 'warning');
        redirect('klinik/profile/');
        exit();  
      }

        if ($this->login_m->update($this->POST('emailx'),['email' => $this->POST('email')])) {
          $user_session = [
            'email' => $this->POST('email')
          ];
          $this->session->set_userdata($user_session);

          $this->flashmsg2('Berhasil!', 'success');
          redirect('klinik/profile/');
          exit();  
        }else{
          $this->flashmsg2('Gagal, Coba lagi!', 'warning');
          redirect('klinik/profile/');
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
        redirect('klinik/profile/');
        exit();  
      }

      $data = [ 
        'password' => md5($this->POST('passwordnew')) 
      ];
      if ($this->login_m->update($this->data['profil']->email, $data)) {
        $this->flashmsg2('Password berhasil diganti!', 'success');
        redirect('klinik/profile/');
        exit();  
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('klinik/profile/');
        exit();  
      } 
    }

    $this->data['index'] = 8;
    $this->data['content'] = 'klinik/profile';
    $this->template($this->data,'klinik');
  }
  public function proses_edit_profil(){
    if ($this->POST('edit')) {
      
      


      
    } 
    elseif ($this->POST('edit2')) { 
      
      
      $this->login_m->update($this->data['email'],$data);
  
      $this->flashmsg('PASSWORD BARU TELAH TERSIMPAN!', 'success');
      redirect('klinik/profil');
      exit();    
    }   
    else{ 
      redirect('klinik/profil');
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
