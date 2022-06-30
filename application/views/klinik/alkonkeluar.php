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
                  <li class="breadcrumb-item active" aria-current="page">Data Alkon Masuk</li>
                </ol>
              </nav>
            </div> 
            <div class="col-lg-6 col-5 text-right">
              <a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-neutral">Tambah Data Alkon Keluar</a> 
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
              <h3 class="mb-0">Data Alkon Keluar</h3>
            </div>
            <!-- Light table -->

            <div class="table-responsive py-4">
              <table class="table table-flush" id="datatable-buttons">
                <thead class="thead-light">
                  <tr>  
                    <th>No.</th>
                    <th>Nama Alkon</th>  
                    <th>Tanggal</th>    
                    <th>Kuantiti</th>  
                    <th>Keterangan</th>   
                  </tr>
                </thead>
                <tbody class="list">

                 <?php $i = 1; foreach ($data as $row): ?> 
                  <tr> 
                    <td>
                      <?=$i++?>
                    </td> 
                    <td>
                      <?=$this->Alkon_m->get_row(['id_alkon' => $row->id_alkon])->nama_alkon?>
                    </td> 
                    <td>
                      <?= date('d-m-Y',strtotime($row->tanggal)) ?>
                    </td>    
                    <td>
                      <?=$row->qty?>
                    </td>  
                    <td>
                      <?=$row->keterangan?>
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
        <h5 class="modal-title" id="exampleModalLabel">Form Tambah Alkon Keluar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <?= form_open_multipart('klinik/alkonkeluar/') ?>
      <div class="modal-body">
         
            
            <div class="form-group">
                <label for="example-email-input" class="form-control-label">Nama Alkon</label>
                <select class="form-control" name="id_alkon" required>
                    <option value="">Pilih Alkon</option>
                    <?php  foreach ($alkons as $k): ?>  
                    <?php if ($this->Stok_m->get_num_row(['id_alkon' => $k->id_alkon , 'id_klinik' => $kl->id_klinik] ) != 0 ) { ?>
                      <option value="<?=$k->id_alkon?>"><?=$k->nama_alkon?></option>
                    <?php }  endforeach; ?>
                  </select>
            </div>  

            <div class="form-group">
                <label for="example-email-input" class="form-control-label">Tanggal</label>
                <input class="form-control" type="date" required name="tanggal" max="<?=date('Y-m-d')?>" value="<?=date('Y-m-d')?>" >
            </div> 
            <div class="form-group">
                <label for="example-email-input" class="form-control-label">Kuantiti</label>
                <input class="form-control" type="number" required name="qty" min="1" >
            </div> 
           <div class="form-group">
                <label for="example-email-input" class="form-control-label">Keterangan</label>
                <textarea class="form-control" name="keterangan"></textarea>
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
