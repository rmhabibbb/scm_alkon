    <!-- Header -->
    <!-- Header -->
    <div class="header   pb-6 bg-purple" >
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">  
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a   href="<?=base_url('admin')?>"><i class="fas fa-home"></i></a></li> 
                  <li class="breadcrumb-item active" aria-current="page"><a   href="<?=base_url('admin/permintaan')?>">Daftar Permintaan </a></li>
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

            <?php if ($permintaan->status == 1) { ?>
                    <th>Stok di Gudang</th> 
              <?php   } ?>
          
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
                    <?php if ($permintaan->status == 1) { ?>
                    <th><?=$this->Alkon_m->get_row(['id_alkon' => $row->id_alkon])->stok_gudang?></th> 
                    <?php   } ?>

 
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
 
            <?php if ($permintaan->status == 1) { ?>
            <hr>
            <?php if ($cek != 0) {
              echo "<i style='color : red'>Stok digudang tidak mencukupi.</i><br><br>";
            }
            ?>
            
             <a href="" data-toggle="modal" data-target="#delete">
                <button type="button" class="btn btn-instagram btn-icon"> 
                  <span class="btn-inner--text">Tolak</span>
                </button>
              </a>
              <?php if ($cek != 0) { ?>
                 
                  <button type="button" class="btn btn-twitter btn-icon" disabled> 
                    <span class="btn-inner--text">Terima, Proses Permintaan</span>
                  </button>
               
              <?php  }else{ ?>
                <a href="" data-toggle="modal" data-target="#selesai">
                  <button type="button" class="btn btn-twitter btn-icon"> 
                    <span class="btn-inner--text">Terima, Proses Permintaan</span>
                  </button>
                </a> 
              <?php }  ?>
              <?php   } ?>
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


 
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-danger"> 
              
                  <div class="modal-body">
                    
                  <form action="<?= base_url('admin/permintaan')?>" method="Post" >  
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x"></i>
                          <h4 class="heading mt-4"> Tolak Permintaan ? </h4> 
                    <textarea class="form-control" name="keterangan" placeholder="Keterangan ..."></textarea>
                      </div>
                      
                  </div>
                  
                  <div class="modal-footer">
                  <input type="hidden" name="id" value="<?=$permintaan->id_permintaan?>">
                
                     
                      <input type="submit" class="btn btn-white" name="tolak" value="Tolak">
                     
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
                          <h4 class="heading mt-4"> Terima dan Proses Permintaan ? </h4> 
                      </div>
                      
                  </div>
                  
                  <form action="<?= base_url('admin/permintaan')?>" method="Post" >  
                  <div class="modal-footer">

                  <input type="hidden" name="id" value="<?=$permintaan->id_permintaan?>">
                
                     
                      <input type="submit" class="btn btn-white" name="terima" value="Terima">
                     
                      <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
                  </div>
                </form>
          </div>
  </div>
</div>