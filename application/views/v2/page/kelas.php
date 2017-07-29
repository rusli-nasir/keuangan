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
            <button type="button" class="btn btn-success hidden-xs" style="width:auto" data-toggle="modal" data-target="#add">Tambah Kelas</button>
          </p>
          <div class="table-responsive">
            <table id="example" class="table table-bordered table-striped table-hover">
              <thead> 
               <!-- <tr> -->
                <th>No</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Group</th>
                <th>Tanggal Diperbarui</th>
                <th>Oleh</th>
               <!-- </tr> -->
              </thead>
              <tbody> 
                <?php
                 $i = 0;
                 foreach ($list_kelas as $key) {
                  echo "
                    <tr>
                      <td>".++$i."</td>
                      <td>".$key['kelas']."</td>
                      <td>".$key['nama_jurusan']."</td>
                      <td>".$key['group']."</td>
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
          <h4 class="modal-title" id="mySmallModalLabel">Tambah Kelas</h4>
        </div>
        <form method="post" action="<?php echo base_url().'kelas/add_kelas' ?>" class="form-horizontal">
        <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-2 control-label">Kelas*</label>
              <div class="col-sm-10">
                <select class="form-control" name="kelas" required>
                  <option value="">Pilih</option>
                  <?php foreach ($jenis_kelas as $row_kelas) { 
                    echo "<option value='".$row_kelas['id']."'>".$row_kelas['kelas']."</option>";
                  } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Jurusan</label>
              <div class="col-sm-10">
                <select class="form-control" name="jurusan" required>
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
                <select class="form-control" name="group">
                  <option value="">-</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
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