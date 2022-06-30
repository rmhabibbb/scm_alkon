    <!-- Header -->
    <!-- Header -->
    <div class="header   pb-6 bg-purple" >
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">  
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a  href="<?=base_url('klinik')?>"><i class="fas fa-home"></i></a></li> 
                  <li class="breadcrumb-item active" aria-current="page">Permintaan Alkon</li>
                </ol>
              </nav>
            </div> 
            <div class="col-lg-6 col-5 text-right">
              <a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-neutral">Buat Permintaan Alkon</a> 
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
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <h3 class="mb-0">Daftar Permintaan</h3>
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
                      <?=$kl->nama_klinik?>
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
                      }elseif ($row->status == 9) {
                        echo "Ditolak, " . $row->keterangan;
                      }
                      ?>
                    </td> 
                    
                    <td class="text-right">
                      <a href="<?=base_url('klinik/permintaan/'.$row->id_permintaan)?>"  >
                        <button type="button" class="btn btn-block btn-twitter btn-icon"> 
                          <span class="btn-inner--text">Lihat</span>
                        </button>
                      </a>
                      <?php if ($row->status == 3) { ?>
                        <br style="margin-top: 5px">
                        <a href="" data-toggle="modal" data-target="#selesai-<?=$row->id_permintaan?>">
                          <button type="button" class="btn btn-block bg-green text-white btn-icon"> 
                            <span class="btn-inner--text">Alkon telah sampai ?</span>
                          </button>
                        </a>
                      <?php } ?>
                       
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div> 
          </div>
        </div>
      </div>
  


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Form Permintaan Alkon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <?= form_open_multipart('klinik/permintaan/') ?>
      <div class="modal-body">
         
          
            <div class="form-group">
                <label for="example-email-input" class="form-control-label">Tanggal Permintaan</label>
                <input class="form-control" type="text" required name="tgl_permintaan"  value="<?=date('Y-m-d')?>" readonly>
            </div>  
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" name="add" value="Buat"> 
      </div>
      </form>
    </div>
  </div>
</div> 

<?php $i = 1; foreach ($permintaan as $row): ?> 
<?php if ($row->status == 3) { ?> 
<div class="modal fade" id="selesai-<?=$row->id_permintaan?>" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-green"> 
              
                  <div class="modal-body">
                    
                  <form action="<?= base_url('klinik/permintaan')?>" method="Post" >  
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x"></i>
                          <h4 class="heading mt-4"> Apakah alkon telah sampai ke klinik anda ? </h4> 
                            <div class="table-responsive py-4">
                                <table class="table">
                                    <thead class="thead-light">
                                      <tr>  
                                        <th>No.</th>
                                        <th>Nama Alkon</th>  
                                        <th>Kuantiti</th>    
                     
                                      </tr>
                                    </thead>
                                    <tbody class="list">

                                     <?php $list_alkon = $this->Detail_m->get(['id_permintaan' => $row->id_permintaan]);  foreach ($list_alkon as $s): ?> 
                                      <tr> 
                                        <td style="color: white">
                                          <?=$i++?>
                                        </td> 
                                        <td style="color: white">
                                          <?=$this->Alkon_m->get_row(['id_alkon' => $s->id_alkon])->nama_alkon?>
                                        </td> 
                                          
                                        <td style="color: white">
                                           <?=$s->qty?> 
                                        </td> 
                                       
                                      </tr>
                                      <?php endforeach; ?>
                                    </tbody>
                                  </table>
                                </div>

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

<?php } endforeach; ?>