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
        <?= $this->session->flashdata('msg2') ?>
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
        
      </div>
    </div>  

    <div class="container-fluid mt--6">
      
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <h3 class="mb-0">Daftar Permintaan Alkon Baru</h3>
            </div>
            <!-- Light table -->

            <div class="table-responsive py-4">
              <table class="table table-flush" id="datatable-basic">
                <thead class="thead-light">
                  <tr>  
                    <th>No.</th>   
                    <th>Klinik</th>  
                    <th>Tanggal Permintaan</th>   
                    <th>Status</th>  
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="list">

                 <?php $i = 1; foreach ($permintaan as $row): ?> 
                  <tr> 
                    <td>
                      <?=$i++?>
                    </td> 
                    <td>
                      <?=$this->Klinik_m->get_row(['id_klinik' => $row->id_klinik])->nama_klinik?>
                    </td> 
                    
                    <td>
                      <?=date('d-m-Y',strtotime($row->tgl_permintaan ))?>
                    </td> 
 
                    <td>
                      <?php if ($row->status == 0) {
                        echo "Draft";
                      }elseif ($row->status == 1) {
                        echo "Menunggu Verifikasi ";
                      }elseif ($row->status == 2) {
                        echo "Sedang di proses";
                      }elseif ($row->status == 3) {
                        echo "Sedang Dikirim";
                      }elseif ($row->status == 4) {
                        echo "Selesai";
                      }
                      ?>
                    </td> 
                    
                    <td class="text-right">
                      <a href="<?=base_url('admin/permintaan/'.$row->id_permintaan)?>"  >
                        <button type="button" class="btn btn-twitter btn-icon"> 
                          <span class="btn-inner--text">Verifikasi</span>
                        </button>
                      </a>
                       
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div> 
          </div>
        </div>
      </div>
    </div>

 
