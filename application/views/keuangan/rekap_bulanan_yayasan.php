<div class="main">

    <div class="container">

      <div class="row">

      	<div class="col-md-12">
      		<div class="row container">
      			<form method="post" action="<?php echo base_url().'main/bulanan_out/' ?>">
				<div class="form-group col-md-10">
          <?php if ($this->uri->segment(3)=='2015-2016'){
            $s2015='btn-default';
            $s2016='btn-primary';
          }else{
            $s2015='btn-primary';
            $s2016='btn-default';
          } ?>
					<a class="btn btn-sm <?php echo $s2015 ?>" href="<?php echo base_url('main/rekap_bulanan_yayasan'); ?>">Tahun Ajaran 2016-2017</a>
          <a href="<?php echo base_url('main/rekap_bulanan_yayasan/2015-2016'); ?>" class="btn btn-sm <?php echo $s2016 ?>">Tahun Ajaran 2015-2016</a>
					<!-- <select name="bulan" required>
						<option value="">Pilih</option>
						<?php //foreach ($list_bulan as $row_list_bulan) {
							//echo "<option value='".$row_list_bulan['bulan_in_code']."'>".$row_list_bulan['bulan']."</option>";
						//} ?>
					</select>
					<select name="tahun" required>
						<option value="">Pilih</option>
						<option>2015</option>
						<option>2016</option>
						<option>2017</option>
						<option>2018</option>
					</select>
					<input type="submit" class="btn btn-sm btn-danger" value="Ok"> -->
				</div>
			</form>
      		</div>
      		<div class="widget stacked widget-table action-table">
				<?php
					$con = mysqli_connect("localhost","ranahweb_yayasan","yayasan123","ranahweb_nasional");
					$s1 = date('y'); $s2 = date('y')+1;
					$current_year = date('Y');
					$now = $this->uri->segment(3);
					$tahun_pelajaran 	= explode('-', $tahun_ajar);
					$semester1 			= $tahun_pelajaran['0'];
					$semester2 			= $tahun_pelajaran['1'];
					// if (empty($uri_2)) {
					//  	$uri_2 = $now;
					//  }
				?>

				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Rekap Bulanan</h3>
				</div> <!-- /widget-header -->

				<div class="widget-content">
					<!-- <div class="table-responsive">					 -->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th class="thtable num">Bulan</th>
								<th class="thtable num2">Keterangan</th>
								<?php foreach ($list_sekolah as $row_list_sekolah) {
									echo "<th class='thtable'>".$row_list_sekolah['nama_sekolah']."</th>";
								} ?>
								<th class="thtable amount">Jumlah <br><b style='color:red;'><?php //echo number_format($total_bulanan_out, 0 , '' , '.' ); ?></b></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($list_bulan as $row_list_bulan) {
								if ($row_list_bulan['id'] >= 7 ) {
									$current_semester = $semester2;
								} else {
									$current_semester = $semester1;
								}
							 ?>
								<tr>
									<td rowspan="3"><?php echo $row_list_bulan['bulan'].'-'.$current_semester; ?></td>
									<td>Uang Masuk</td>
									<?php foreach ($list_sekolah as $row_list_sekolah) { ?>
										<td><?php
											$sql = mysqli_query($con, "SELECT
																		  SUM(payment.amount) AS masuk
																		FROM
																		  payment
																		  JOIN kategori_keuangan
																		ON kategori_keuangan.id = payment.kategori_keuangan_id
																		WHERE kategori_keuangan.sekolah_id = '".$row_list_sekolah['id']."'
																		AND payment.date_created like '%".$current_semester.'-'.$row_list_bulan['bulan_in_code']."%'");
											$buff = mysqli_fetch_array($sql,MYSQLI_ASSOC);

											$sql_lainnya 	= mysqli_query($con, "SELECT
																  sum(amount) as lainnya_harian
																FROM
																  payment_lainnya
																WHERE sekolah_id = '".$row_list_sekolah['id']."'
																and kategori_keuangan_lainnya_id !='3'
																and date_created like '%".$current_semester.'-'.$row_list_bulan['bulan_in_code']."%'");

											$buff_lainnya 	= mysqli_fetch_array($sql_lainnya,MYSQLI_ASSOC);

											echo "<a href='".base_url('keuangan/payment_in_perbulan/'.$row_list_sekolah['id'].'/'.$current_semester.'-'.$row_list_bulan['bulan_in_code'])."'>".number_format($buff['masuk']+$buff_lainnya['lainnya_harian'], 0 , '' , '.' )."</a>";
											 ?>
										</td>
									<?php } ?>
										<td>
											<?php
												$sql3 = mysqli_query($con, "SELECT
																			  SUM(payment.amount) AS total_bulanan_in
																			FROM
																			  payment
																			  JOIN kategori_keuangan
																			ON kategori_keuangan.id = payment.kategori_keuangan_id
																			WHERE payment.date_created like '%".$current_semester.'-'.$row_list_bulan['bulan_in_code']."%'");
												$buff3 = mysqli_fetch_array($sql3,MYSQLI_ASSOC);

												$sql_lainnya2 	= mysqli_query($con, "SELECT
																  sum(amount) as lainnya_bulanan
																FROM
																  payment_lainnya
																WHERE date_created like '%".$current_semester.'-'.$row_list_bulan['bulan_in_code']."%'
																and kategori_keuangan_lainnya_id !='3'");

												$buff_lainnya2 	= mysqli_fetch_array($sql_lainnya2,MYSQLI_ASSOC);

												echo number_format($buff3['total_bulanan_in']+$buff_lainnya2['lainnya_bulanan'], 0 , '' , '.' ); ?>
										</td>
								</tr>
								<tr>
									<td>Uang Keluar</td>
									<?php foreach ($list_sekolah as $row_list_sekolah) { ?>
										<td><?php
											$sql2 = mysqli_query($con, "SELECT
																  SUM(payment_out.amount) AS keluar
																FROM
																  payment_out
																WHERE sekolah_id = '".$row_list_sekolah['id']."'
																AND payment_out.date_created like '%".$current_semester.'-'.$row_list_bulan['bulan_in_code']."%'");
											$buff2 = mysqli_fetch_array($sql2,MYSQLI_ASSOC);
											// echo ;
											echo "<a href='".base_url('keuangan/payment_out_perbulan/'.$row_list_sekolah['id'].'/'.$current_semester.'-'.$row_list_bulan['bulan_in_code'])."'>".number_format($buff2['keluar'], 0 , '' , '.' )."</a>";
											 ?>
										</td>
									<?php } ?>
									<td>
									<?php
										$sql4 = mysqli_query($con, "SELECT
																  SUM(payment_out.amount) AS total_bulanan_out
																FROM
																  payment_out
																WHERE payment_out.date_created like '%".$current_semester.'-'.$row_list_bulan['bulan_in_code']."%'");
										$buff4 = mysqli_fetch_array($sql4,MYSQLI_ASSOC);
										echo number_format($buff4['total_bulanan_out'], 0 , '' , '.' ); ?>
									</td>
								</tr>
								<tr>
									<td>Selisih</td>
									<?php foreach ($list_sekolah as $row_list_sekolah) { ?>
										<td><?php
											$sql5 = mysqli_query($con, "SELECT
																		  SUM(payment.amount) AS masuk
																		FROM
																		  payment
																		  JOIN kategori_keuangan
																		ON kategori_keuangan.id = payment.kategori_keuangan_id
																		WHERE kategori_keuangan.sekolah_id = '".$row_list_sekolah['id']."'
																		AND payment.date_created like '%".$current_semester.'-'.$row_list_bulan['bulan_in_code']."%'");
											$buff5 = mysqli_fetch_array($sql5,MYSQLI_ASSOC);

											$sql_lainnya3 	= mysqli_query($con, "SELECT
																  sum(amount) as lainnya_harian
																FROM
																  payment_lainnya
																WHERE sekolah_id = '".$row_list_sekolah['id']."'
																and kategori_keuangan_lainnya_id !='3'
																and date_created like '%".$current_semester.'-'.$row_list_bulan['bulan_in_code']."%'");

											$buff_lainnya3 	= mysqli_fetch_array($sql_lainnya3,MYSQLI_ASSOC);

											$sql6 = mysqli_query($con, "SELECT
																  SUM(payment_out.amount) AS keluar
																FROM
																  payment_out
																WHERE sekolah_id = '".$row_list_sekolah['id']."'
																AND payment_out.date_created like '%".$current_semester.'-'.$row_list_bulan['bulan_in_code']."%'");
											$buff6 = mysqli_fetch_array($sql6,MYSQLI_ASSOC);
											echo "<i>".number_format(($buff5['masuk']+$buff_lainnya3['lainnya_harian'])-$buff6['keluar'], 0 , '' , '.' )."</i>"; ?>
										</td>
									<?php } ?>
									<td style="background-color: rgba(255, 153, 0, 0.28);color: #333;font-weight: bolder;">
									<?php
										$sql7 = mysqli_query($con, "SELECT
																	  SUM(payment.amount) AS total_bulanan_in
																	FROM
																	  payment
																	  JOIN kategori_keuangan
																	ON kategori_keuangan.id = payment.kategori_keuangan_id
																	WHERE payment.date_created like '%".$current_semester.'-'.$row_list_bulan['bulan_in_code']."%'");
										$buff7 = mysqli_fetch_array($sql7,MYSQLI_ASSOC);

										$sql_lainnya4 	= mysqli_query($con, "SELECT
																				  sum(amount) as lainnya_bulanan
																				FROM
																				  payment_lainnya
																				WHERE date_created like '%".$current_semester.'-'.$row_list_bulan['bulan_in_code']."%'
																				and kategori_keuangan_lainnya_id !='3'");
										$buff_lainnya4 	= mysqli_fetch_array($sql_lainnya4,MYSQLI_ASSOC);

										$sql8 = mysqli_query($con, "SELECT
																  SUM(payment_out.amount) AS total_bulanan_out
																FROM
																  payment_out
																WHERE payment_out.date_created like '%".$current_semester.'-'.$row_list_bulan['bulan_in_code']."%'");
										$buff8 = mysqli_fetch_array($sql8,MYSQLI_ASSOC);
										echo "<b><i>".number_format(($buff7['total_bulanan_in']+$buff_lainnya4['lainnya_bulanan']) - $buff8['total_bulanan_out'] , 0 , '' , '.' )."</b></i>"; ?>
									</td>
								</tr>
							<?php }
              /*
              ?>
							<tr>
								<td colspan="2" class="thtable">Uang Kas Akhir Tahun 2015</td>
								<?php foreach ($list_sekolah as $row_list_sekolah) { ?>
									<td><?php
										$sql = mysqli_query($con, "SELECT
																	  SUM(payment_lainnya.amount) AS masuk
																	FROM
																	  payment_lainnya
																	  JOIN kategori_keuangan_lainnya
																	    ON kategori_keuangan_lainnya.id = payment_lainnya.kategori_keuangan_lainnya_id
																	WHERE payment_lainnya.sekolah_id = '".$row_list_sekolah['id']."'
																	  AND LEFT(
																	    payment_lainnya.date_created,
																	    4
																	  ) = '2015'
                                    AND payment_lainnya.sekolah_id != '9'");
										$buff = mysqli_fetch_array($sql,MYSQLI_ASSOC);

								?>
										<a href="<?php echo base_url("main/kas_akhir_tahun/".$row_list_sekolah['id']); ?>"><?php echo number_format($buff['masuk'], 0 , '' , '.' ); ?></a>
									</td>
								<?php } ?>
								<td>
									<?php
										$sql = mysqli_query($con, "SELECT
																	  SUM(payment_lainnya.amount) AS total_kas_akhir_tahun
																	FROM
																	  payment_lainnya
																	  JOIN kategori_keuangan_lainnya
																	    ON kategori_keuangan_lainnya.id = payment_lainnya.kategori_keuangan_lainnya_id
																	WHERE LEFT(
																	    payment_lainnya.date_created,
																	    4
																	  ) = '2015'
                                    AND payment_lainnya.sekolah_id != '9'");
										$buff = mysqli_fetch_array($sql,MYSQLI_ASSOC);
										echo "<b><i>".number_format($buff['total_kas_akhir_tahun'], 0 , '' , '.' )."</b></i>"; ?>
								</td>
							</tr>
              <?php */ ?>
							<tr>
								<td rowspan="3"class="thtable num2">Total</td>
								<td style="background-color: rgba(92, 184, 92, 0.56);">Uang Masuk/Tahun</td>
								<?php foreach ($list_sekolah as $row_list_sekolah) { ?>
									<td style="background-color: rgba(92, 184, 92, 0.56);"><?php
										// $sql = mysqli_query($con, "SELECT
										// 							  SUM(payment.amount) AS masuk
										// 							FROM
										// 							  payment
										// 							  JOIN kategori_keuangan
										// 							    ON kategori_keuangan.id = payment.kategori_keuangan_id
										// 							WHERE kategori_keuangan.sekolah_id = '".$row_list_sekolah['id']."'
										// 							  AND DATE(payment.date_created) BETWEEN '".$semester1."-".$row_list_bulan['bulan_in_code']."'
										// 							  AND '".$semester2."-".$row_list_bulan['bulan_in_code']."-30'");

                    $sql = mysqli_query($con, "SELECT
																	  SUM(payment.amount) AS masuk
																	FROM
																	  payment
																	  JOIN kategori_keuangan
																	    ON kategori_keuangan.id = payment.kategori_keuangan_id
																	WHERE kategori_keuangan.sekolah_id = '".$row_list_sekolah['id']."'
																	  AND DATE(payment.date_created) BETWEEN '".$semester1."-07-01'
																	  AND '".$semester2."-06-30'");

										$buff = mysqli_fetch_array($sql,MYSQLI_ASSOC);

										$sql_lainnya5 	= mysqli_query($con, "SELECT
																				  sum(amount) as lainnya_tahunan
																				FROM
																				  payment_lainnya
																				WHERE sekolah_id = '".$row_list_sekolah['id']."'
                                        AND DATE(payment_lainnya.date_created) BETWEEN '".$semester1."-07-01'
    																	  AND '".$semester2."-06-30'");

										$buff_lainnya5 	= mysqli_fetch_array($sql_lainnya5,MYSQLI_ASSOC);
                    // echo $semester1."-".$row_list_bulan['bulan_in_code']."-".$semester2;
										echo number_format($buff['masuk']+$buff_lainnya5['lainnya_tahunan'], 0 , '' , '.' ); ?>
									</td>
								<?php } ?>
								<td style="background-color: rgba(92, 184, 92, 0.56);">
									<?php
										$sql = mysqli_query($con, "SELECT
																	  SUM(payment.amount) AS total_bulanan_in_year
																	FROM
																	  payment
																	  JOIN kategori_keuangan
																	ON kategori_keuangan.id = payment.kategori_keuangan_id
																	WHERE
                                  DATE(payment.date_created) BETWEEN '".$semester1."-07-01' AND '".$semester2."-06-30'");

										$buff = mysqli_fetch_array($sql,MYSQLI_ASSOC);

										$sql_lainnya6 	= mysqli_query($con, "SELECT
																				  sum(amount) as total_lainnya_tahunan
																				FROM
																				  payment_lainnya
																				WHERE DATE(payment_lainnya.date_created) BETWEEN '".$semester1."-07-01' AND '".$semester2."-06-30'
																	  			AND payment_lainnya.kategori_keuangan_lainnya_id !='3'");

										$buff_lainnya6 	= mysqli_fetch_array($sql_lainnya6,MYSQLI_ASSOC);

										$sql1 = mysqli_query($con, "SELECT
																	  SUM(payment_lainnya.amount) AS total_kas_akhir_tahun
																	FROM
																	  payment_lainnya
																	  JOIN kategori_keuangan_lainnya
																	    ON kategori_keuangan_lainnya.id = payment_lainnya.kategori_keuangan_lainnya_id
																	WHERE LEFT(
																	    payment_lainnya.date_created,
																	    4
																	  ) = '2015'
                                    AND payment_lainnya.sekolah_id != '9'");
										$buff1 = mysqli_fetch_array($sql1,MYSQLI_ASSOC);
                    #tidak ada uang kas akhir tahun ajaran 2015-2016
                    $buff1['total_kas_akhir_tahun'] = 0;

										echo "<b><i>".number_format($buff['total_bulanan_in_year']+$buff_lainnya6['total_lainnya_tahunan']+$buff1['total_kas_akhir_tahun'], 0 , '' , '.' )."</b></i>"; ?>
								</td>
							</tr>
							<tr>
								<td style="background-color: rgba(201, 48, 44, 0.2);">Uang Keluar/Tahun</td>
								<?php foreach ($list_sekolah as $row_list_sekolah) { ?>
									<td style="background-color: rgba(201, 48, 44, 0.2);"><?php
										$sql = mysqli_query($con, "SELECT
																  SUM(payment_out.amount) AS keluar
																FROM
																  payment_out
																WHERE sekolah_id = '".$row_list_sekolah['id']."'
																 AND DATE(payment_out.date_created) BETWEEN '".$semester1."-07-01' AND '".$semester2."-06-30'");
										$buff = mysqli_fetch_array($sql,MYSQLI_ASSOC);
										echo number_format($buff['keluar'], 0 , '' , '.' ); ?>
									</td>
								<?php } ?>
								<td style="background-color: rgba(201, 48, 44, 0.2);"><?php
									$sql = mysqli_query($con, "SELECT
															  SUM(payment_out.amount) AS total_bulanan_out_year
															FROM
															  payment_out
															WHERE DATE(payment_out.date_created) BETWEEN '".$semester1."-07-01' AND '".$semester2."-06-30'");
									$buff = mysqli_fetch_array($sql,MYSQLI_ASSOC);
									echo number_format($buff['total_bulanan_out_year'], 0 , '' , '.' ); ?>
								</td>
							</tr>
							<tr>
								<td>Selisih</td>
								<?php foreach ($list_sekolah as $row_list_sekolah) { ?>
									<td><?php
										$sql = mysqli_query($con, "SELECT
																	  SUM(payment.amount) AS masuk
																	FROM
																	  payment
																	  JOIN kategori_keuangan
																	    ON kategori_keuangan.id = payment.kategori_keuangan_id
																	WHERE kategori_keuangan.sekolah_id = '".$row_list_sekolah['id']."'
																	  AND DATE(payment.date_created) BETWEEN '".$semester1."-07-01' AND '".$semester2."-06-30'");
										$buff = mysqli_fetch_array($sql,MYSQLI_ASSOC);

										$sql_lainnya7 	= mysqli_query($con, "SELECT
																				  sum(amount) as lainnya_tahunan
																				FROM
																				  payment_lainnya
																				WHERE sekolah_id = '".$row_list_sekolah['id']."'
																				AND DATE(payment_lainnya.date_created) BETWEEN '".$semester1."-07-01' AND '".$semester2."-06-30'");
										$buff_lainnya7 	= mysqli_fetch_array($sql_lainnya7,MYSQLI_ASSOC);

										$sql2 = mysqli_query($con, "SELECT
																	  SUM(payment_out.amount) AS keluar
																	FROM
																	  payment_out
																	WHERE sekolah_id = '".$row_list_sekolah['id']."'
																	 AND DATE(payment_out.date_created) BETWEEN '".$semester1."-07-01' AND '".$semester2."-06-30'");
										$buff2 = mysqli_fetch_array($sql2,MYSQLI_ASSOC);
										echo number_format(($buff['masuk']+$buff_lainnya7['lainnya_tahunan'])-$buff2['keluar'], 0 , '' , '.' ); ?>
									</td>
								<?php } ?>
								<td style="background-color: rgba(255, 153, 0, 0.28);color: #333;font-weight: bolder;"><h4>
								<?php
									$sql = mysqli_query($con, "SELECT
																  SUM(payment.amount) AS total_bulanan_in_year
																FROM
																  payment
																  JOIN kategori_keuangan
																ON kategori_keuangan.id = payment.kategori_keuangan_id
																WHERE DATE(payment.date_created) BETWEEN '".$semester1."-07-01' AND '".$semester2."-06-30'");
									$buff = mysqli_fetch_array($sql,MYSQLI_ASSOC);

									$sql_lainnya8 	= mysqli_query($con, "SELECT
																			  sum(amount) as total_lainnya_tahunan
																			FROM
																			  payment_lainnya
																			WHERE DATE(payment_lainnya.date_created) BETWEEN '".$semester1."-07-01' AND '".$semester2."-06-30'");
									$buff_lainnya8 	= mysqli_fetch_array($sql_lainnya8,MYSQLI_ASSOC);

									$sql2 = mysqli_query($con, "SELECT
															  SUM(payment_out.amount) AS total_bulanan_out_year
															FROM
															  payment_out
															WHERE DATE(payment_out.date_created) BETWEEN '".$semester1."-07-01' AND '".$semester2."-06-30'");
									$buff2 = mysqli_fetch_array($sql2,MYSQLI_ASSOC);
									echo "Rp. ".number_format(($buff['total_bulanan_in_year']+$buff_lainnya8['total_lainnya_tahunan']) - $buff2['total_bulanan_out_year'], 0 , '' , '.' ); ?>
								</h4>
								</td>
							</tr>
						</tbody>
						</table>
					<!-- </div> -->
				</div> <!-- /widget-content -->

			</div> <!-- /widget -->

	      </div> <!-- /span6 -->

      </div> <!-- /row -->

    </div> <!-- /container -->

</div> <!-- /main -->
