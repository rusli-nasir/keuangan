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
            <button type="button" class="btn btn-success hidden-xs" style="width:auto" data-toggle="modal" data-target="#add">Tambah Jurusan</button>
          </p>
          <div class="table-responsive">
            <table id="example" class="table table-bordered table-striped table-hover">
              <thead> 
                <th>No</th>
                <th>Nama Jurusan</th>
                <th>Tanggal Diperbarui</th>
              </thead>
              <tbody> 
                <?php
                 $i = 0;
                 foreach ($jurusan as $key) {
                  echo "
                    <tr>
                      <td>".++$i."</td>
                      <td>".$key['nama_jurusan']."</td>
                      <td>".$key['date_updated']."</td>
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
          <h4 class="modal-title" id="mySmallModalLabel">Tambah Jurusan</h4>
        </div>
        <form method="post" action="<?php echo base_url().'jurusan/add_jurusan' ?>" class="form-horizontal">
        <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">Nama Jurusan*</label>
              <div class="col-sm-9">
                <input class="form-control" name="nama_jurusan" type="text" required>
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

<?php foreach ($jurusan as $key) { ?>
  <div id="edit<?php echo $key['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Tambah Jurusan</h4>
        </div>
        <form method="post" action="<?php echo base_url().'jurusan/update_jurusan' ?>" class="form-horizontal">
        <input type="hidden" name="id" value="<?php echo $key['id'] ?>">
        <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-2 control-label">Nama Jurusan*</label>
              <div class="col-sm-10">
                <input class="form-control" name="nama_jurusan" type="text" required value="<?php echo $key['nama_jurusan'] ?>">
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
<?php } ?>

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