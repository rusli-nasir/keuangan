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
					$now = date('Y-m-d'); $now_indonesia = date('d-m-Y');
					$kelas_sekolah_id 	= $this->uri->segment(3);
					$tahun_pelajaran 	= explode('-', $tahun_ajar);
					$semester1 			= $tahun_pelajaran['0'];
					$semester2 			= $tahun_pelajaran['1'];
          $start = $semester1.'-07-01';
          $end = $semester2.'-06-30';
				 ?>
				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Uang Masuk Bulanan</h3>
					<a class='btn btn-sm btn-success pull-right' href="<?php echo base_url('keuangan/export_bulanan/'.$kelas_sekolah_id); ?>">Print</a>
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
								<th rowspan="2" class="thtable">Total<br> <p style='color:red';><b><?php echo number_format($total_bulanan, 0 , '' , '.' ); ?></b></p></th>
							</tr>
							<tr>
								<th class="thtable2">Jul <?php echo $semester1; ?></th>
								<th class="thtable2">Ags <?php echo $semester1; ?></th>
								<th class="thtable2">Sept <?php echo $semester1; ?></th>
								<th class="thtable2">Okt <?php echo $semester1; ?></th>
								<th class="thtable2">Nov <?php echo $semester1; ?></th>
								<th class="thtable2">Des <?php echo $semester1; ?></th>
								<th class="thtable2">Jan <?php echo $semester2; ?></th>
								<th class="thtable2">Feb <?php echo $semester2; ?></th>
								<th class="thtable2">Mar <?php echo $semester2; ?></th>
								<th class="thtable2">Apr <?php echo $semester2; ?></th>
								<th class="thtable2">Mei <?php echo $semester2; ?></th>
								<th class="thtable2">Jun <?php echo $semester2; ?></th>
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
																			  left(payment.date_created,10) as tanggal
																			FROM
																			  payment
																			  JOIN kategori_keuangan
																			    ON kategori_keuangan.id = payment.kategori_keuangan_id
																			  JOIN siswa
																			    ON siswa.id = payment.siswa_id
																			  JOIN kelas_sekolah
																			    ON kelas_sekolah.id = siswa.kelas_sekolah_id
                                        JOIN bulan
                        								 	ON bulan.id = payment.bulan_id
																			WHERE siswa_id = '".$row_payment['siswa_id']."'
																			  AND bulan.id = '".$x."'
																			  AND nama_kategori like '%SPP%'
																			  AND kelas_sekolah_id = '".$kelas_sekolah_id."'
                                        AND payment.tahun = '".$current_semester."'");

												?>
												<td class="thtable">
												<?php
													while ($buff=mysqli_fetch_array($sql,MYSQLI_ASSOC)) {
														echo number_format($buff['amount'], 0 , '' , '.' );
														echo " (".$buff['tanggal'].")<br>";

													}
														$sql2 = mysqli_query($con, "SELECT
																					  sum(amount) as total_per_bulan
																					FROM
																					  payment
																					  JOIN kategori_keuangan
																					    ON kategori_keuangan.id = payment.kategori_keuangan_id
																					  JOIN siswa
																					    ON siswa.id = payment.siswa_id
																					  JOIN kelas_sekolah
																					    ON kelas_sekolah.id = siswa.kelas_sekolah_id
																					WHERE siswa_id = '".$row_payment['siswa_id']."'
																					  AND bulan_id = '".$x."'
																					  AND nama_kategori like '%SPP%'
                                            AND payment.tahun = '".$current_semester."'");
														$buff2 = mysqli_fetch_array($sql2,MYSQLI_ASSOC);
														if ($buff2['total_per_bulan'] == $row_payment['biaya']) {
															echo '<b>Lunas</b>';
														} else if ($buff2['total_per_bulan'] >= $row_payment['biaya']){
															echo '<b>Berlebih</b>';
														}
												 ?>
												</td>
											<?php
											}

											foreach ($payment_non_spp_table as $row) {
												$sql2 = mysqli_query($con, "SELECT
																			  amount,
																			  nama_kategori,
																			  tahun_ajaran_id,
																			  payment.date_created,
																			  kategori_keuangan.biaya
																			FROM
																			  payment
																			  JOIN kategori_keuangan
																			    ON kategori_keuangan.id = payment.kategori_keuangan_id
																			  JOIN siswa
																			    ON siswa.id = payment.siswa_id
																			  JOIN kelas_sekolah
																			    ON kelas_sekolah.id = siswa.kelas_sekolah_id
																			WHERE siswa_id = '".$row_payment['siswa_id']."'
																			  AND nama_kategori = '".$row['nama_kategori']."'
																			  AND kelas_sekolah_id = '".$kelas_sekolah_id."'
                                        AND DATE(payment.date_created) BETWEEN '".$start."' AND '".$end."'");
												$buff2=mysqli_fetch_array($sql2,MYSQLI_ASSOC);

												$sql4 = mysqli_query($con, "SELECT
																			  sum(amount) as total
																			FROM
																			  payment
																			  JOIN kategori_keuangan
																			    ON kategori_keuangan.id = payment.kategori_keuangan_id
																			WHERE siswa_id = '".$row_payment['siswa_id']."'
																			  AND nama_kategori = '".$row['nama_kategori']."'");
												$buff4 =mysqli_fetch_array($sql4,MYSQLI_ASSOC);

												if (!empty($buff2['amount'])) { ?>
													<td class='thtable'>
														<?php
															echo number_format($buff2['amount'], 0 , '' , '.' )." (".substr($buff2['date_created'], 0, 10).")<br>";
															while ($buff3=mysqli_fetch_array($sql2,MYSQLI_ASSOC)) {
																echo number_format($buff3['amount'], 0 , '' , '.' )." (".substr($buff3['date_created'], 0, 10).")<br>";
															}
															if ($buff4['total'] == $buff2['biaya']) {
																echo "<b>Lunas</b>";
															}
														?>
													</td>
													<?php
												} else {
													echo "<td class='thtable'></th>";
												}

												// if (!empty($buff2['amount'])) {
												// 	echo "<td class='thtable'>".number_format($buff2['amount'], 0 , '' , '.' )."</td>";
												// }else{
												// 	echo "<td class='thtable'></th>";
												// }
											}

											$sql3 = mysqli_query($con, "SELECT
                                                    SUM(amount) AS total
                                                  FROM
                                                    payment
                                                  WHERE siswa_id = '".$row_payment['siswa_id']."'
                                                    AND DATE(payment.date_created) BETWEEN '".$start."'
                                                    AND '".$end."'");
											$buff3=mysqli_fetch_array($sql3,MYSQLI_ASSOC);
											echo "<td class='thtable'>".number_format($buff3['total'], 0 , '' , '.' )."</td>";
										  ?>
									</tr>
								<?php $i++; } ?>
						</tbody>
						</table>
					</div>
				</div> <!-- /widget-content -->

			</div> <!-- /widget -->

	      </div> <!-- /span6 -->

      </div> <!-- /row -->

    </div> <!-- /container -->

</div> <!-- /main -->
