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
          <p>
            <form style="display:flex;" action="<?php echo base_url('v2/main/rekap_harian_yayasan') ?>" method="get">
            <div class="input-group" style="margin-bottom: 0; margin-left: 12px; margin-right: 0; margin-top: 0; width: 200px;">
                <input class="form-control" type="text" id="datepicker" required placeholder="Tanggal" name="date">
                <div class="input-group-btn">
                  <button type="submit" class="btn btn-danger">Ok</button>
                </div>
            </div>
            </form>
          </p>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
               <thead>
                <tr>
                  <th rowspan="2" style="vertical-align: middle;">No</th>
                  <th rowspan="2" style="vertical-align: middle;">Tanggal</th>
                  <th rowspan="2" style="vertical-align: middle;">Nama Sekolah</th>
                  <th colspan="2" style="vertical-align: middle; text-align: center;">Keuangan</th>
                  <th rowspan="2" style="vertical-align: middle;">Selisih</th>
                </tr>
                <tr>
                  <th>In</th>
                  <th>Out</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $x = 1; 
                foreach ($data as $key) { ?>
                <tr>
                  <td><?php echo $x++; ?></td>
                  <td><?php echo $key['tanggal'] ?></td>
                  <td><?php echo $key['sekolah'] ?></td>
                  <td><a href="<?php echo base_url('v2/main/harian_in/'.$key['idSekolah'].'/'.$key['tanggal']) ?>"><?php echo number_format($key['in'], 0 , '' , '.' ) ?></a></td>
                  <td><a href="<?php echo base_url('v2/main/harian_out/'.$key['idSekolah'].'/'.$key['tanggal']) ?>"><?php echo number_format($key['out'], 0 , '' , '.' ) ?></a></td>
                  <td><?php echo number_format($key['selisih'], 0 , '' , '.' ) ?></td>
                </tr> 
                <?php } ?>
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