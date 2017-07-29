<!-- Main content -->
<section class="content">

  <!-- Main row -->
  <div class="row">
    <!-- Left col -->
    <div class="col-md-6">
      <!-- MAP & BOX PANE -->

          <?php 
            foreach ($data as $key) {
              if ($key['piutangRombel']) { ?>
              <div class="box box-default box-solid collapsed-box">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $key['kelas'] ?></h3>
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
                        <?php 
                        $x = 0;
                        foreach ($key['siswa'] as $siswa) { ?>
                          <tr>
                            <td><?php echo $siswa['nama_siswa'] ?></td>
                            <td><?php echo number_format($siswa['studentFee'],'0','.','.') ?></td>
                            <td><?php echo number_format($siswa['payment'],'0','.','.') ?></td>
                            <td><?php echo number_format($siswa['piutang'],'0','.','.') ?></td>
                            <td id="clickMeId" onclick="hide('<?php echo $siswa['nama_siswa']; ?>')"><a class="btn btn-xs btn-info">Show</a></td>
                            <td id="clickMeId" onclick="show('<?php echo $siswa['nama_siswa']; ?>')"><a class="btn btn-xs btn-success">Hide</a> 
                          </tr>
                          <tr id="<?php echo $siswa['nama_siswa']; ?>" style="display: none;">
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
                                      // print_r($siswa['detail']);
                                      // exit();
                                    foreach ($siswa['detail']['listFee'] as $listFee) {
                                     ?>
                                      <tr>
                                        <td><?php echo $listFee['nama_kategori'] ?></td>
                                        <td><?php echo number_format($listFee['biaya'],'0','.','.') ?></td>
                                        <td>
                                        <?php 
                                          if (empty($siswa['detail']['listPayment'][$x]->totalPayment)) {
                                            echo '0';
                                          } else {
                                            echo number_format($siswa['detail']['listPayment'][$x]->totalPayment,'0','.','.');
                                          } ?>
                                        </td>
                                        <td>
                                        <?php 
                                          if (empty($siswa['detail']['listPayment'][$x]->totalPayment)) {
                                            echo $listFee['biaya'];
                                          } else {
                                            echo number_format($listFee['biaya']- $siswa['detail']['listPayment'][$x]->totalPayment,'0','.','.');
                                          } ?>
                                        </td>
                                      </tr>
                                  <?php $x++;} ?>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        <?php $x++;} ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- /.box-body -->
              </div>
          <?php }} ?>
          
    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-12">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo 'Rp.'.number_format($data['piutangSekolah'],'0','.','.'); ?></h3>
              <p>Total sisa pembayaran</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">Penerimaan <?php echo $data['percentaPenerimaan'].'%'; ?></a>
          </div>
        </div>
      </div>

      <div class="row">
      <?php 
        foreach ($data as $key) {
          if ($key['piutangRombel']) { ?>
          <div class="col-md-6">
            <div class="info-box bg-blue">
              <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text"><?php echo $key['kelas']; ?></span>
                <span class="info-box-number"><?php echo number_format($key['piutangRombel'],'0','.','.'); ?></span>
                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div> 
                <span class="progress-description">
                  Penerimaan <?php echo $key['percentage'].'%'; ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>
      <?php }} ?>
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