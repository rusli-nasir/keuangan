<div class="main">

    <div class="container">

      <div class="row">

      	<div class="col-md-8">
      		<div class="widget stacked widget-table action-table">
      			<form class="form-horizontal" method="post" action="<?php echo base_url('main/harian_out_yayasan/'.$sekolah_id.'/') ?>">
					<div class="form-group">
				      <label for="name" class="col-lg-1"><b>Tanggal</b> </label>
						<div class="col-lg-3">
							<div class="input-group">
				        		<input name="date" class="date-picker-yayasan form-control" required>
							    <span class="input-group-btn">
							        <input class="btn btn-primary" type="submit" value="Go!">
							    </span>
							</div>
				 		</div>
				 	</div>
				</form>
				<?php if (!empty($info)) {
					echo '
						<div class="alert alert-warning alert-dismissible fade in" role="alert">
						    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						    <strong>'.$info.'</strong>
						</div>
						';
				} ?>
				<?php $now_indonesia  = nice_date($now, 'd-m-Y');
						$current_date = date('Y-m-d');
				 ?>
				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Uang Keluar <?php echo $now_indonesia; ?></h3>
					<a class='btn btn-xs btn-info pull-right' data-target='#add' data-toggle='modal' href='#'>Pengeluaran</a>
				</div> <!-- /widget-header -->

				<div class="widget-content">
					<!-- <div class="table-responsive">					 -->
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th rowspan='2' class="thtable num" class="thtable">No</th>
								<th rowspan='2' class="thtable" class="thtable">Kategori</th>
								<th rowspan='2' class="thtable" class="thtable">Keterangan</th>
								<th rowspan='2' class="thtable" class="thtable">Oleh</th>
								<th rowspan='2' class="thtable amount" class="thtable">Jumlah <br><b style='color:red;'><?php echo number_format($total_harian_out_yayasan, 0 , '' , '.' ); ?></b></th>
							</tr>
						</thead>
						<tbody>
							<?php $i=1; foreach ($list_harian_out_yayasan as $row) { ?>
								<tr>
									<td class="thtable2" class="thtable"><?php echo $i++; ?></td>
									<td class="thtable2" class="thtable"><?php echo $row['nama_kategori']; ?></td>
									<td class="thtable2" class="thtable"><?php echo $row['keterangan']; ?></td>
									<td class="thtable2" class="thtable"><?php echo $row['username']; ?></td>
									<td class="thtable2" class="thtable"><?php echo number_format($row['amount'], 0 , '' , '.' ); ?>
									<?php if ( $this->session->privilege_id =='2') { ?>
										<a class="edit" href="#" title="Edit" data-target='#edit<?php echo $row['id'] ?>' data-toggle='modal' ><i class="icon-edit"></i></a>
										<a class="delete" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_payment_out/'.$row['id']) ;?>" title="Delete"><i class="icon-trash"></i></a>
									<?php } ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						</table>
					<!-- </div> -->
				</div> <!-- /widget-content -->

			</div> <!-- /widget -->

	      </div> <!-- /span6 -->

      </div> <!-- /row -->

    </div> <!-- /container -->

</div> <!-- /main -->

<!-- MODAL ADD -->
<div id="add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
    	<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Form Pengeluaran</h4>
        </div>
        <div class="modal-body form-group">
        	<form method="post" action="<?php echo base_url().'keuangan/payment_yayasan_out' ?>">
        		<div class="row">
        			<div class="col-md-4 col-left">
        				<div class="form-group">
							<label>Jenis Pengeluaran</label>
							<select class="form-control" name="kategori_pengeluaran" required>
								<option value="">Pilih</option>
								<?php foreach ($list_pengeluaran as $row_list_pengeluaran) {
									echo "<option value='".$row_list_pengeluaran['id']."'>".$row_list_pengeluaran['nama_kategori']."</option>";
								} ?>
							</select>
						</div>
        			</div>
        			<div class="col-md-4">
        				<div class="form-group">
						    <label>Jumlah :</label>
						    <input type="number" class="form-control" name="amount" required>
						</div>
        			</div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tanggal Pengeluaran :</label>
                            <input type="text" class="form-control date-pickerindo" name="date_entry" readonly required>
                        </div>
                    </div>

        		</div>
        		<div class="row">
        			<div class="col-md-12 col-left">
        				<div class="form-group">
							<label>Keterangan :</label>
							<input type="text" name="keterangan" class="form-control" required>
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

<?php foreach ($list_harian_out_yayasan as $row_edit) { ?>
<!-- MODAL Edit -->
<div id="edit<?php echo $row_edit['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
    	<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Form Pengeluaran</h4>
        </div>
        <div class="modal-body form-group">
        	<form method="post" action="<?php echo base_url().'keuangan/edit_payment_out' ?>">
        		<input type="hidden" name="id" value="<?php echo $row_edit['id']; ?>">
        		<div class="row">
        			<div class="col-md-4 col-left">
        				<div class="form-group">
							<label>Jenis Pengeluaran</label>
							<select class="form-control" name="kategori_pengeluaran" required>
								<option value="<?php echo $row_edit['kategori_keuangan_pengeluaran_id'] ?>"><?php echo $row_edit['nama_kategori'] ?></option>
								<?php foreach ($list_pengeluaran as $row_list_pengeluaran) {
									echo "<option value='".$row_list_pengeluaran['id']."'>".$row_list_pengeluaran['nama_kategori']."</option>";
								} ?>
							</select>
						</div>
        			</div>
        			<div class="col-md-4">
        				<div class="form-group">
						    <label>Jumlah :</label>
						    <input type="number" class="form-control" name="amount" required value="<?php echo $row_edit['amount']; ?>">
						</div>
        			</div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tanggal Pengeluaran :</label>
                            <input type="text" class="form-control date-pickerindo" name="date_entry" readonly required value="<?php echo $row_edit['date_created']; ?>">
                        </div>
                    </div>
        		</div>
        		<div class="row">
        			<div class="col-md-12 col-left">
        				<div class="form-group">
							<label>Keterangan :</label>
							<input type="text" name="keterangan" class="form-control" required value="<?php echo $row_edit['keterangan']; ?>">
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
<!-- END MODAL Edit -->
<?php } ?>
