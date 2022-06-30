<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Admin extends MY_Controller
{

  function __construct()
  {
    parent::__construct();
  
        $this->data['email'] = $this->session->userdata('email');
        $this->data['id_role']  = $this->session->userdata('id_role'); 
          if (!$this->data['email'] || ($this->data['id_role'] != 1))
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
      $d =  $this->AlkonKeluar_m->get_d($prevmonth,$year,$a->id_alkon);
      $ss = $d/31 * 14;

      $rop = intval(2*($ss));

      if ($a->stok_gudang <= $rop) {
        $this->data['msgrop'] = $this->data['msgrop'] .  "Stok " . $a->nama_alkon . " [" .$a->stok_gudang ."] kurang dari Reorder Point [".$rop."]<br>";
        $this->data['cek']++;
      }

    }
    
  }

public function index()
{    

     $this->data['permintaan'] = $this->Permintaan_m->get_by_order('tgl_permintaan', 'desc' ,['status' => 1]);
      $this->data['index'] = 1;
      $this->data['content'] = 'admin/dashboard';
      $this->template($this->data,'admin');
}
 

public function permintaan()
{     

   if ($this->POST('terima')) { 
        
  
    if ($this->Permintaan_m->update($this->POST('id'),['status' => 2])) {  

      $this->flashmsg2('Permintaan berhasil diverifikasi, Menunggu divisi gudang memproses', 'success');
      redirect('admin/permintaan/'.$this->POST('id'));
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/permintaan/'.$this->POST('id'));
      exit();  
    }
  } 
  else if ($this->POST('tolak')) { 
        
  
    if ($this->Permintaan_m->update($this->POST('id'),['status' => 9 , 'keterangan' => $this->POST('keterangan')])) { 
      $this->flashmsg2('Permintaan berhasil dikirim, sedang menunggu verifikasi admin', 'success');
      redirect('admin/permintaan/'.$this->POST('id'));
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/permintaan/'.$this->POST('id'));
      exit();  
    }
  } 
  elseif ($this->uri->segment(3)) {
    $id = $this->uri->segment(3);
    $this->data['permintaan'] = $this->Permintaan_m->get_row(['id_permintaan' => $id]);
    $this->data['list_alkon'] = $this->Detail_m->get(['id_permintaan' => $id]);
    $this->data['alkons'] = $this->Alkon_m->get(['status' => 1]);
    $this->data['index'] = 2  ;
    $this->data['content'] = 'admin/detaildb';
    $this->template($this->data,'admin');
  }
  else {
    $this->data['permintaan2'] = $this->Permintaan_m->get_by_order('tgl_permintaan', 'desc' ,['status > ' => 1]);
   
    $this->data['index'] = 2  ;
    $this->data['content'] = 'admin/permintaan';
    $this->template($this->data,'admin');
  }
}
 


