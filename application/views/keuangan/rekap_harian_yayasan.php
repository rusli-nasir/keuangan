<div class="main">

    <div class="container">

      <div class="row">

      	<div class="col-md-8">
      		<div class="widget stacked widget-table action-table">
	  		<form class="form-horizontal" method="get" action="<?php echo base_url('main/rekap_harian_yayasan/') ?>">
				<div class="form-group">
			      <label for="name" class="col-lg-1"><b>Tanggal</b></label>
					<div class="col-lg-4">
						<div class="input-group">
			        		<input name="date" class="date-picker-yayasan form-control" required>
						    <span class="input-group-btn">
						        <input class="btn btn-primary" type="submit" value="Go!">
						    </span>
						</div>
			 		</div>
			 	</div>
			</form>
				<?php
					$s1 = date('y'); $s2 = date('y')+1;
					$better_date = nice_date($now, 'd-m-Y');
				?>

				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Rekap Harian <?php echo $better_date; ?></h3>
				</div> <!-- /widget-header -->

				<div class="widget-content">
					<!-- <div class="table-responsive">					 -->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th rowspan='2' class="thtable num">No</th>
								<th rowspan='2' class="thtable num2">Tanggal</th>
								<th rowspan='2' class="thtable amount">Nama Sekolah</th>
								<th colspan='2' class="thtable">Keuangan</th>
								<th rowspan='2' class="thtable amount">Selisih <br><b style='color:red;'><?php //echo number_format($total_bulanan_out, 0 , '' , '.' ); ?></b></th>
							</tr>
							<tr>
								<th class="thtable">In</th>
								<th class="thtable">Out</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$con = mysqli_connect("localhost","ranahweb_yayasan","yayasan123","ranahweb_nasional");
							$i=1; foreach ($list_sekolah as $row) { ?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td><?php echo $better_date; ?></td>
								<td><?php echo $row['nama_sekolah'] ?></td>
								<?php
									$sql2 = mysqli_query($con, "SELECT
																  SUM(payment.amount) AS masuk
																FROM
																  payment
																  JOIN kategori_keuangan
																ON kategori_keuangan.id = payment.kategori_keuangan_id
																WHERE kategori_keuangan.sekolah_id = '".$row['id']."'
																AND payment.date_created like '%$now%'");
									$buff2 = mysqli_fetch_array($sql2,MYSQLI_ASSOC);

									$sql3 = mysqli_query($con, "SELECT
																  sum(amount) as total
																FROM
																  payment_lainnya
																WHERE sekolah_id = '".$row['id']."'
																and date_created like '%$now%'");
									$buff3 = mysqli_fetch_array($sql3,MYSQLI_ASSOC);
								?>
								<td>
								<?php if ($row['id']=='9') { ?>
									<a href="<?php echo base_url("main/harian_in_yayasan/".$row['id']."/".$now); ?>">
								<?php } else { ?>
									<a href="<?php echo base_url("main/harian_in/".$row['id']."/".$now); ?>">
								<?php }
								echo number_format($buff2['masuk']+$buff3['total'], 0 , '' , '.' ) ?>
								</a></td>

								<?php
									$sql = mysqli_query($con, "SELECT
																  SUM(payment_out.amount) AS keluar
																FROM
																  payment_out
																WHERE sekolah_id = '".$row['id']."'
																AND payment_out.date_created like '%$now%'");
									$buff=mysqli_fetch_array($sql,MYSQLI_ASSOC); ?>
								<td><a href="<?php echo base_url("main/harian_out/".$row['id']."/".$now); ?>"><?php echo number_format($buff['keluar'], 0 , '' , '.' ) ?></a></td>
								<td><?php $selisih = ($buff2['masuk']+$buff3['total']) - $buff['keluar'];
										  echo number_format($selisih, 0 , '' , '.' ) ?></td>
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
