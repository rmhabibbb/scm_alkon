    <!-- Header -->
    <!-- Header -->
    <div class="header   pb-6 bg-purple" >
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">  
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a  href="<?=base_url('admin')?>"><i class="fas fa-home"></i></a></li> 
                  <li class="breadcrumb-item active" aria-current="page">Kelola Alkon</li>
                </ol>
              </nav>
            </div> 
            <div class="col-lg-6 col-5 text-right">
              <a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-neutral">Tambah Alkon</a> 
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
              <h3 class="mb-0">Data Alkon</h3>
            </div>
            <!-- Light table -->

            <div class="table-responsive py-4">
              <table class="table table-flush" id="datatable-basic">
                <thead class="thead-light">
                  <tr>  
                    <th>ID Alkon</th>
                    <th>Nama Alkon</th>  
                    <th>Keterangan</th>    
                    <th>Stok Gudang</th>  
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="list">

                 <?php $i = 1; foreach ($alkons as $row): ?> 
                  <tr> 
                    <td>
                      <?=$row->id_alkon?>
                    </td> 
                    <td>
                      <?=$row->nama_alkon?>
                    </td> 
                    <td>
                      <?=$row->deskripsi?>
                    </td>    
                    <td>
                      <?=$row->stok_gudang?>
                    </td>  
                    
                    <td class="text-right">
                      <a href="" data-toggle="modal" data-target="#edit-<?=$i?>">
                        <button type="button" class="btn btn-twitter btn-icon"> 
                          <span class="btn-inner--text">Edit</span>
                        </button>
                      </a>
                      
                      <a href="" data-toggle="modal" data-target="#delete-<?=$i++?>">
                        <button type="button" class="btn btn-instagram btn-icon"> 
                          <span class="btn-inner--text">Delete</span>
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
  


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Form Tambah Alkon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <?= form_open_multipart('admin/alkon/') ?>
      <div class="modal-body">
         
            
            <div class="form-group">
                <label for="example-email-input" class="form-control-label">Nama Alkon</label>
                <input class="form-control" type="text" required name="nama" >
            </div>  
           
             
            
            <div class="form-group">
                <label for="example-email-input" class="form-control-label">Keterangan</label>
                <textarea class="form-control" name="deskripsi"></textarea>
            </div> 
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" name="add" value="Submit"> 
      </div>
      </form>
    </div>
  </div>
</div>

<?php $i = 1; foreach ($alkons as $row): ?> 
<div class="modal fade" id="edit-<?=$i?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Form Edit Data Alkon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <?= form_open_multipart('admin/alkon/') ?>
      <div class="modal-body">
         
            <div class="form-group">
                <label for="example-email-input" class="form-control-label">ID Alkon</label>
                <input class="form-control" type="text" readonly name="id" value="<?=$row->id_alkon?>">
            </div>  
            <div class="form-group">
                <label for="example-email-input" class="form-control-label">Nama Alkon</label>
                <input class="form-control" type="text" required name="nama" value="<?=$row->nama_alkon?>" >
            </div>   

            <div class="form-group">
                <label for="example-email-input" class="form-control-label">Keterangan</label>
                <textarea class="form-control" name="deskripsi"><?=$row->deskripsi?></textarea>
            </div> 
        
    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" name="edit" value="Submit"> 
      </div>
      </form>
    </div>
  </div>
</div>
 

<div class="modal fade" id="delete-<?=$i++?>" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-danger"> 
              
                  <div class="modal-body">
                    
                      <div class="py-3 text-center">
                          <i class="ni ni-bell-55 ni-3x"></i>
                          <h4 class="heading mt-4"> Hapus Data Alkon ini? </h4> 
                      </div>
                      
                  </div>
                  
                  <form action="<?= base_url('admin/alkon')?>" method="Post" >  
                  <div class="modal-footer">

                   
                      <input type="hidden" required name="id"  value="<?=$row->id_alkon?>"> 
                      <input type="submit" class="btn btn-white" name="delete" value="Ya!">
                     
                      <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
                  </div>
                </form>
          </div>
  </div>
</div>
<?php endforeach; ?>