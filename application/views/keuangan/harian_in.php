<style type="text/css">
	.edit{color:blue;}
	.delete{color:red;}
	tr th.thtable2{min-width: 50px;}
	/*tr {
width: 100%;
display: inline-table;
    table-layout: fixed;
}

table{
 height:300px;
}
tbody{
  overflow-y: scroll;
  height: 200px;
  width: 100%;
  position: absolute;
}*/
</style>
<div class="main">

    <div class="container">

      <div class="row">

      	<div class="col-md-12">

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
					// $now = date('Y-m-d');
					$current_date 		= date('Y-m-d');
					$now_indonesia = nice_date($now, 'd-m-Y');
					$tahun_pelajaran 	= explode('-', $tahun_ajar);
					$semester1 			= $tahun_pelajaran['0'];
					$semester2 			= $tahun_pelajaran['1'];
					// $semester1	= '2015';
					// $semester2	=	'2016';
					$sekolah_id 		= $this->session->sekolah_id;
					if ($this->session->privilege_id != 2) { ?>
						<form class="form-horizontal" method="get" action="<?php echo base_url('main/harian_in/'.$sekolah_id.'/') ?>">
							<div class="form-group">
						      <label for="name" class="col-lg-1"><b>Tanggal :</b> </label>
								<div class="col-lg-3">
									<div class="input-group">
						        		<input name="date" class="date-picker form-control" required>
									    <span class="input-group-btn">
									        <input class="btn btn-primary" type="submit" value="Go!">
									    </span>
									    <span class="input-group-btn">
									    	<a class="btn btn-success" href="<?php echo base_url('keuangan/export_harian_nasional/'.$now) ?>">Print!</a>
									    </span>
									</div>
						 		</div>
						 	</div>
						</form>
				<?php } ?>

				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Uang Masuk Harian <?php echo $now_indonesia.' '.$nama_sekolah; ?></h3>
					<?php if ($this->session->privilege_id =='1') {
						echo "<a class='btn btn-xs btn-info pull-right' data-target='#add' data-toggle='modal' href='#'>Pembayaran</a>";
						echo "<a style='margin-right:10px' class='btn btn-xs btn-info pull-right' data-target='#add2' data-toggle='modal' href='#'>Penerimaan Lainnya</a>";
					} elseif ($this->session->privilege_id =='2') {
						echo "<a class='btn btn-xs btn-info pull-right' onclick='goBack()'>Back</a>";
					} ?>
				</div> <!-- /widget-header -->

				<div class="widget-content">
					<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th rowspan="2" class="thtable">No</th>
								<th rowspan="2" class="thtable">NIS</th>
								<th rowspan="2" class="thtable">Nama Siswa</th>
								<th rowspan="2" class="thtable">Kelas</th>
								<th colspan="12" class="thtable2">Bulan</th>
								<?php foreach ($payment_non_spp_table as $row) {
									echo "<th rowspan='2' class='thtable'>".$row['nama_kategori']."</th>";
								} ?>
								<th rowspan="2" class="thtable">Total Penerimaan<br> <p style='color:red';><b><?php echo number_format($total_harian, 0 , '' , '.' ); ?></b></p></th>
							</tr>
							<tr>
								<th class="thtable2">Jul <?php //echo $semester1; ?></th>
								<th class="thtable2">Ags <?php //echo $semester1; ?></th>
								<th class="thtable2">Sept <?php //echo $semester1; ?></th>
								<th class="thtable2">Okt <?php //echo $semester1; ?></th>
								<th class="thtable2">Nov <?php //echo $semester1; ?></th>
								<th class="thtable2">Des <?php //echo $semester1; ?></th>
								<th class="thtable2">Jan <?php //echo $semester2; ?></th>
								<th class="thtable2">Feb <?php //echo $semester2; ?></th>
								<th class="thtable2">Mar <?php //echo $semester2; ?></th>
								<th class="thtable2">Apr <?php //echo $semester2; ?></th>
								<th class="thtable2">Mei <?php //echo $semester2; ?></th>
								<th class="thtable2">Jun <?php //echo $semester2; ?></th>
 							</tr>
						</thead>
						<tbody>
							<?php
							$i=1;

							foreach ($payment as $row_payment) {
								?>
									<tr>
										<td class="thtable2"><?php echo $i; ?></td>
										<td class="thtable2"><?php echo $row_payment['nis']; ?></td>
										<td class="thtable2"><?php echo $row_payment['nama_siswa']; ?></td>
										<td class="thtable2"><?php echo $row_payment['kelas'].' '.$row_payment['nama_jurusan'].' '.$row_payment['group']; ?></td>
										<?php
											$con = mysqli_connect("localhost","ranahweb_yayasan","yayasan123","ranahweb_nasional");
											for ($x=1; $x < 13 ; $x++) {

												if ($x >= 7 ) {
													$current_semester = $semester2;
												} else {
													$current_semester = $semester1;
												}

												$sql = mysqli_query($con, "SELECT
																			  amount,
																			  bulan_id,
																			  payment.id as pay_id,
																			  tahun
																			FROM
																			  payment
																			  JOIN kategori_keuangan
																			    ON kategori_keuangan.id = payment.kategori_keuangan_id
																			WHERE siswa_id = '".$row_payment['siswa_id']."'
																			  AND bulan_id = '".$x."'
																			  AND nama_kategori like '%SPP%'
																			  AND payment.date_created like '%$now%'
																			  #OR tahun = '".$current_semester."'
																			  ");
												$buff=mysqli_fetch_array($sql,MYSQLI_ASSOC); ?>
													<td class="thtable">
														<?php if ($buff['bulan_id']==$x) {
															echo number_format($buff['amount'], 0 , '' , '.' );

															if (($current_date == $now) OR empty($now)) {
																if ($this->session->privilege_id =='1') { ?>
																	<a class="edit" href="<?php echo base_url('keuangan/get_payment_id/'.$buff['pay_id']) ;?>" title="Edit"><i class="icon-edit"></i></a>
																	<a class="delete" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_harian_in/'.$buff['pay_id']) ;?>" title="Delete"><i class="icon-trash"></i></a>
																<?php }
															}

															if ($buff['tahun'] != $current_semester) {
																$late = $current_semester-1;
																echo '<a href="#" title="SPP '.$late.'"><i style="color:red">'.$late.'</i>';
															}
														 } ?>
													</td>
												<?php
											}

											foreach ($payment_non_spp_table as $row) {
												$sql2 = mysqli_query($con, "SELECT
																			  amount,
																			  nama_kategori,
																			  tahun_ajaran_id,
																			  payment.date_created,
																			  kategori_keuangan.biaya,
																			  payment.id as pay_id
																			FROM
																			  payment
																			  JOIN kategori_keuangan
																			    ON kategori_keuangan.id = payment.kategori_keuangan_id
																			WHERE siswa_id = '".$row_payment['siswa_id']."'
																			  AND nama_kategori = '".$row['nama_kategori']."'
																			  and payment.date_created like '%$now%'");
												$buff3 = mysqli_fetch_array($sql2,MYSQLI_ASSOC);

												$sql4 = mysqli_query($con, "SELECT
																			  sum(amount) as total
																			FROM
																			  payment
																			  JOIN kategori_keuangan
																			    ON kategori_keuangan.id = payment.kategori_keuangan_id
																			WHERE siswa_id = '".$row_payment['siswa_id']."'
																			  AND nama_kategori = '".$row['nama_kategori']."'
																			  and payment.date_created like '%$now%'");
												$buff4 = mysqli_fetch_array($sql4,MYSQLI_ASSOC);

												if (!empty($buff3['amount'])) { ?>
													<td class='thtable'>
														<?php
															echo number_format($buff3['amount'], 0 , '' , '.' )." (".substr($buff3['date_created'], 0, 10).")";
															if (($current_date == $now) OR empty($now)) {
																if ($this->session->privilege_id == '1') { ?>
																<a class="edit" href="<?php echo base_url('keuangan/get_payment_id/'.$buff3['pay_id']) ;?>" title="Edit"><i class="icon-edit"></i></a>
																<a class="delete" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_harian_in/'.$buff3['pay_id']) ;?>" title="Delete"><i class="icon-trash"></i></a>
															<?php } }
															echo "<br>";
															while ($buff2=mysqli_fetch_array($sql2,MYSQLI_ASSOC)) {
																echo number_format($buff2['amount'], 0 , '' , '.' )." (".substr($buff2['date_created'], 0, 10).")";
																if (($current_date == $now) OR empty($now)) {
																	if ($this->session->privilege_id == '1') { ?>
																	<a class="edit" href="<?php echo base_url('keuangan/get_payment_id/'.$buff2['pay_id']) ;?>" title="Edit"><i class="icon-edit"></i></a>
																	<a class="delete" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_harian_in/'.$buff2['pay_id']) ;?>" title="Delete"><i class="icon-trash"></i></a>
																<?php } }
																echo "<br>";
															}
															if ($buff4['total'] == $buff3['biaya']) {
																echo "<b>Lunas</b>";
															}
														?>
													</td>
													<?php
												} else {
													echo "<td class='thtable'></th>";
												}
											}

											$sql3 = mysqli_query($con, "SELECT SUM(amount) as total FROM payment
																			WHERE siswa_id = '".$row_payment['siswa_id']."'
																			and payment.date_created like '%$now%'");
											$buff3=mysqli_fetch_array($sql3,MYSQLI_ASSOC);
											echo "<td class='thtable'>".number_format($buff3['total'], 0 , '' , '.' )."</td>";
										  ?>
									</tr>
								<?php $i++; } ?>
						</tbody>
						</table>
					</div>
				</div> <!-- /widget-content -->
				<?php if ($get_total_in_harian_lainnya > 0) { ?>
					<div class="panel panel-default" style="margin-top:20px;">
					  <!-- Default panel contents -->
					  <div class="panel-heading">
					  	<b>Penerimaan Lainnya</b>
					  </div>
					  <!-- Table -->
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
										if (($current_date == $now) OR empty($now)) {
											if ($this->session->privilege_id == '1') { ?>
											<a class="edit" href="<?php echo base_url('keuangan/get_payment_id_lainnya/'.$row_payment_lainnya['id']) ;?>" title="Edit"><i class="icon-edit"></i></a>
											<a class="delete" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_harian_in_lainnya/'.$row_payment_lainnya['id']) ;?>" title="Delete"><i class="icon-trash"></i></a>
									<?php } } ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						</table>
					</div>
				<?php } ?>

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
          <h4 class="modal-title" id="mySmallModalLabel">Form Pembayaran</h4>
        </div>
        <div class="modal-body form-group">
        	<form method="post" action="<?php echo base_url().'keuangan/payment_in' ?>">
        		<div class="row">
        			<div class="col-md-6 col-left">
        				<div class="form-group">
							<label>Kelas</label>
							<select class="form-control" name="kelas" id="kelas" required>
								<option value="">Pilih</option>
								<?php foreach ($kelas_siswa as $row_kelas) {
									echo "<option value='".$row_kelas['id']."'>".$row_kelas['kelas']." ".$row_kelas['nama_jurusan']." ".$row_kelas['group']."</option>";
								} ?>
							</select>
						</div>
        			</div>
        			<div class="col-md-6">
        				<div class="form-group">
							<label>Siswa :</label>
							<select name="siswa_id" id="siswa" class="form-control" required>
								<option value="">Pilih Siswa</option>
							</select>
						</div>
        			</div>
        		</div>
        		<div class="row">
        			<div class="col-md-5 col-left">
		        		<div class="form-group">
						    <label>Pembayaran Keuangan :</label>
						    <select name="kategori_keuangan_id" class="form-control" required id="payment">
						    	<option value="">Pilih Pembayaran</option>
						    </select>
						</div>
        			</div>
        			<div class="col-md-4 col-left">
		        		<div class="form-group">
						    <label>Bulan :</label>
						    <select name="annualy" class="form-control" required id="annualy">
						    	<option value="">Pilih</option>
						    </select>
						</div>
        			</div>
        			<div class="col-md-3">
        				<div class="form-group">
						    <label>Jumlah :</label>
						    <input type="number" class="form-control" name="amount" required>
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

<!-- MODAL ADD -->
<div id="add2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal fade bs-example-modal-lg">
    <div class="modal-content">
    	<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Form Penerimaan</h4>
        </div>
        <div class="modal-body form-group">
        	<form method="post" action="<?php echo base_url().'keuangan/payment_in_lainnya' ?>">
        		<div class="row">
        			<div class="col-md-6 col-left">
		        		<div class="form-group">
						    <label>Penerimaan Keuangan :</label>
						    <select name="kategori_keuangan_id" class="form-control" required>
						    	<option value="">Pilih</option>
						    	<?php foreach ($list_penerimaan_lainnya as $row_lainnya) {
						    		echo "<option value='".$row_lainnya['id']."'>".$row_lainnya['nama_kategori']."</option>";
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
