<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<!-- Main content -->
<section class="content">

  <!-- Main row -->
  <div class="row">
    <!-- Left col -->
    <div class="col-md-6">
      <!-- MAP & BOX PANE --> 

      <button type="button" class="btn btn-danger hidden-md" data-toggle="modal" data-target="#add" style="margin-bottom: 10px"><b>Pembayaran</b></button>
      <?php
        $x = 0; 
        foreach ($data as $key) { ?>
          <div class="box box-default box-solid collapsed-box">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $key[$x]['namaKelas'] ?></h3>
              <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
            </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <th>Nama</th>
                    <th>Biaya Pendidikan</th>
                    <th>Total Pembayaran</th>
                    <th>Sisa Pembayaran</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <?php foreach ($key as $key2) { ?>
                      <tr>
                        <td><?php echo $key2['namaSiswa'] ?></td>
                        <td><?php echo number_format($key2['studentFee'],'0','.','.') ?></td>
                        <td><?php echo number_format($key2['studentFee']-$key2['piutangSiswa'],'0','.','.') ?></td>
                        <td><?php echo number_format($key2['piutangSiswa'],'0','.','.') ?></td>
                        <td id="clickMeId" onclick="hide('<?php echo $key2['namaSiswa']; ?>')"><a class="btn btn-xs btn-info">Show</a></td>
                        <td id="clickMeId" onclick="show('<?php echo $key2['namaSiswa']; ?>')"><a class="btn btn-xs btn-success">Hide</a> 
                      </tr>
                      <tr id="<?php echo $key2['namaSiswa']; ?>" style="display: none;">
                        <td colspan="6">
                          <table class="table table-striped table-bordered">
                            <thead>
                              <tr>
                                <th>Jenis Pembayaran</th>
                                <th>Biaya</th>
                                <th>Total Pembayaran</th>
                                <th>Sisa Pembayaran</th>
                              </tr>
                            </thead>
                            <tbody> 
                              <?php
                                $x = 0;
                                foreach ($key2['detail'] as $listFee) {
                                 ?>
                                  <tr>
                                    <td><?php echo $listFee['namaKategori'] ?></td>
                                    <td><?php echo number_format($listFee['biaya'],'0','.','.') ?></td>
                                    <td><?php echo number_format($listFee['totalPayment'],'0','.','.') ?>
                                    </td>
                                    <td>
                                    <?php 
                                      if (empty($listFee['totalPayment'])) {
                                        echo $listFee['biaya'];
                                      } else {
                                        echo number_format($listFee['biaya']- $listFee['totalPayment'],'0','.','.');
                                      } ?>
                                    </td>
                                  </tr>
                              <?php $x++;} ?>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
      <?php  } ?>

    </div>
    <div class="col-md-6">
      <div class="row">
        <?php if ($data[0][0]['piutangSekolah']) { ?>
          <div class="col-md-12">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php echo 'Rp.'.number_format($data[0][0]['piutangSekolah'],'0','.','.'); ?></h3>
                <p>Total sisa pembayaran</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">Penerimaan <?php echo $data[0][0]['percentageSekolah'].'%'; ?></a>
            </div>
          </div>
        <?php } ?>
      </div>

      <div class="row">
      <?php 
        $x = 0;
        foreach ($data as $key) { ?>
          <div class="col-md-6">
            <div class="info-box bg-blue">
              <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text"><?php echo $key[$x]['namaKelas']; ?></span>
                <span class="info-box-number"><?php echo number_format($key[$x]['piutangRombel'],'0','.','.'); ?></span>
                <div class="progress">
                  <div class="progress-bar" style="width: <?php echo $key[$x]['percentageRombel'] ?>%"></div>
                </div> 
                <span class="progress-description">
                  Penerimaan <?php echo $key[$x]['percentageRombel'].'%'; ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>
      <?php $x++; } ?>
      </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
<script>
  function show(target){
    document.getElementById(target).style.display = 'none';
  }
  function hide(target){
    document.getElementById(target).style.display = '';
  }
</script>

<!-- MODAL ADD -->
<div id="add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Form Pembayaran</h4>
        </div>
        <div class="modal-body form-group">
          <form method="post" action="<?php echo base_url().'piutang/payment_in' ?>">
            <div class="row">
              <div class="col-md-4 col-left">
                <div class="form-group">
                  <label>Nama Siswa</label>
                  <select class="selectpicker form-control" data-live-search="true" name="idpiutangSiswa" id="siswaPiutang" required data-live-search="true">
                    <option value="">Pilih</option>
                    <?php foreach ($siswa as $row) {
                      echo "<option data-tokens='".$row['namaSiswa']."' value='".$row['idpiutangSiswa']."'>".$row['namaSiswa']."</option>";
                    } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Pembayaran :</label>
                  <select name="iddetailPiutangSiswa" id="iddetailPiutangSiswa" class="form-control" required>
                    <option value="">Pilih Pembayaran</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <label>Jumlah :</label>
                    <input type="number" class="form-control" name="amount" required min="1">
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