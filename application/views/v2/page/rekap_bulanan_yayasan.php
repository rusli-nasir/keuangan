<style type="text/css">
td{white-space: nowrap;}
.delete{color: red;}
.edit{color: blue;}
</style>
<!-- Main content -->
<section class="content">
  <!-- Info boxes -->
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><?php echo $title; ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <!-- <p>
            <form style="display:flex;" action="<?php //echo base_url('v2/main/rekap_harian_yayasan') ?>" method="get">
            <div class="input-group" style="margin-bottom: 0; margin-left: 12px; margin-right: 0; margin-top: 0; width: 200px;">
                <input class="form-control" type="text" id="datepicker" required placeholder="Tanggal" name="date">
                <div class="input-group-btn">
                  <button type="submit" class="btn btn-danger">Ok</button>
                </div>
            </div>
            </form>
          </p> -->
          <?php switch ($this->uri->segment('4')) {
            case '2015-2016':
              $actived1 = $actived;
              $actived2 = $unactive;
              break;
            
            case '2016-2017':
              $actived2 = $actived;
              $actived1 = $unactive;
              break;

            default:
              $actived2 = $actived;
              $actived1 = $unactive;
              break;
          } ?>
          <p>
            <a href="<?php echo base_url('v2/main/rekap_bulanan_yayasan/2015-2016') ?>"><button type="button" id="Button1" class="btn btn-flat <?php echo $actived1; ?>">2015-2016</button></a>
            <a href="<?php echo base_url('v2/main/rekap_bulanan_yayasan/2016-2017') ?>">
            <button type="button" id="Button2" class="btn btn-flat <?php echo $actived2; ?>">2016-2017</button></a>
          </p>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
               <thead>
                <th>Bulan</th>
                <th>Keterangan</th>
                <?php foreach ($list_sekolah as $key) {
                  echo "<th>".$key['nama_sekolah']."</th>";
                } ?>
                <th>Jumlah</th>
              </thead>
              <tbody>
                <?php foreach ($data as $key) { ?>
                  <tr>
                    <td rowspan="3"><?php echo $key['bulan'] ?></td>
                    <td>Uang Masuk</td>
                    <?php foreach ($key['buff'] as $key2) { ?>
                      <td>
                        <a href="<?php echo base_url('v2/main/bulanan_in/'.$key2['idSekolah'].'/'.$key['bulanInCode']) ?>">
                        <?php echo number_format($key2['Uang Masuk'], 0 , '' , '.' ) ?></a> 
                      </td>
                    <?php } ?>
                    <td><?php echo number_format($key['totalMasuk'], 0 , '' , '.' ) ?></td>
                  </tr>
                  <tr>
                    <!-- <td></td> -->
                    <td>Uang Keluar</td>
                    <?php foreach ($key['buff'] as $key2) { ?>
                      <td>
                        <a href="<?php echo base_url('v2/main/bulanan_out/'.$key['bulanInCode'].'/'.$key2['idSekolah']) ?>">
                        <?php echo number_format($key2['Uang Keluar'], 0 , '' , '.' ) ?></a></td>
                    <?php } ?>
                    <td><?php echo number_format($key['totalKeluar'], 0 , '' , '.' ) ?></td>
                  </tr>
                  <tr>
                    <!-- <td></td> -->
                    <td>Selisih</td>
                    <?php foreach ($key['buff'] as $key2) {
                      if ($key2['Selisih'] >= 0) { ?>
                        <td><i><?php echo number_format($key2['Selisih'], 0 , '' , '.' ) ?></i></td>
                      <?php } else { ?>
                        <td class="bg-red disabled color-palette"><i><?php echo number_format($key2['Selisih'], 0 , '' , '.' ) ?></i></td>
                      <?php } ?>
                    <?php } ?>
                    <td class="bg-yellow disabled color-palette"><b><i><?php echo number_format($key['totalSelisih'], 0 , '' , '.' ) ?></i></b></td>
                  </tr>
                <?php } ?>
                <tr>
                  <td rowspan="3">Total</td>
                  <td>Uang Masuk / Tahun</td>
                  <?php foreach ($total as $key) { ?>
                    <td class="bg-light-blue disabled color-palette"><?php echo number_format($key['Uang Masuk'], 0 , '' , '.' ) ?></td>
                  <?php } ?>
                  <td class="bg-light-blue color-palette"><b><?php echo number_format($allTotal['Uang Masuk'], 0 , '' , '.' ) ?></b></td>
                </tr>
                <tr>
                  <td>Uang Keluar / Tahun</td>
                  <?php foreach ($total as $key) { ?>
                    <td class="bg-green disabled color-palette"><?php echo number_format($key['Uang Keluar'], 0 , '' , '.' ) ?></td>
                  <?php } ?>
                  <td class="bg-green color-palette"><b><?php echo number_format($allTotal['Uang Keluar'], 0 , '' , '.' ) ?></b></td>
                </tr>
                <tr>
                  <td>Selisih</td>
                  <?php foreach ($total as $key) {
                      if ($key['Selisih'] >= 0) { ?>
                        <td class="bg-navy disabled color-palette"><i><?php echo number_format($key['Selisih'], 0 , '' , '.' ) ?></i></td>
                      <?php } else { ?>
                        <td class="bg-red disabled color-palette"><i><?php echo number_format($key['Selisih'], 0 , '' , '.' ) ?></i></td>
                  <?php } } ?>
                  <td class="bg-navy color-palette"><b><i><h4>Rp. <?php echo number_format($allTotal['Selisih'], 0 , '' , '.' ) ?></h4></i></b></td>
                </tr>
              </tbody> 
            </table>
          </div>
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