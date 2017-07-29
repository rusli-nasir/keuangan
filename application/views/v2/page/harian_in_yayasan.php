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
            <form style="display:flex;" action="<?php echo base_url('v2/main').'/'.$action ?>" method="get">
            <button type="button" class="btn btn-success hidden-xs" style="width:auto" data-toggle="modal" data-target="#add">Penerimaan</button>
            <div class="input-group" style="margin-bottom: 0; margin-left: 12px; margin-right: 0; margin-top: 0; width: 200px;">
                <input class="form-control" type="text" id="<?php echo $dateType; ?>" required placeholder="Bulan" name="date">
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
                 <th>No</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th style="color:orange;">
                Jumlah (<?php echo number_format($get_total_in_harian_lainnya, 0 , '' , '.' ) ?>)</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $num = 1;
              foreach ($payment_lainnya as $row_payment_lainnya) { ?>
                <tr>
                  <td><?php echo $num++ ?></td>
                  <td><?php echo $row_payment_lainnya['date_created'] ?></td>
                  <td><?php echo $row_payment_lainnya['nama_kategori'] ?></td>
                  <td><?php echo $row_payment_lainnya['keterangan'] ?></td>
                  <td><?php echo number_format($row_payment_lainnya['amount'], 0 , '' , '.' );
                    // if (($current_date == $now) OR empty($now)) {
                      if ($this->session->privilege_id == '2') { ?>
                      <a class="edit" href="<?php echo base_url('keuangan/get_payment_id_lainnya/'.$row_payment_lainnya['id'].'/'.$returnURL) ;?>" title="Edit"><i class="fa fa-edit"></i></a>
                      <a class="delete" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_harian_in_lainnya/'.$row_payment_lainnya['id']) ;?>" title="Delete"><i class="fa fa-remove"></i></a>
                  <?php //}
                                    } ?>
                  </td>
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

<!-- MODAL ADD -->
<div id="add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Penerimaan</h4>
        </div>
        <form method="post" action="<?php echo base_url().'keuangan/payment_in_lainnya' ?>" class="form-horizontal">
        <input type="hidden" name="kategori_keuangan_id" value="1">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-2 control-label">Jumlah*</label>
            <div class="col-sm-10">
              <input type="number" class="form-control" name="amount" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Tanggal Penerimaan*</label>
            <div class="col-sm-10">
              <input type="text" id="datepicker2" class="form-control" name="date_entry" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Keterangan*</label>
            <div class="col-sm-10">
              <textarea name="keterangan" class="form-control" cols="10" rows="5" required>Diterima dari</textarea>
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