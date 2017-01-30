<style type="text/css">
	.edit{color:blue;}
	.delete{color:red;}
</style>
<div class="main">

    <div class="container">

      <div class="row">

      	<div class="col-md-12">

      		<div class="widget stacked widget-table action-table">
				<?php if($this->session->sekolah_id != 9) { ?>
				<form class="form-inline" method="post" action="<?php echo base_url('keuangan/payment_in_perbulan/') ?>">
					<div class="form-group">
						<select class="form-control" name="year" required>
							<option value="">Tahun</option>
							<?php
							for ($i=2015; $i < 2018; $i++) {
								echo "<option value='".$i."'>".$i."</option>";
							};
							?>
						</select>
					</div>
				  <div class="form-group">
				    <select name="month" class="form-control" required>
				    	<option value="">Bulan</option>
				    	<?php
				    		foreach ($daftar_bulan as $row_bulan) {
				    			echo "<option value='".$row_bulan['bulan_in_code']."'>".$row_bulan['nama_bulan']."</option>";
				    		}
				    	?>
				    </select>
				  </div>
				  <button type="submit" class="btn btn-default">Ok</button>
				</form>
				<?php } ?>
				<br>
				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Daftar Penerimaan <?php echo $nama_sekolah.' '.$tahun.' '.$bulan; ?></h3>
					<a class='btn btn-xs btn-primary pull-right' href="<?php echo base_url('keuangan/export_bulanan_yayasan/'.$sekolah_id.'/'.$month) ?>">Print</a>
					<a class='btn btn-xs btn-info pull-right' onclick='goBack()' style="margin-right:10px;">Back</a>
				</div> <!-- /widget-header -->

				<div class="widget-content">
					<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th rowspan="2" class="thtable">No</th>
								<th rowspan="2" class="thtable">Tanggal</th>
								<th rowspan="2" class="thtable">NIS</th>
								<th rowspan="2" class="thtable">Nama Siswa</th>
								<th rowspan="2" class="thtable">Kelas</th>
								<th colspan="12" class="thtable2">Bulan</th>
								<?php foreach ($payment_non_spp_table as $row) {
									echo "<th rowspan='2' class='thtable'>".$row['nama_kategori']."</th>";
								} ?>
								<th rowspan="2" class="thtable">Total Penerimaan<br> <p style='color:red';><b><?php echo number_format($total, 0 , '' , '.' ); ?></b></p></th>
							</tr>
							<tr>
								<th class="thtable2">Juli</th>
								<th class="thtable2">Agustus</th>
								<th class="thtable2">September</th>
								<th class="thtable2">Oktober</th>
								<th class="thtable2">November</th>
								<th class="thtable2">Desember</th>
								<th class="thtable2">Januari</th>
								<th class="thtable2">Februari</th>
								<th class="thtable2">Maret</th>
								<th class="thtable2">April</th>
								<th class="thtable2">Mei</th>
								<th class="thtable2">Juni</th>
 							</tr>
						</thead>
							<?php
							$no = 1;
							foreach ($payment as $row_payment) { ?>
								<tr>
									<td><?php echo $no++; ?></td>
									<td><?php echo $row_payment['Tanggal'] ?></td>
									<td><?php echo $row_payment['nis'] ?></td>
									<td><?php echo $row_payment['nama_siswa'] ?></td>
									<td><?php echo $row_payment['Kelas'] ?></td>
									<td><?php echo $row_payment['Juli'] ?></td>
									<td><?php echo $row_payment['Agustus'] ?></td>
									<td><?php echo $row_payment['September'] ?></td>
									<td><?php echo $row_payment['Oktober'] ?></td>
									<td><?php echo $row_payment['November'] ?></td>
									<td><?php echo $row_payment['Desember'] ?></td>
									<td><?php echo $row_payment['Januari'] ?></td>
									<td><?php echo $row_payment['Februari'] ?></td>
									<td><?php echo $row_payment['Maret'] ?></td>
									<td><?php echo $row_payment['April'] ?></td>
									<td><?php echo $row_payment['Mei'] ?></td>
									<td><?php echo $row_payment['Juni'] ?></td>

									<?php if ($sekolah_id == 1) { ?>
										<td><?php echo $row_payment['Komite'] ?></td>
										<td><?php echo $row_payment['Mid_Ganjil'] ?></td>
										<td><?php echo $row_payment['Mid_Genap'] ?></td>
										<td><?php echo $row_payment['Perpisahan'] ?></td>
										<td><?php echo $row_payment['Prakerin'] ?></td>
										<td><?php echo $row_payment['Semester_Ganjil'] ?></td>
										<td><?php echo $row_payment['Semester_Genap'] ?></td>
									<?php } else { ?>
										<td><?php echo $row_payment['SPP_Semester_1'] ?></td>
										<td><?php echo $row_payment['SPP_Semester_2'] ?></td>
										<td><?php echo $row_payment['SPP_Semester_3'] ?></td>
										<td><?php echo $row_payment['SPP_Semester_4'] ?></td>
										<td><?php echo $row_payment['SPP_Semester_5'] ?></td>
										<td><?php echo $row_payment['SPP_Semester_6'] ?></td>
									<?php } ?>
									<td><?php echo $row_payment['Total'] ?></td>
								</tr>
							<?php } ?>
						</table>
					</div>
				</div> <!-- /widget-content -->

				<?php if ($get_total_in_bulanan_lainnya > 0) { ?>
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
							<th style="color:orange;">Jumlah (<?php echo number_format($get_total_in_bulanan_lainnya, 0 , '' , '.' ) ?>)</th>
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
