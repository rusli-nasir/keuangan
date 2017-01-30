<style type="text/css">
td{white-space: nowrap;}
</style>
<!-- Main content -->
<section class="content">
  <!-- Info boxes -->
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><?php echo $menu; ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <p style="position: absolute;">
            <button type="button" class="btn btn-success hidden-xs" style="width:auto" data-toggle="modal" data-target="#add">Biaya</button>
          </p>
          <div class="table-responsive">
            <table id="example" class="table table-bordered table-striped table-hover">
              <thead> 
               <!-- <tr> -->
                 <th>No</th>
                 <th>Nama Pembayaran</th>
                 <th>Jurusan</th>
                 <th>Tahun Masuk</th>
                 <th>Semester</th>
                 <th>Gender</th>
                 <th>Biaya</th>
                 <th>Tanggal Dibuat</th>
                 <th>Tanggal Diperbarui</th>
                 <th>Oleh</th>
               <!-- </tr> -->
              </thead>
              <tbody> 
                <?php
                 $i = 0;
                 foreach ($data as $key) {
                  echo "
                    <tr>
                      <td>".++$i."</td>
                      <td>".$key['nama_kategori']."</td>
                      <td>".$key['nama_jurusan']."</td>
                      <td>".$key['tahun_masuk']."</td>
                      <td>".$key['semester']."</td>
                      <td>".$key['gender']."</td>
                      <td>Rp.".number_format($key['biaya'], 0 , '' , '.' ).",-</td>
                      <td>".$key['date_created']."</td>
                      <td>".$key['date_updated']."</td>
                      <td>".$key['username']."</td>
                    </tr>
                  ";
                } ?>
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

<!-- MODAL ADD -->
<div id="add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Form Pembayaran</h4>
        </div>
        <form method="post" action="<?php echo base_url().'biaya/add_biaya' ?>" class="form-horizontal">
        <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-2 control-label">Nama Pembayaran*</label>
              <div class="col-sm-10">
                <input class="form-control" name="nama_pembayaran" type="text" required>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Jurusan</label>
              <div class="col-sm-10">
                <select class="form-control" name="jurusan">
                  <option value="">Pilih</option>
                  <?php foreach ($jurusan as $row_jurusan) {
                    echo "<option value='".$row_jurusan['id']."'>".$row_jurusan['nama_jurusan']."</option>";
                  } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Tahun Masuk</label>
              <div class="col-sm-10">
                <select name="tahun_masuk" class="form-control">
                  <option value="">Pilih</option>
                  <option>2013</option>
                  <option>2014</option>
                  <option>2015</option>
                  <option>2016</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Semester</label>
              <div class="col-sm-10">
                <select name="semester" class="form-control">
                  <option value="">Pilih</option>
                  <?php foreach ($semester as $row_semester) {
                    echo "<option value='".$row_semester['id']."'>".$row_semester['semester']."</option>";
                  } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Gender</label>
              <div class="col-sm-10">
                <select name="gender" class="form-control">
                  <option value="">Pilih</option>
                  <option>L</option>
                  <option>P</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Biaya*</label>
              <div class="col-sm-10">
                <input type="number" class="form-control" name="biaya" placeholder="50000" required>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-primary" value="Save">
        </div>
        </form>
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