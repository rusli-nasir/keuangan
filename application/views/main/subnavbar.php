<?php
$current_month 	= date('Y-m');
$current_date 	= date('Y-m-d');
$sekolah_id 	= $this->session->sekolah_id;
$privilege_id 	= $this->session->privilege_id; ?>
<div class="subnavbar">

	<div class="subnavbar-inner">

		<div class="container">

			<a href="javascript:;" class="subnav-toggle" data-toggle="collapse" data-target=".subnav-collapse">
		      <span class="sr-only">Toggle navigation</span>
		      <i class="icon-reorder"></i>

		    </a>

			<div class="collapse subnav-collapse">
				<ul class="mainnav">

					<li class="active">
						<a href="<?php echo base_url('main'); ?>">
							<i class="icon-home"></i>
							<span>Beranda</span>
						</a>
					</li>

					<!-- <li class="active">
						<a href="./index.html">
							<i class="icon-home"></i>
							<span>User</span>
						</a>
					</li> -->
					<?php
						if ($privilege_id=='1') { ?>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-th"></i>
								<span>Uang Masuk</span>
								<b class="caret"></b>
							</a>

							<ul class="dropdown-menu">

								<li><a href="<?php echo base_url('main/harian_in') ?>">Harian</a></li>
								<li><a href="<?php echo base_url('keuangan/payment_in_perbulan') ?>">Bulanan</a></li>
								<?php /* ?>
								<li class="dropdown-submenu">
								    <a href="#">Kelas</a>
								    <ul class="dropdown-menu">
								      <?php
								      $con = mysqli_connect("localhost","ranahweb_yayasan","yayasan123","ranahweb_nasional");
								      foreach ($kelas as $row_kelas) {
								      		$sql = mysqli_query($con, "SELECT nama_jurusan,kelas_sekolah.id as ksid, kelas_sekolah.group as groups FROM kelas_sekolah JOIN jurusan ON jurusan.id=kelas_sekolah.jurusan_id WHERE kelas_id='".$row_kelas['kelas_id']."' and sekolah_id='$sekolah_id'");

								       ?>
								      	 <li class="dropdown-submenu"><a href="#"><?php echo $row_kelas['kelas']; ?></a>
								      	 		 <ul class="dropdown-menu">
										      	 	<?php while ($buff=mysqli_fetch_array($sql,MYSQLI_ASSOC)) { ?>
													    <li><a href="<?php echo base_url('main/bulanan_in/'.$buff['ksid']) ?>"><?php echo $buff['nama_jurusan'].' '.$buff['groups'] ?></a></li>
										      	 	<?php } ?>
											    </ul>
								      	 </li>
								      <?php } ?>

								    </ul>
								  </li>
								<?php  */ ?>
								<li class="dropdown-submenu">
								    <a href="#">Kelas</a>
								    <ul class="dropdown-menu">
								    	<?php
								    	$con = mysqli_connect("localhost","root","Jatis123","ranahweb_nasional");
								    	foreach ($kelas as $key_kelas => $value_kelas) {
								    		$kelas_id = $value_kelas['kelas_id'];
								    		$sekolah_id = $value_kelas['sekolah_id'];
								    		$sql = mysqli_query($con, "SELECT
																		  CONCAT_WS(
																		    ' ',
																		    jurusan.nama_jurusan,
																		    kelas_sekolah.group
																		  ) AS kelas,
																		  kelas_sekolah.id
																		FROM
																		  kelas_sekolah
																		  JOIN jurusan
																		    ON kelas_sekolah.jurusan_id = jurusan.id
																		  JOIN kelas
																		    ON kelas.id = kelas_sekolah.kelas_id
																		WHERE kelas.id='$kelas_id' AND jurusan.sekolah_id='$sekolah_id' AND kelas_sekolah.status='show'");
								    			if ($value_kelas['jumlah_kelas'] > 1) {
										?>
										      	<li class="dropdown-submenu"><a href="#"><?php echo $value_kelas['kelas']; ?></a>
											      	<?php
											      		//if ($value_kelas['amount_group'] > 1) { ?>
								      	 				<ul class="dropdown-menu">
										      				<?php while ($buff = mysqli_fetch_array($sql,MYSQLI_ASSOC)) { ?>
										      					<li><a href="<?php echo base_url('main/bulanan_in/'.$buff['id']) ?>"><?php echo $buff['kelas']?></a></li>
										      				<?php } ?>
										      			</ul>
										      		<?php //}
											    }else{
											    	echo '<li class="dropdown-submenu"><a href="'.base_url('main/bulanan_in/'.$value_kelas['kelas_sekolah_id']).'">'.$value_kelas['kelas_jurusan'].'</a>';
											    }

										      	 ?>
										      	</li>
								    		<?php
								    	} ?>
								    </ul>
								</li>
								<li><a href="#">Tahun</a></li>
								<?php if ($this->session->sekolah_id==1) {
									echo "<li><a href='".base_url('main/komite')."'>Komite</a></li>";
								} ?>
								<li><a href="<?php echo base_url('main/kas_akhir_tahun') ?>">Kas Tahun 2015</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-th"></i>
								<span>Uang Keluar</span>
								<b class="caret"></b>
							</a>

							<ul class="dropdown-menu">
								<li><a href="<?php echo base_url('main/harian_out') ?>">Harian</a></li>
								<li><a href="<?php echo base_url('main/bulanan_out/'.$current_month) ?>">Bulanan</a></li>
								<li><a href="#">Tahun</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-th"></i>
								<span>Data</span>
								<b class="caret"></b>
							</a>

							<ul class="dropdown-menu">
								<li><a href="<?php echo base_url('main/biaya'); ?>">Biaya</a></li>
								<li><a href="<?php echo base_url('main/jurusan'); ?>">Jurusan</a></li>
								<li><a href="<?php echo base_url('main/kelas'); ?>">Kelas</a></li>
								<li><a href="<?php echo base_url('main/siswa'); ?>">Siswa</a></li>
							</ul>
						</li>
						?>
						<?php } else if ($privilege_id=='2'){ ?>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-th"></i>
								<span>Keuangan</span>
								<b class="caret"></b>
							</a>

							<ul class="dropdown-menu">
								<li class="dropdown-submenu"><a href="#">Rekap</a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo base_url('main/rekap_harian_yayasan/'.$current_date) ?>">Harian</a></li>
									<li><a href="<?php echo base_url('main/rekap_bulanan_yayasan/'.$tahun_ajar) ?>">Bulanan</a></li>
								</ul></li>
								<li class="dropdown-submenu"><a href="#">Uang masuk</a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo base_url('main/harian_in_yayasan') ?>">Harian</a></li>
									<li><a href="<?php echo base_url('main/bulanan_in_yayasan') ?>">Bulanan</a></li>
								</ul></li>
								<li class="dropdown-submenu"><a href="#">Uang Keluar</a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo base_url('main/harian_out_yayasan') ?>">Harian</a></li>
									<li><a href="<?php echo base_url('main/bulanan_out/'.$current_month) ?>">Bulanan</a></li>
								</ul></li>
							</ul>
						</li>
					<?php } ?>

					<li>
						<a href="<?php echo base_url('main/permintaan'); ?>">
							<i class="icon-th"></i>
							<span>Permintaan</span>
						</a>
					</li>

					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-th"></i>
							<span>Informasi</span>
							<b class="caret"></b>
						</a>

						<ul class="dropdown-menu">
							<li><a href="#">Pengurus</a></li>
							<li><a href="#">Profil</a></li>
						</ul>
					</li>

				</ul>
			</div> <!-- /.subnav-collapse -->

		</div> <!-- /container -->

	</div> <!-- /subnavbar-inner -->

</div> <!-- /subnavbar -->
