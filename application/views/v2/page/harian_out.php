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

 ?>
<!-- Main content -->
<section class="content">
  <!-- Info boxes -->
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Uang Keluar <?php echo $now.' '.$data['sekolah']; ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <?php if ($this->session->privilege_id == '1') { ?>
          <p>
            <form style="display:flex;" action="<?php echo base_url('v2/main/harian_out/'.$data['sekolah_id']) ?>" method="get"> 
              <button type="button" class="btn btn-success hidden-xs" style="width:auto" data-toggle="modal" data-target="#add">Pengeluaran</button> 
              <div class="input-group" style="margin-bottom: 0; margin-left: 12px; margin-right: 0; margin-top: 0; position: absolute; width: 200px;">
                <input class="form-control" type="text" id="datepicker" required placeholder="Tanggal" name="date">
                <div class="input-group-btn">
                  <button type="submit" class="btn btn-danger">Ok</button>
                </div>
              </div>
            </form>
          </p>
          <?php } else { ?>
          <button type="button" class="btn btn-success hidden-xs" style="margin-bottom: 10px" onclick="goBack()">Kembali</button>
          <?php } ?>
          <br class="hidden-lg hidden-small"><br class="hidden-lg hidden-small">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <th>No</th>
                <th>Uraian</th>
                <th>Keterangan</th>
                <th>Jumlah <br><b style='color:red;'><?php echo number_format($total_harian_out, 0 , '' , '.' ); ?></b></th>
                <?php if ((($current_date == $now) OR !$now) and $this->session->privilege_id =='1') { ?>
                  <th style="text-align:center;">Kontrol</th>
                <?php } ?>
              </thead>
              <tbody>
                <?php $i=1; foreach ($list_harian_out as $row) { ?>
                  <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $row['nama_kategori']; ?></td>
                    <td><?php echo $row['keterangan']; ?></td>
                    <td><?php echo number_format($row['amount'], 0 , '' , '.' ); ?></td>
                    <?php if ((($current_date == $now) OR !$now) and $this->session->privilege_id =='1') { ?>
                    <td style="text-align:center;">
                      <a class="btn btn-xs btn-primary" href="#" title="Edit" data-target='#edit<?php echo $row['payid'] ?>' data-toggle='modal' ><i class="fa fa-edit"></i></a>
                      <a class="btn btn-xs btn-danger" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_payment_out/'.$row['payid']) ;?>" title="Delete"><i class="fa fa-remove"></i></a>
                    </td>
                    <?php } ?>
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

<div id="add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Form Pengeluaran</h4>
        </div>
        <div class="modal-body form-group">
          <form method="post" action="<?php echo base_url('keuangan/payment_out') ?>">
            <div class="row">
              <div class="col-md-6 col-left">
                <div class="form-group">
                <label>Jenis Pengeluaran :</label>
                <select name="kategori_pengeluaran" class="form-control" required>
                  <option value="">Pilih</option>
                  <?php foreach ($list_pengeluaran as $row_list_pengeluaran) {
                    echo "<option value='".$row_list_pengeluaran['id']."'>".$row_list_pengeluaran['nama_kategori']."</option>";
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
        </div> <!-- /.form-group -->
          </form>
        </div>
    </div>
  </div>
</div>
<!-- END MODAL ADD -->

<?php foreach ($list_harian_out as $key) { ?>
<div id="edit<?php echo $key['payid']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Form Pengeluaran</h4>
        </div>
        <div class="modal-body form-group">
          <form method="post" action="<?php echo base_url('keuangan/edit_payment_out') ?>">
            <input type="hidden" value="<?php echo $key['payid'] ?>" name='id'>
            <div class="row">
              <div class="col-md-6 col-left">
                <div class="form-group">
                <label>Jenis Pengeluaran :</label>
                <select name="kategori_pengeluaran" class="form-control" required>
                  <option value="<?php echo $key['kid'] ?>"><?php echo $key['nama_kategori'] ?></option>
                  <?php foreach ($list_pengeluaran as $row_list_pengeluaran) {
                    echo "<option value='".$row_list_pengeluaran['id']."'>".$row_list_pengeluaran['nama_kategori']."</option>";
                  } ?>
                </select>
            </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                <label>Jumlah :</label>
                <input type="number" class="form-control" name="amount" required value="<?php echo $key['amount'] ?>">
            </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 col-left">
                <label>Keterangan</label>
                <textarea class="form-control" required cols="5" rows="5" name="keterangan"><?php echo $key['keterangan'] ?></textarea>
              </div>
            </div>
            <div class="form-group" style="margin-top:20px;">
          <button type="submit" class="btn btn-default">Simpan</button>
        </div> <!-- /.form-group -->
          </form>
        </div>
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