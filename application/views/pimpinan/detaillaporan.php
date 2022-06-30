 <!-- Header -->
    <div class="header  pb-6 bg-purple" >
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
           
          </div> 
        </div>
        <?= $this->session->flashdata('msg2') ?>
      </div>
    </div>  

    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col"> 
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <h3 class="mb-0">Laporan Alkon Keluar - <?=$m?>/<?=$y?></h3>
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

                 <?php $i = 1; foreach ($laporan_keluar as $row): ?> 
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

          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <h3 class="mb-0">Laporan Alkon Masuk - <?=$m?>/<?=$y?></h3>
            </div>
            <!-- Light table -->

            <div class="table-responsive py-4">
              <table class="table table-flush" id="datatable-buttons2">
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

                 <?php $i = 1; foreach ($laporan_masuk as $row): ?> 
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

 
