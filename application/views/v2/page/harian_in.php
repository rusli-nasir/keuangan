<style type="text/css">
  .middle{vertical-align: middle;}
  td{white-space: nowrap;}
  .edit{color:blue;}
  .delete{color:red;}
</style>
<?php 
  date_default_timezone_set('Asia/Jakarta');
  $current_date = date('Y-m-d');
  $now          = $data['tanggal'];
  $sekolah_id   = $this->session->sekolah_id;
  if ($sekolah_id == '9') {
    $sekolah_id = $this->uri->segment('4');
  }
 ?>
<!-- Main content -->
<section class="content">
  <!-- Info boxes -->
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Uang Masuk Harian <?php echo $data['tanggal'].' '.$data['sekolah']; ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if ($this->session->privilege_id == '1') { ?>
          <p>
            <form style="position:absolute; display:flex;" action="<?php echo base_url('v2/main/harian_in/'.$data['sekolah_id']) ?>" method="get">
            <div class="input-group" >
                <input class="form-control" type="text" id="datepicker" required placeholder="Tanggal" name="date">
                <div class="input-group-btn">
                  <button type="submit" class="btn btn-danger">Ok</button>
                </div>
            </div>
            <a target="_blank" style="margin: 0px 12px 0px 12px; width:auto" class="btn btn-warning" href="<?php echo base_url('v2/main/pdf?type=1&id='.$sekolah_id.'&times='.$now.''); ?>">Print</a>
            <button type="button" class="btn btn-success hidden-xs" style="width:auto" data-toggle="modal" data-target="#add2">Penerimaan Lainnya</button>
            <button type="button" class="btn btn-primary hidden-xs" style="margin-left: 12px;width:auto" data-toggle="modal" data-target="#add">Pembayaran</button>
            </form>
          </p>
          <?php } else { ?>
          <a target="_blank" class="btn btn-warning" href="<?php echo base_url('v2/main/pdf?type=1&id='.$sekolah_id.'&times='.$now.''); ?>">Print</a>
          <button type="button" class="btn btn-success hidden-xs" style="margin-left: 12px;" onclick="goBack()">Kembali</button>
          <?php } ?>
          <br class="hidden-lg hidden-small"><br class="hidden-lg hidden-small">
          <table id="example" class="table table-bordered table-striped table-hover">
            <thead> 
              <tr> 
                <th rowspan="2" style="vertical-align: middle;">No</th>
                <!-- <th rowspan="2" style="vertical-align: middle;">Nis</th> -->
                <th rowspan="2" style="vertical-align: middle;">Nama</th>
                <th rowspan="2" style="vertical-align: middle;">Kelas</th>
                <th colspan="12" style="text-align: center">Bulan di Tahun Ajaran <?php echo $tahunAjaran ?></th>
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
                              echo $amountSPP.' (<b>'.$keySPP2['Tahun'].'</b>)';
                            } else{
                              echo $amountSPP;
                            }
                            if ((($current_date == $now) OR !$now) and $this->session->privilege_id == '1') {
                              echo " <a href='".base_url('keuangan/get_payment_id/'.$keySPP2['Id'])."' title='Edit'><i class='fa fa-edit' class='edit'></i></a>";
                              ?>
                              <a onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_harian_in/'.$keySPP2['Id']) ;?>" title='Delete' class="delete"><i class='fa fa-remove'></i></a><br>
                              <?php
                            }
                          }
                          echo "</td>";
                       }
                    }

                    foreach ($key['PaymentNonSPP'] as $keyNonSPP) {
                      echo "<td>";
                        foreach ($keyNonSPP['Data'] as $keyNonSPPDetail) {
                          echo number_format($keyNonSPPDetail['Amount'], 0 , '' , '.' ); 
                          if ((($current_date == $now) OR !$now) and $this->session->privilege_id == '1') {
                            echo " <a href='".base_url('keuangan/get_payment_id/').$keyNonSPPDetail['Id']."' title='Edit'><i class='fa fa-edit' class='edit'></i></a>";
                            ?>
                            <a onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_harian_in/'.$keyNonSPPDetail['Id']) ;?>" title='Delete' class="delete"><i class='fa fa-remove'></i></a>
                            <?php
                          }
                        } 
                      echo "</td>";
                    }
                    echo "<td>".number_format($key['Total'], 0 , '' , '.' )."</td>"
                  ?>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Penerimaan Lainnya</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table id="example2" class="table table-bordered table-striped table-hover">
            <thead> 
             <th>No</th>
             <th>Tanggal</th>
             <!-- <th>Kategori</th> -->
             <th>Keterangan</th>
             <th>Jumlah</th>
            </thead>
            <tbody> 
              <?php 
              $x = 1;
              foreach ($data['paymentLainnya'] as $key) { ?>
                <tr>
                  <td><?php echo $x++; ?></td>
                  <td><?php echo date_format(date_create($key['date_created']),"Y-m-d"); ?></td>
                  <!-- <td><?php //echo $key['kategori_keuangan_lainnya_id'] ?></td> -->
                  <td><?php echo $key['keterangan'] ?></td>
                  <td><?php
                    echo number_format($key['amount'], 0 , '' , '.' ); 
                    if ((($current_date == $now) OR !$now) and $this->session->privilege_id == '1') {
                      echo " <a href='".base_url('keuangan/get_payment_id_lainnya/').$key['id']."' title='Edit'><i class='fa fa-edit' class='edit'></i></a>";
                      ?>
                      <a onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_harian_in_lainnya/'.$key['id']) ;?>" title='Delete' class="delete"><i class='fa fa-remove'></i></a>
                    <?php } ?></td>
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

<!-- MODAL ADD -->
<div id="add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Form Pembayaran</h4>
        </div>
        <div class="modal-body form-group">
          <form method="post" action="<?php echo base_url().'keuangan/payment_in' ?>">
            <div class="row">
              <div class="col-md-6 col-left">
                <div class="form-group">
              <label>Kelas</label>
              <select class="form-control" name="kelas" id="kelas" required>
                <option value="">Pilih</option>
                <?php foreach ($data['kelasJurusan'] as $row_kelas) {
                  echo "<option value='".$row_kelas['id']."'>".$row_kelas['kelas']."</option>";
                } ?>
              </select>
            </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
              <label>Siswa :</label>
              <select name="siswa_id" id="siswa" class="form-control" required>
                <option value="">Pilih Siswa</option>
              </select>
            </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 col-left">
                <div class="form-group">
                <label>Pembayaran Keuangan :</label>
                <select name="kategori_keuangan_id" class="form-control" required id="payment">
                  <option value="">Pilih Pembayaran</option>
                </select>
            </div>
              </div>
              <div class="col-md-3 col-left">
                <div class="form-group">
                <label>Bulan :</label>
                <select name="annualy" class="form-control" required id="annualy">
                  <option value="">Pilih</option>
                </select>
            </div>
              </div>
              <div class="col-md-2 col-left">
                <div class="form-group">
                <label>Tahun :</label>
                <select name="years" class="form-control" required>
                  <option><?php echo date('Y'); ?></option>
                  <option><?php echo date('Y')-1; ?></option>
                  <option><?php echo date('Y')+1; ?></option>
                </select>
            </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                <label>Jumlah :</label>
                <input type="number" class="form-control" name="amount" required>
            </div>
              </div>
            </div>
            <div class="form-group">
          <button type="submit" class="btn btn-default">Simpan</button>
        </div> <!-- /.form-group -->
          </form>
        </div>
    </div>
  </div>
</div>
<!-- END MODAL ADD -->

<div id="add2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Form Penerimaan Lainnya</h4>
        </div>
        <div class="modal-body form-group">
          <form method="post" action="<?php echo base_url('keuangan/payment_in_lainnya') ?>">
            <div class="row">
              <div class="col-md-6 col-left">
                <div class="form-group">
                <label>Penerimaan Keuangan :</label>
                <select name="kategori_keuangan_id" class="form-control" required>
                  <option value="">Pilih</option>
                  <?php foreach ($data['kategoriPenerimaanLainnya'] as $row_lainnya) {
                    echo "<option value='".$row_lainnya['id']."'>".$row_lainnya['nama_kategori']."</option>";
                  } ?>
                </select>
            </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                <label>Jumlah :</label>
                <input type="number" class="form-control" name="amount" required>
            </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 col-left">
                <label>Keterangan</label>
                <textarea class="form-control" required cols="5" rows="5" name="keterangan"></textarea>
              </div>
            </div>
            <div class="form-group" style="margin-top:20px;">
          <button type="submit" class="btn btn-default">Simpan</button>
          <p class="text-red pull-right" style="display: inline;"><b>Khusus penerimaan dana dari Yayasan</b></p>
        </div> <!-- /.form-group -->
          </form>
        </div>
    </div>
  </div>
</div>
<!-- END MODAL ADD -->


<script src="<?php echo baseAdminLte; ?>sweetalert/sweetalert-dev.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo baseAdminLte; ?>sweetalert/sweetalert.css">
<?php 
$sess = $this->session->flashdata('status');
switch ($sess) {
   case 'true':
     ?><script> swal("<?php echo $this->session->flashdata('info') ?>", "", "success")</script><?php
     break;

   case 'false':
     ?><script> swal("<?php echo $this->session->flashdata('info') ?>", "", "error")</script><?php
     break;

   default:
     # code...
     break;
 } ?>