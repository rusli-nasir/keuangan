<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo baseAdminLte; ?>dist/img/avatar3.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->username ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active treeview">
          <a href="<?php echo base_url('v2/main'); ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        <!-- SIDEBAR MENU BENDAHARA SEKOLAH -->
        <?php if ($this->session->privilege_id == '1') { ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i>
            <span>Uang Masuk</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('v2/main/harian_in'); ?>"><i class="fa fa-circle-o"></i> Harian</a></li>
            <li><a href="<?php echo base_url('v2/main/bulanan_in'); ?>"><i class="fa fa-circle-o"></i> Bulanan</a></li>
            <li>
              <a href="#">
                <i class="fa fa-circle-o"></i> Kelas
                <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span>
              </a>
              <ul class="treeview-menu">
              <?php 
              $count = count($submenu);
              for ($i=0; $i < $count ; $i++) {
                $countSubs = count($submenu[$i]['DATA']);
               ?>
                <li>
                  <a href="#"><i class="fa fa-circle-o"></i> <?php echo $submenu[$i]['KELAS']; ?>
                  <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                <?php for ($x=0; $x < $countSubs ; $x++) {
                  $kelas_sekolah_id = $submenu[$i]['DATA'][$x]['KELAS_SEKOLAH_ID'];
                 ?>
                  <li><a href="<?php echo base_url('v2/main/kelas_in/'.$kelas_sekolah_id) ?>"><i class="fa fa-circle-o"></i> <?php echo $submenu[$i]['DATA'][$x]['KELAS_JURUSAN']; ?></a></li>
                <?php } ?>
                </ul></li>
              <?php } ?>
              </ul>
            </li>
            <!-- <li><a href="#"><i class="fa fa-circle-o"></i> Tahun</a></li> -->
            <!-- <li><a href="#"><i class="fa fa-circle-o"></i> Komite</a></li> -->
            <!-- <li><i class="#"></i> Kas</li> -->
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i>
            <span>Uang Keluar</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('v2/main/harian_out'); ?>"><i class="fa fa-circle-o"></i> Harian</a></li>
            <li><a href="<?php echo base_url('v2/main/bulanan_out/'.date('Y-m')) ?>"><i class="fa fa-circle-o"></i> Bulanan</a></li>
            <!-- <li><a href="#"><i class="fa fa-circle-o"></i> Tahun</a></li> -->
            <!-- <li><i class="fa fa-circle-o"></i> Kas</li> -->
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i>
            <span>Data</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('v2/main/biaya') ?>"><i class="fa fa-graduation-cap"></i> Biaya</a></li>
            <li><a href="<?php echo base_url('v2/main/Jurusan') ?>"><i class="fa fa-map-o"></i> Jurusan</a></li>
            <li><a href="<?php echo base_url('v2/main/listkelas') ?>"><i class="fa fa-industry"></i> Kelas</a></li>
            <li><a href="<?php echo base_url('v2/main/siswa') ?>"><i class="fa fa-group"></i> Siswa</a></li>
          </ul>
        </li>
        <?php } ?>
        <!-- END OF SIDEBAR MENU BENDAHARA SEKOLAH -->

        <!-- SIDEBAR MENU BENDAHARA YAYASAN -->
        <?php if ($this->session->privilege_id == '2') { ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i>
            <span>Rekap</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('v2/main/rekap_harian_yayasan') ?>"><i class="fa fa-bullseye"></i> Harian</a></li>
            <li><a href="<?php echo base_url('v2/main/rekap_bulanan_yayasan') ?>"><i class="fa fa-bullseye"></i> Bulanan</a></li>
          </ul>
        </li>
         <li class="treeview">
          <a href="#">
            <i class="fa fa-plus"></i>
            <span>Uang Masuk</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('v2/main/harian_in_yayasan') ?>"><i class="fa fa-bullseye"></i> Harian</a></li>
            <li><a href="<?php echo base_url('v2/main/bulanan_in_yayasan') ?>"><i class="fa fa-bullseye"></i> Bulanan</a></li>
          </ul>
        </li>
         <li class="treeview">
          <a href="#">
            <i class="fa fa-minus"></i>
            <span>Uang Keluar</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('v2/main/harian_out_yayasan') ?>"><i class="fa fa-bullseye"></i> Harian</a></li>
            <li><a href="<?php echo base_url('v2/main/bulanan_out') ?>"><i class="fa fa-bullseye"></i> Bulanan</a></li>
          </ul>
        </li>
        <?php } ?>
        <!-- END OF SIDEBAR MENU BENDAHARA YAYASAN -->

        <li>
          <a href="<?php echo base_url('v2/main/permintaan') ?>">
            <i class="fa fa-paper-plane-o"></i> <span>Permintaan</span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-tasks"></i>
            <span>Sisa Pembayaran</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('piutang/getListPiutang/'.$this->session->sekolah_id) ?>"><i class="fa fa-users"></i> 2016 - 2017</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-info"></i>
            <span>Informasi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-users"></i> Pengurus</a></li>
            <li><a href="#"><i class="fa fa-sticky-note"></i> Profil</a></li>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>