    <!-- Header -->
    <!-- Header -->
    <div class="header   pb-6 bg-purple"  >
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">  
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a   href="<?=base_url('klinik')?>"><i class="fas fa-home"></i></a></li> 
                  <li class="breadcrumb-item active" aria-current="page"><a   href="<?=base_url('klinik/permintaan')?>">Daftar Permintaan </a></li>
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
                  <th>Tanggal  Permintaan</th>
                  <td> : </td>
                  <td><?=$permintaan->tgl_permintaan?></td>
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
            <?php if ($permintaan->status == 0) { ?>
                    <th>Action</th>
              <?php   } ?>
                  </tr>
                </thead>
                <tbody class="list">

                 <?php $i = 1; foreach ($list_alkon as $row): ?> 
                  <tr> 
                    <td>
                      <?=$i++?>
                    </td> 
                    <td>
                      <?=$this->Alkon_m->get_row(['id_alkon' => $row->id_alkon])->nama_alkon?>
                    </td> 
                      
                    <td>
                      <?=$row->qty?>
                    </td>  

            <?php if ($permintaan->status == 0) { ?>
                    <td class="text-right">
                      <center>
                        <a href="" data-toggle="modal" data-target="#delete2-<?=$row->id_alkon?>">
                        <button type="button" class="btn btn-instagram btn-icon"> 
                          <span class="btn-inner--text">Hapus</span>
                        </button>
                      </a>
                      </center>
                       
                    </td>
              <?php  } ?>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <?php if ($permintaan->status == 0) { ?>
            <hr>
             <a href="" data-toggle="modal" data-target="#delete">
                <button type="button" class="btn btn-instagram btn-icon"> 
                  <span class="btn-inner--text">Hapus Permintaan</span>
                </button>
              </a>
               <a href="" data-toggle="modal" data-target="#selesai">
                <button type="button" class="btn btn-twitter btn-icon"> 
                  <span class="btn-inner--text">Kirim Permintaan</span>
                </button>
              </a>
              <?php   }elseif ($permintaan->status == 3) { ?>
                <a href="" data-toggle="modal" data-target="#konfirmasi">
                <button type="button" class="btn btn-twitter btn-icon"> 
                  <span class="btn-inner--text">Konfirmasi Alkon Telah Sampai</span>
                </button>
              </a>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
  


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Form Tambah Alkon yang diminta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <?= form_open_multipart('klinik/permintaan/') ?>
      <div class="modal-body">
         
            <input type="hidden" name="id_permintaan" value="<?=$permintaan->id_permintaan?>">
            <div class="form-group">
                <label for="example-email-input" class="form-control-label">Nama Alkon</label>
                <select class="form-control" name="id_alkon" required>
                    <option value="">Pilih Alkon</option>
                    <?php  foreach ($alkons as $k): ?>  
                    <?php if ($this->Detail_m->get_num_row(['id_alkon' => $k->id_alkon , 'id_permintaan' => $permintaan->id_permintaan] ) == 0 ) { ?>
                      <option value="<?=$k->id_alkon?>"><?=$k->nama_alkon?></option>
                    <?php }  endforeach; ?>
                  </select>
            </div>  
             
            <div class="form-group">
                <label for="example-email-input" class="form-control-label">Kuantiti</label>
                <input class="form-control" type="number" required name="qty" min="1">
            </div>    
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" name="add_alkon" value="Tambah"> 
      </div>
      </form>
    </div>
  </div>
</div> 



<?php $i = 1; foreach ($list_alkon as $row): ?> 
<div class="modal fade" id="delete2-<?=$row->id_alkon?>" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-danger"> 
              
                  <div class="modal-body">
                    
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x"></i>
                          <h4 class="heading mt-4"> Hapus Permintaan Alkon ini? </h4> 
                      </div>
                      
                  </div>
                  
                  <form action="<?= base_url('klinik/permintaan')?>" method="Post" >  
                  <div class="modal-footer">

                  <input type="hidden" name="id_permintaan" value="<?=$permintaan->id_permintaan?>">
                
                   
                      <input type="hidden" value="<?=$row->id?>" name="id">  
                      <input type="submit" class="btn btn-white" name="delete_alkon" value="Ya!">
                     
                      <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
                  </div>
                </form>
          </div>
  </div>
</div>
<?php endforeach; ?>

<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-danger"> 
              
                  <div class="modal-body">
                    
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x"></i>
                          <h4 class="heading mt-4"> Hapus Permintaan ini? </h4> 
                      </div>
                      
                  </div>
                  
                  <form action="<?= base_url('klinik/permintaan')?>" method="Post" >  
                  <div class="modal-footer">

                  <input type="hidden" name="id" value="<?=$permintaan->id_permintaan?>">
                
                     
                      <input type="submit" class="btn btn-white" name="delete" value="Ya!">
                     
                      <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
                  </div>
                </form>
          </div>
  </div>
</div>



<div class="modal fade" id="selesai" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-green"> 
              
                  <div class="modal-body">
                    
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x"></i>
                          <h4 class="heading mt-4"> Kirim Permintaan Sekarang? </h4> 
                      </div>
                      
                  </div>
                  
                  <form action="<?= base_url('klinik/permintaan')?>" method="Post" >  
                  <div class="modal-footer">

                  <input type="hidden" name="id" value="<?=$permintaan->id_permintaan?>">
                
                     
                      <input type="submit" class="btn btn-white" name="selesai" value="Ya!">
                     
                      <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
                  </div>
                </form>
          </div>
  </div>
</div>

<div class="modal fade" id="konfirmasi" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-green"> 
              
                  <div class="modal-body">
                    
                  <form action="<?= base_url('klinik/permintaan')?>" method="Post" >  
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x"></i>
                          <h4 class="heading mt-4"> Apakah alkon telah sampai ke klinik anda ? </h4> 
                           
                      </div>
                      
                  </div>
                  
                  <div class="modal-footer">

                  <input type="hidden" name="id" value="<?=$row->id_permintaan?>">
                
                     
                      <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
                      <input type="submit" class="btn btn-white" name="konfirmasi" value="Konfirmasi">
                     
                  </div>
                </form>
          </div>
  </div>
</div>