public function akun()
{     

  if ($this->POST('add')) { 
        
    if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0) {
      $this->flashmsg2('Email telah digunakan!', 'warning');
      redirect('admin/akun/');
      exit();  
    }

     
    $data = [
      'email' => $this->POST('email'), 
      'role' => $this->POST('role'),
      'password' => md5($this->POST('password')) 
    ];

    if ($this->login_m->insert($data)) {
      $this->flashmsg2('Akun berhasil ditambah', 'success');
      redirect('admin/akun/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/akun/');
      exit();  
    }
  }
  elseif ($this->POST('edit')) { 
        
    if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email_x') != $this->POST('email')) {
      $this->flashmsg2('Email telah digunakan!', 'warning');
      redirect('admin/akun/');
      exit();  
    }

   
    $data = [
      'email' => $this->POST('email'), 
      'role' => $this->POST('role')
    ];
    
    

    if ($this->login_m->update($this->POST('email_x'),$data)) {
      $this->flashmsg2('Akun berhasil diedit.', 'success');
      redirect('admin/akun/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/akun/');
      exit();  
    }
  }
  elseif ($this->POST('edit2')) { 
        
    if ($this->POST('password') != $this->POST('password2')) {
      $this->flashmsg2('Konfirmasi password tidak sama!', 'warning');
      redirect('admin/akun/');
      exit();  
    }

   
    $data = [
      'password' => md5($this->POST('password') )
    ];
    
    

    if ($this->login_m->update($this->POST('email'),$data)) {
      $this->flashmsg2('Password '.$this->POST('email'). ' berhasil diganti.', 'success');
      redirect('admin/akun/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/akun/');
      exit();  
    }
  }
  elseif ($this->POST('delete')) {
    if ($this->login_m->delete($this->POST('email'))) {
      $this->flashmsg2('Akun berhasil dihapus.', 'success');
      redirect('admin/akun/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/akun/');
      exit();  
    }
  }
  else {
    $this->data['users'] = $this->login_m->get(['email !=' => $this->data['email']  ]);
    $this->data['index'] = 7;
    $this->data['content'] = 'admin/users';
    $this->template($this->data,'admin');
  }
}
 
 
public function klinik()
{     

  if ($this->POST('add')) { 
    $cek = 0;
    $msg = ''; 
    if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0) {
      $msg = $msg . 'Email telah digunakan!<br>';
      $cek++;
    } 

    if ($cek != 0) {

      $this->flashmsg2($msg, 'warning');
      redirect('admin/klinik/');
      exit();  
    }
     
    $data = [
      'email' => $this->POST('email'), 
      'role' => 3,
      'password' => md5($this->POST('password')) 
    ];

    if ($this->login_m->insert($data)) {

      $d = [ 
        'nama_klinik' =>  $this->POST('nama'),
        'email' => $this->POST('email'),    
        'kontak' => $this->POST('kontak') ,
        'alamat' => $this->POST('alamat') 
      ];

      if ($this->Klinik_m->insert($d)) {
         $this->flashmsg2('Data Klinik berhasil ditambah', 'success');
          redirect('admin/klinik/');
          exit(); 
      }else{
        $this->login_m->delete($this->POST('email'));
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('admin/klinik/');
        exit();  
      }

      
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/klinik/');
      exit();  
    }
  }
  elseif ($this->POST('edit')) { 
         
    $cek = 0;
    $msg = ''; 
    if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email_x') != $this->POST('email')) {
      $msg = $msg . 'Email telah digunakan!<br>';
      $cek++;
    } 

    if ($cek != 0) {

      $this->flashmsg2($msg, 'warning');
      redirect('admin/klinik/');
      exit();  
    }
     
   
    $data = [
      'email' => $this->POST('email') 
    ];
    
    

    if ($this->login_m->update($this->POST('email_x'),$data)) {

      $d = [ 
        'nama_klinik' =>  $this->POST('nama'),
        'email' => $this->POST('email'),   
        'kontak' => $this->POST('kontak') ,
        'alamat' => $this->POST('alamat') 
      ];

      if ($this->Klinik_m->update($this->POST('id_x'), $d)) {
        $this->flashmsg2('Data Klinik berhasil diedit.', 'success');
        redirect('admin/klinik/');
        exit(); 
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('admin/klinik/');
        exit();  
    }

       
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/klinik/');
      exit();  
    } 
  } 
  elseif ($this->POST('delete')) {
    if ($this->login_m->delete($this->POST('email'))) {
      $this->flashmsg2('Data klinik berhasil dihapus.', 'success');
      redirect('admin/klinik/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/klinik/');
      exit();  
    }
  }
  else {
    $this->data['kliniks'] = $this->Klinik_m->get();
    $this->data['index'] = 6;
    $this->data['content'] = 'admin/klinik';
    $this->template($this->data,'admin');
  }
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
          redirect('admin/alkon/');
          exit(); 
      }else{ 
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('admin/alkon/');
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
        redirect('admin/alkon/');
        exit(); 
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('admin/alkon/');
        exit();  
    }
 
  } 
  elseif ($this->POST('delete')) {
    if ($this->Alkon_m->update($this->POST('id'), ['status' => 0])) {
      $this->flashmsg2('Data alkon berhasil dihapus.', 'success');
      redirect('admin/alkon/');
      exit();  
    }else{
      $this->flashmsg2('Gagal, Coba lagi!', 'warning');
      redirect('admin/alkon/');
      exit();  
    }
  }
  else {
    $this->data['alkons'] = $this->Alkon_m->get(['status' => 1]);
    $this->data['index'] = 5;
    $this->data['content'] = 'admin/alkon';
    $this->template($this->data,'admin');
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
          redirect('admin/alkonmasuk/');
          exit(); 
      }else{ 
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('admin/alkonmasuk/');
        exit();  
      }

       
  } 
  else {
    $this->data['data'] = $this->AlkonMasuk_m->get_by_order('tanggal','desc', []);
    $this->data['alkons'] = $this->Alkon_m->get(['status' => 1]);
    $this->data['index'] = 3;
    $this->data['content'] = 'admin/alkonmasuk';
    $this->template($this->data,'admin');
  }
}

public function alkonkeluar()
{      
    $this->data['data'] = $this->AlkonKeluar_m->get_by_order('tanggal','desc', []); 
    $this->data['index'] = 4;
    $this->data['content'] = 'admin/alkonkeluar';
    $this->template($this->data,'admin');
  
}
 
// PROFIL
  public function profile(){
    if ($this->POST('save')) {
      if ($this->login_m->get_num_row(['email' => $this->POST('email')]) != 0 && $this->POST('email') != $this->POST('emailx')) { 
        $this->flashmsg2('Email telah digunakan!', 'warning');
        redirect('admin/profile/');
        exit();  
      }

        if ($this->login_m->update($this->POST('emailx'),['email' => $this->POST('email')])) {
          $user_session = [
            'email' => $this->POST('email')
          ];
          $this->session->set_userdata($user_session);

          $this->flashmsg2('Berhasil!', 'success');
          redirect('admin/profile/');
          exit();  
        }else{
          $this->flashmsg2('Gagal, Coba lagi!', 'warning');
          redirect('admin/profile/');
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
        redirect('admin/profile/');
        exit();  
      }

      $data = [ 
        'password' => md5($this->POST('passwordnew')) 
      ];
      if ($this->login_m->update($this->data['profil']->email, $data)) {
        $this->flashmsg2('Password berhasil diganti!', 'success');
        redirect('admin/profile/');
        exit();  
      }else{
        $this->flashmsg2('Gagal, Coba lagi!', 'warning');
        redirect('admin/profile/');
        exit();  
      } 
    }

    $this->data['index'] = 8;
    $this->data['content'] = 'admin/profile';
    $this->template($this->data,'admin');
  }
  public function proses_edit_profil(){
    if ($this->POST('edit')) {
      
      


      
    } 
    elseif ($this->POST('edit2')) { 
      
      
      $this->login_m->update($this->data['email'],$data);
  
      $this->flashmsg('PASSWORD BARU TELAH TERSIMPAN!', 'success');
      redirect('admin/profil');
      exit();    
    }   
    else{ 
      redirect('admin/profil');
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
