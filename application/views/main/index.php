<?php
$current_month 	= date('Y-m');
$current_date 	= date('Y-m-d');
$sekolah_id 	= $this->session->sekolah_id;
$privilege_id 	= $this->session->privilege_id; ?>
<style type="text/css">
	.answer{
		padding-left:75px;
	}
</style>
<div class="main">

    <div class="container">

      <div class="row">

      	<div class="col-md-6 col-xs-12">

      		<div class="widget stacked">

				<div class="widget-header">
					<i class="icon-star"></i>
					<h3>Welcome Message</h3>
				</div> <!-- /widget-header -->

				<div class="widget-content">
					<p>Selamat datang di Sistem Informasi Manajemen Keuangan YPPN</p>
					<p>Mohon menjaga Akun anda secara rahasia, jika ada kendala harap hubungi Administrator. (081289008786)</p>

				</div> <!-- /widget-content -->

			</div> <!-- /widget -->

			<!--
			<div class="widget">
				<div class="widget-content">
					<div class="media">
					  <div class="media-body">
					    <h4 class="media-heading">Media heading</h4>
					    jawaban anda ada disini, semoga terselesaikan dengan baik
					    <div class="media answer">
						  <div class="media-body">
						    <h4 class="media-heading">Media heading</h4>
						    jawaban anda ada disini, semoga terselesaikan dengan baik
						  </div>
						</div>
					  </div>
					</div>
				</div>
			</div>
			-->
	    </div> <!-- /span6 -->


      	<div class="col-md-6">


      		<div class="widget stacked">

				<div class="widget-header">
					<i class="icon-bookmark"></i>
					<h3>Quick Shortcuts</h3>
				</div> <!-- /widget-header -->

				<div class="widget-content">

					<div class="shortcuts">
						<?php if ($this->session->privilege_id == '1') { ?>
							<a href="<?php echo base_url('main/harian_in') ?>" class="shortcut">
						<?php } else { ?>
							<a href="<?php echo base_url('main/rekap_harian_yayasan/'.$current_date) ?>" class="shortcut">
						<?php } ?>
							<i class="shortcut-icon icon-calendar-empty"></i>
							<span class="shortcut-label">Uang Masuk Harian</span>
						</a>


						<a href="<?php echo base_url('main/harian_out') ?>" class="shortcut">
							<i class="shortcut-icon icon-calendar-empty"></i>
							<span class="shortcut-label">Uang Keluar Harian</span>
						</a>

						<a href="<?php echo base_url('main/bulanan_out/'.$current_month) ?>" class="shortcut">
							<i class="shortcut-icon icon-calendar"></i>
							<span class="shortcut-label">Uang Keluar Bulanan</span>
						</a>

						<a href="javascript:;" class="shortcut">
							<i class="shortcut-icon icon-user"></i>
							<span class="shortcut-label">Data Pengguna</span>
						</a>

						<a href="<?php echo base_url('main/biaya'); ?>" class="shortcut">
							<i class="shortcut-icon icon-money"></i>
							<span class="shortcut-label">Data Biaya</span>
						</a>

						<a href="<?php echo base_url('main/jurusan'); ?>" class="shortcut">
							<i class="shortcut-icon icon-file"></i>
							<span class="shortcut-label">Data Jurusan</span>
						</a>

						<a href="<?php echo base_url('main/kelas'); ?>" class="shortcut">
							<i class="shortcut-icon icon-file"></i>
							<span class="shortcut-label">Data Kelas</span>
						</a>

						<a href="<?php echo base_url('main/siswa'); ?>" class="shortcut">
							<i class="shortcut-icon icon-group"></i>
							<span class="shortcut-label">Data Siswa</span>
						</a>
					</div> <!-- /shortcuts -->

				</div> <!-- /widget-content -->

			</div> <!-- /widget -->

	      </div> <!-- /span6 -->

      </div> <!-- /row -->

      <div class="row col-s"></div>

    </div> <!-- /container -->

</div> <!-- /main -->
