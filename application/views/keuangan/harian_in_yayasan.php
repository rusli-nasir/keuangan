<div class="main">

    <div class="container">

      <div class="row">

      	<div class="col-md-8">

      		<div class="widget stacked widget-table action-table">
				<?php if (!empty($info)) {
					echo '
						<div class="alert alert-warning alert-dismissible fade in" role="alert">
						    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						    <strong>'.$info.'</strong>
						</div>
						';
				} ?>
				<?php
				$now_indonesia  = nice_date($now, 'd-m-Y');
				$current_date 	= date('Y-m-d');
				$sekolah_id 	= $this->session->sekolah_id; ?>

				<form class="form-horizontal" method="get" action="<?php echo base_url('main/harian_in_yayasan/'.$sekolah_id.'/') ?>">
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

				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Uang Masuk <?php echo $now_indonesia?></h3>
					<a class='btn btn-xs btn-info pull-right' onclick='goBack()' style="margin-left:15px;">Back</a>
					<a class='btn btn-xs btn-info pull-right' data-target='#add' data-toggle='modal' href='#'>Penerimaan</a>
				</div> <!-- /widget-header -->

				<div class="widget-content">
					<!-- <div class="table-responsive">					 -->
					<table class="table table-striped table-bordered">
						<thead>
							<th>No</th>
							<th>Tanggal</th>
							<th>Kategori</th>
							<th>Keterangan</th>
							<th style="color:orange;">Jumlah (<?php echo number_format($get_total_in_harian_lainnya, 0 , '' , '.' ) ?>)</th>
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
											<a class="edit" href="<?php echo base_url('keuangan/get_payment_id_lainnya/'.$row_payment_lainnya['id']) ;?>" title="Edit"><i class="icon-edit"></i></a>
											<a class="delete" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_harian_in_lainnya/'.$row_payment_lainnya['id']) ;?>" title="Delete"><i class="icon-trash"></i></a>
									<?php //}
                                    } ?>
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

<?php foreach ($payment_lainnya as $row) { ?>
<!-- MODAL Edit -->
<div id="edit<?php echo $row['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
    	<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Form Penerimaan</h4>
        </div>
        <div class="modal-body form-group">
        	<form method="post" action="<?php echo base_url().'keuangan/payment_in_lainnya' ?>">
        		<input type="hidden" name="kategori_keuangan_id" value="1">
        		<div class="row">
                    <div class="col-md-6 col-left">
                        <div class="form-group">
                            <label>Jumlah :</label>
                            <input type="number" class="form-control" name="amount" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Diterima :</label>
                            <input type="text" class="form-control date-pickerindo" name="date_entry" readonly required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-left">
                        <div class="form-group">
                            <label>Keterangan :</label>
                            <textarea name="keterangan" class="form-control" cols="10" rows="5" required>Diterima dari</textarea>
                        </div>
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
<!-- END MODAL Edit -->
<?php } ?>

<!-- MODAL ADD -->
<div id="add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
    	<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Form Penerimaan</h4>
        </div>
        <div class="modal-body form-group">
        	<form method="post" action="<?php echo base_url().'keuangan/payment_in_lainnya' ?>">
        		<input type="hidden" name="kategori_keuangan_id" value="1">
        		<div class="row">
                    <div class="col-md-6 col-left">
                        <div class="form-group">
                            <label>Jumlah :</label>
                            <input type="number" class="form-control" name="amount" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Diterima :</label>
                            <input type="text" class="form-control date-pickerindo" name="date_entry" readonly required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-left">
                        <div class="form-group">
                            <label>Keterangan :</label>
                            <textarea name="keterangan" class="form-control" cols="10" rows="5" required>Diterima dari</textarea>
                        </div>
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
