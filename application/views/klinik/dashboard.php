 <!-- Header -->
    <div class="header  pb-6 bg-purple" >
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              
              <h6 class="h2 text-white d-inline-block mb-0">Beranda</h6>
               
            </div>
            
          </div> 
        </div>
        <?php if ($cek != 0) { ?>
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <span class="alert-icon"><i class="ni ni-bell-55"></i></span>
            <span class="alert-text"><strong>Reorder Point!</strong> <br>
              <?=$msgrop?>
              <br>
              <strong>Silahkan lakukan pengadaan kembali.</strong>
            </span> 
          </div>
        <?php } ?>
        <?= $this->session->flashdata('msg2') ?>
      </div>
    </div>  

    <div class="container-fluid mt--6">
      <div class="row justify-content-center">
        <div class="col-lg-12 card-wrapper">
          <div class="card"> 
            <div class="card-body">
              
              <div class="row py-3 align-items-center"> 
                <div class="col-sm-12">
                  <div>
                    <h2 class="display-3">Selamat Datang, <?=$kl->nama_klinik?> </h2>
                    <p class="lead text-muted">Sistem Informasi Persedian Alat Kontrasepsi DPPKB Kabupaten Muara Enim.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

 
