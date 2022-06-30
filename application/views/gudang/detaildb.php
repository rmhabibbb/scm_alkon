    <!-- Header -->
    <!-- Header -->
    <div class="header   pb-6 bg-purple"  >
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">  
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a   href="<?=base_url('gudang')?>"><i class="fas fa-home"></i></a></li> 
                  <li class="breadcrumb-item active" aria-current="page"><a   href="<?=base_url('gudang/permintaan')?>">Daftar Permintaan </a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?=$permintaan->id_permintaan?></li>
                </ol>
              </nav>
            </div> 
            <div class="col-lg-6 col-5 text-right">
            <?php if ($permintaan->status == 0) { ?>
              <a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-neutral">Tambah Alkon</a> 
              <?php   } ?>
            </div>
          </div>
        </div>
        <?= $this->session->flashdata('msg2') ?>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            
            <!-- Light table -->
            <div class="card-body">
              <table>
                <tr>
                  <th>Nama  Klinik</th>
                  <td> : </td>
                  <td> <?=$this->Klinik_m->get_row(['id_klinik' => $permintaan->id_klinik])->nama_klinik?></td>
                </tr>
                <tr>
                  <th>Kontak  Klinik</th>
                  <td> : </td>
                  <td> <?=$this->Klinik_m->get_row(['id_klinik' => $permintaan->id_klinik])->kontak?></td>
                </tr>
                <tr>
                  <th>Alamat  Klinik</th>
                  <td> : </td>
                  <td> <?=$this->Klinik_m->get_row(['id_klinik' => $permintaan->id_klinik])->alamat?></td>
                </tr>
                <tr>
                  <th>Tanggal  Permintaan</th>
                  <td> : </td>
                  <td> <?=$permintaan->tgl_permintaan?></td>
                </tr>
                
                <tr>
                  <th>Status  </th>
                  <td> : </td>
                  <td>
                    <?php if ($permintaan->status == 0) {
                        echo "Draft";
                      }elseif ($permintaan->status == 1) {
                        echo "Menunggu Verifikasi ";
                      }elseif ($permintaan->status == 2) {
                        echo "Sedang di proses";
                      }elseif ($permintaan->status == 3) {
                        echo "Sedang Dikirim";
                      }elseif ($permintaan->status == 4) {
                        echo "Selesai";
                      }elseif ($permintaan->status == 9) {
                        echo "Ditolak, " . $permintaan->keterangan;
                      }
                      ?>
                  </td>
                </tr>
              </table>

               <div class="table-responsive py-4">
              <table class="table table-flush" id="datatable-basic">
                <thead class="thead-light">
                  <tr>  
                    <th>No.</th>
                    <th>Nama Alkon</th>  
                    <th>Kuantiti</th>    
 
                  </tr>
                </thead>
                <tbody class="list">

                 <?php $cek = 0 ; $i = 1; foreach ($list_alkon as $row): ?> 
                  <tr> 
                    <td>
                      <?=$i++?>
                    </td> 
                    <td>
                      <?=$this->Alkon_m->get_row(['id_alkon' => $row->id_alkon])->nama_alkon?>
                    </td> 
                      
                    <td>
                      <b 
                      style="color: <?php if ($row->qty > $this->Alkon_m->get_row(['id_alkon' => $row->id_alkon])->stok_gudang) {
                        echo "red";
                        $cek++;
                      }?>" 
                      ><?=$row->qty?></b>
                    </td> 
              
 
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
 
            <?php if ($permintaan->status == 2) { ?>
            <hr>
            <?php if ($cek != 0) {
              echo "<i style='color : red'>Stok digudang tidak mencukupi.</i><br><br>";
            }
            ?>
            
            
              <?php if ($cek != 0) { ?>
                 
                  <button type="button" class="btn btn-twitter btn-icon" disabled> 
                    <span class="btn-inner--text">Kirim Alkon ke Klinik</span>
                  </button>
               
              <?php  }else{ ?>
                <a href="" data-toggle="modal" data-target="#selesai">
                  <button type="button" class="btn btn-twitter btn-icon"> 
                    <span class="btn-inner--text">Kirim Alkon ke Klinik</span>
                  </button>
                </a> 
              <?php }  ?>
              <?php   } ?>
            </div>
          </div>
        </div>
      </div>
  

 


<div class="modal fade" id="selesai" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-green"> 
              
                  <div class="modal-body">
                    
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x"></i>
                          <h4 class="heading mt-4"> Apakah Alkon telah dikirim ke Klinik ?</h4> 
                      </div>
                      
                  </div>
                  
                  <form action="<?= base_url('gudang/permintaan')?>" method="Post" >  
                  <div class="modal-footer">

                  <input type="hidden" name="id" value="<?=$permintaan->id_permintaan?>">
                
                     
                      <input type="submit" class="btn btn-white" name="selesai" value="Ya">
                     
                      <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
                  </div>
                </form>
          </div>
  </div>
</div>