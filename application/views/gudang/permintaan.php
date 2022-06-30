    <!-- Header -->
    <!-- Header -->
    <div class="header   pb-6 bg-purple" >
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">  
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a   href="<?=base_url('klinik')?>"><i class="fas fa-home"></i></a></li> 
                  <li class="breadcrumb-item active" aria-current="page">Permintaan Alkon</li>
                </ol>
              </nav>
            </div> 
            <div class="col-lg-6 col-5 text-right">
              <a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-neutral">Buat Permintaan Alkon</a> 
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
            <div class="card-header border-0">
              <h3 class="mb-0">Riwayat Permintaan</h3>
            </div>
            <!-- Light table -->

            <div class="table-responsive py-4">
              <table class="table table-flush" id="datatable-buttons">
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

                 <?php $i = 1; foreach ($permintaan2 as $row): ?> 
                 <?php if ($row->status != 9) { ?>
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
                      }elseif ($row->status == 9) {
                        echo "Ditolak, " . $row->keterangan;
                      }
                      ?>
                    </td> 
                    
                    <td class="text-right">
                      <a href="<?=base_url('gudang/permintaan/'.$row->id_permintaan)?>"  >
                        <button type="button" class="btn btn-twitter btn-icon"> 
                          <span class="btn-inner--text">Lihat</span>
                        </button>
                      </a>
                       
                    </td>
                  </tr>
                  <?php } endforeach; ?>
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