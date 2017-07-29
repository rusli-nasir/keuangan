<style type="text/css">
  .middle{vertical-align: middle;}
  td{white-space: nowrap;}
  .edit{color:blue;}
  .delete{color:red;}
</style>
<?php 
  date_default_timezone_set('Asia/Jakarta');
  $current_date     = date('Y-m-d');
  $now              = $data['tanggal'];
  $kelas_sekolah_id = $this->uri->segment('4');
 ?>
<!-- Main content -->
<section class="content">
  <!-- Info boxes -->
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Uang Masuk <?php echo $data['tanggal'].' '.$data['sekolah']; ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <p>
            <form style="position:absolute; display:flex;" action="<?php echo base_url('v2/main/kelas_in/'.$kelas_sekolah_id) ?>" method="get">
            <a target="_blank" style="width:auto" class="btn btn-warning" href="<?php echo base_url('v2/main/pdf?type=3&id='.$kelas_sekolah_id.'&times='.$now.''); ?>">Print</a>
            <div class="input-group" style="margin-bottom: 0; margin-left: 12px; margin-right: 0; margin-top: 0; position: absolute; width: 200px;">
                <input class="form-control" type="text" id="datepickerMonth" required placeholder="Bulan" name="date">
                <div class="input-group-btn">
                  <button type="submit" class="btn btn-danger">Ok</button>
                </div>
            </div>
            </form>
          </p>
          <br class="hidden-lg hidden-small"><br class="hidden-lg hidden-small">
          <table id="example" class="table table-bordered table-striped table-hover">
            <thead> 
              <tr> 
                <th rowspan="2" style="vertical-align: middle;">No</th>
                <!-- <th rowspan="2" style="vertical-align: middle;">Nis</th> -->
                <th rowspan="2" style="vertical-align: middle;">Nama</th>
                <th rowspan="2" style="vertical-align: middle;">Kelas</th>
                <th colspan="12" style="text-align: center">Bulan</th>
                <?php foreach ($data['kategoriPaymentNonSPP'] as $key) {
                  echo "<th rowspan='2'>".$key['nama_kategori']."</th>";
                }
                  echo "<th rowspan='2'>Total Penerimaan ".number_format($data['totalPenerimaan'], 0 , '' , '.' )."</th>" ?>
              </tr> 
              <tr>
                <?php foreach ($data['bulan'] as $key) {
                  echo "<th style='vertical-align: middle;'>".$key."</th>";
                } ?>
              </tr>
            </thead>
            <tbody> 
              <?php 
              $x = 1;
              foreach ($data['payment'] as $key) { ?>
                <tr>
                  <td><?php echo $x++; ?></td>
                  <!-- <td><?php //echo $key['NIS'] ?></td> -->
                  <td><?php echo $key['Nama'] ?></td>
                  <td><?php echo $key['Kelas'] ?></td>
                  <?php 
                    foreach ($key['SPP'] as $keySPP) {
                      
                      if (!$keySPP['data']) {
                         echo "<td></td>";
                       } else {

                        echo "<td>";
                        foreach ($keySPP['data'] as $keySPP2) {
                          $amountSPP = number_format($keySPP2['Amount'], 0 , '' , '.' );
                          if ($keySPP2['Debt']) {
                              echo $amountSPP.' ('.$keySPP2['Created'].') <b>'.$keySPP2['Tahun'].'</b> <br>';
                            } else{
                              echo $amountSPP.' ('.$keySPP2['Created'].') <br>';
                            }
                        }
                        echo "</td>";
                      }
                    }

                    foreach ($key['PaymentNonSPP'] as $keyNonSPP) {
                      echo "<td>";
                        foreach ($keyNonSPP['Data'] as $keyNonSPPDetail) {
                          $here = number_format($keyNonSPPDetail['Amount'], 0 , '' , '.' ); 
                          echo $here.' ('.$keyNonSPPDetail['Created'].') <br>';
                          if (($current_date == $now) OR !$now) {
                            echo " <a href='".base_url('keuangan/get_payment_id_lainnya/').$keyNonSPPDetail['Id']."' title='Edit'><i class='fa fa-edit' class='edit'></i></a>";
                            ?>
                            <a onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_harian_in_lainnya/'.$keyNonSPPDetail['Id']) ;?>" title='Delete' class="delete"><i class='fa fa-remove'></i></a>
                            <?php
                          } 
                        } 
                      echo "</td>";
                    }
                    echo "<td>".number_format($key['Total'], 0 , '' , '.' )."</td>"
                    // echo "<td>".$key['Total']."</td>"
                  ?>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->