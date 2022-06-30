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
              <h3 class="mb-0">Laporan Alkon</h3>
            </div>
            <!-- Light table -->

            <div class="table-responsive py-4">
              <table class="table table-flush" id="datatable-buttons">
                <thead class="thead-light">
                  <tr>  
                    <th>No.</th>    
                    <th>Bulan/Tahun</th>   
                    <th>Jumlah Alkon Keluar</th>  
                    <th>Jumlah Alkon Masuk</th>  
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="list">

                 <?php $begin = new DateTime('2021-01-01');
                  $end = new DateTime(date('Y-m-d'));

                  $interval = DateInterval::createFromDateString('1 month');
                  $period = new DatePeriod($begin, $interval, $end);
                  $x = array();
                  foreach ($period as $dt){
                    array_push($x, ['my' => $dt->format("m").$dt->format("Y"), 'm'=> $dt->format("m"), 'y' =>$dt->format("Y")]);
                  }
                  rsort($x)
                 ?>

                 <?php $i = 1; foreach ($x as $dt): ?> 
                  <tr> 
                    <td>
                      <?=$i++?>
                    </td> 
                    <td>
                      <?=$dt['m']?>/<?=$dt['y']?>
                    </td> 
                    
                    <td>
                      <?php 

                      if ($this->AlkonKeluar_m->get_laporan($dt['m'],$dt['y'])) {
                        echo $this->AlkonKeluar_m->get_laporan($dt['m'],$dt['y']);
                      }else{
                        echo "0";
                      }

                      ?> Alkon
                    </td> 

                    <td>
                      <?php 

                      if ($this->AlkonMasuk_m->get_laporan($dt['m'],$dt['y'])) {
                        echo $this->AlkonMasuk_m->get_laporan($dt['m'],$dt['y']);
                      }else{
                        echo "0";
                      }

                      ?> Alkon
                    </td> 
  
                    
                    <td>
                      <a href="<?=base_url('pimpinan/detaillaporan/'.$dt['m'].'/'.$dt['y'])?>"  >
                        <button type="button" class="btn btn-twitter btn-icon"> 
                          <span class="btn-inner--text">Lihat Detail</span>
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

 
