<style type="text/css">
/*td{white-space: nowrap;}*/
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
            <form style="display:flex;" action="<?php echo base_url('v2/main/permintaan') ?>" method="get">
            <?php
              switch ($this->session->privilege_id) {
                case '1':
                  echo '<button type="button" class="btn btn-success hidden-xs" style="width:auto" data-toggle="modal" data-target="#add">Permintaan</button>';
                  break;
                
                default:
                  # code...
                  break;
              }
            ?>
            
            <div class="input-group" style="margin-bottom: 0; margin-left: 12px; margin-right: 0; margin-top: 0; width: 200px;">
                <input class="form-control" type="text" id="datepickerMonth" required placeholder="Bulan" name="date">
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
                  <th>Keterangan</th>
                  <th>Jumlah <b style='color:red;'>
                    <?php echo number_format($total_permintaan, 0 , '' , '.' ); ?></b></th>
                  <th>Status</th>
                  <?php if ($this->session->privilege_id != '1') {
                    echo "<th class='thtable'>Sekolah</th>";
                  } ?>                
                  <th style="text-align: center;">Kontrol</th>
                </tr>
              </thead>
              <tbody>
              <?php $i=1; foreach ($list_permintaan as $row) { ?>
                <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $row['date_created']; ?></td>
                  <td><?php echo $row['keterangan']; ?></td>
                  <td><?php echo number_format($row['amount'], 0 , '' , '.' ); ?></td>
                  <td style="text-align:center">
                    <?php if (($this->session->privilege_id != '1' and $row['status'] != 'Terkirim') or ($this->session->privilege_id == '1')) {
                      echo $row['status'];
                    } else {
                      echo '<div style="color:red";><b>------</b></div>';
                    }?>
                  </td>
                  <?php if ($this->session->privilege_id != '1') {
                    echo "<td class='thtable2'>".$row['nama_sekolah']."</td>";
                  } ?>                  
                  <td style="text-align:center;">
                    <?php if ($row['status']!='Diterima' and $this->session->privilege_id=='1') { ?>
                      <a class="edit" href="#" data-target='#edit<?php echo $row['id']; ?>' data-toggle='modal' title="Edit"><i class="fa fa-edit"></i></a>
                      <a class="delete" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_permintaan/'.$row['id']) ;?>" title="Delete"><i class="fa fa-remove"></i></a>
                    <?php } else if($row['status'] != 'Diterima' and $this->session->privilege_id == '2'){
                        if ($row['status'] == 'Ditolak') { ?>
                          <a class="edit" onclick="return confirm('Apakah Permintaan ini Diterima ?');" href="<?php echo base_url('keuangan/permintaan_diterima/'.$row['id']) ;?>" title="Diterima"><i class="fa fa-check"></i></a>
                    <?php } else if($row['status'] == 'Diterima') { ?>
                          <a class="delete" onclick="return confirm('Apakah Permintaan ini Ditolak ?');" href="<?php echo base_url('keuangan/permintaan_ditolak/'.$row['id']) ;?>" title="Ditolak"><i class="fa fa-remove"></i></a>
                    <?php } else { ?>
                          <a class="edit" onclick="return confirm('Apakah Permintaan ini Diterima ?');" href="<?php echo base_url('keuangan/permintaan_diterima/'.$row['id']) ;?>" title="Diterima"><i class="fa fa-check"></i></a>&nbsp;
                          <a class="delete" onclick="return confirm('Apakah Permintaan ini Ditolak ?');" href="<?php echo base_url('keuangan/permintaan_ditolak/'.$row['id']) ;?>" title="Ditolak"><i class="fa fa-remove"></i></a>
                    <?php } } ?>
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
          <h4 class="modal-title" id="mySmallModalLabel">Permintaan</h4>
        </div>
        <form method="post" action="<?php echo base_url().'keuangan/add_permintaan' ?>" class="form-horizontal">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-2 control-label">Keterangan*</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="keterangan" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Jumlah*</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" name="amount" required>
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