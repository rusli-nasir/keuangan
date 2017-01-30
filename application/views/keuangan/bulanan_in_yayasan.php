<div class="main">

    <div class="container">

      <div class="row">

      	<div class="col-md-8">
      		<div class="row container">
      			<form method="post" action="<?php echo base_url().'main/bulanan_in_yayasan' ?>">
				<div class="form-group col-md-10">
					<select name="bulan" required>
						<option value="">Pilih</option>
						<?php foreach ($list_bulan as $row_list_bulan) {
							echo "<option value='".$row_list_bulan['bulan_in_code']."'>".$row_list_bulan['bulan']."</option>";
						} ?>
					</select>
					<select name="tahun" required>
						<option value="">Pilih</option>
						<option>2015</option>
						<option>2016</option>
						<option>2017</option>
						<option>2018</option>
					</select>
					<input type="submit" class="btn btn-sm btn-danger" value="Ok">
				</div>
			</form>
      		</div>
      		<div class="widget stacked widget-table action-table">
				<?php if (!empty($info)) {
					echo '
						<div class="alert alert-warning alert-dismissible fade in" role="alert">
						    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						    <strong>'.$info.'</strong>
						</div>
						';
				}
				if ($now=='-') {
					$now = date('M Y');
				}
				 ?>

				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Uang Masuk Bulan <?php echo nice_date($now, 'M Y'); ?></h3>
				</div> <!-- /widget-header -->

				<div class="widget-content">
					<!-- <div class="table-responsive">					 -->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th rowspan='2' class="thtable num">No</th>
								<th rowspan='2' class="thtable num2">Tanggal Diterima</th>
								<th rowspan='2' class="thtable">Keterangan</th>
								<th rowspan="2" class="thtable num2">Tanggal Dientry</th>
								<th rowspan='2' class="thtable amount">Jumlah <br><b style='color:red;'><?php echo number_format($total_bulanan_in, 0 , '' , '.' ); ?></b></th>
								<th rowspan="2" class="thtable num2">Kontrol</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=1; foreach ($list_bulanan_in as $row) { ?>
								<tr>
									<td class="thtable2"><?php echo $i++; ?></td>
									<td class="thtable2"><?php echo $row['date_created']; ?></td>
									<td class="thtable2"><?php echo $row['keterangan']; ?></td>
									<td class="thtable2"><?php echo $row['date_updated']; ?></td>
									<td class="thtable2"><?php echo number_format($row['amount'], 0 , '' , '.' ); ?></td>
									<td class="thtable2"><center><a class="edit" href="<?php echo base_url('keuangan/get_payment_id_lainnya/'.$row['id'].'/bln') ;?>" title="Edit">Edit</a> |
									<a class="Delete" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_harian_in_lainnya/'.$row['id'].'/bln') ;?>" title="Delete">Delete</a></center></td>
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
