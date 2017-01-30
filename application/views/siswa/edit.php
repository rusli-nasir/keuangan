<div class="main">

    <div class="container">

      <div class="row">   	
      	
      	<div class="col-md-6">		
      		
      		<div class="widget stacked widget-table action-table">
				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Edit Siswa</h3>
					<a class="btn btn-xs btn-info pull-right" onclick="goBack()">Back</a>
				</div> <!-- /widget-header -->
				
				<div class="widget-content">
					
					<form method="post" action="<?php echo base_url().'siswa/update_siswa/'.$siswa['id'] ?>" class="col-md-12">
		        		<div class="form-group under_widget">
						    <label>NIS :</label>
						    <input type="number" class="form-control" name="nis" required value="<?php echo $siswa['nis']; ?>">
						</div>
		        		<div class="form-group">
						    <label>Nama siswa :</label>
						    <input type="text" class="form-control" name="nama_siswa" required value="<?php echo $siswa['nama_siswa']; ?>">
						</div>
						<div class="form-group">
						    <label>Jenis Kelamin :</label>
						    <?php 
							    if ($siswa['gender']=='P') {
							    	$gender = 'Perempuan';
							    }else{
							    	$gender = 'Laki-laki';
							    }
						    ?>
						    <select name="gender" class="form-control" required>
						    	<option value="<?php echo $siswa['gender']; ?>"><?php echo $gender; ?></option>
						    	<option value="">Pilih</option>
						    	<option value="L">Laki-laki</option>
						    	<option value="P">Perempuan</option>
						    </select>
						</div>
						<div class="form-group">
						    <label>Tahun Masuk :</label>
						    <select name="tahun_masuk" class="form-control" required>
						    	<option><?php echo $siswa['tahun_masuk']; ?></option>
						    	<option value="">Pilih</option>
						    	<option>2013</option>
						    	<option>2014</option>
						    	<option>2015</option>
						    	<option>2016</option>
						    </select>
						</div>
						<div class="form-group">
							<label>Kelas</label>
							<select class="form-control" name="kelas" required>
								<?php 
								echo "<option value='".$siswa['kelas_sekolah_id']."'>".$siswa['kelas']." ".$siswa['nama_jurusan']." ".$siswa['group']."</option>";
								foreach ($kelas_siswa as $row_kelas) { 
									echo "<option value='".$row_kelas['id']."'>".$row_kelas['kelas']." ".$row_kelas['nama_jurusan']." ".$row_kelas['group']."</option>";
								} ?>
							</select>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-default">Simpan</button>
						</div> <!-- /.form-group -->
		        	</form>

				</div> <!-- /widget-content -->
			
			</div> <!-- /widget -->
								
	      </div> <!-- /span6 -->
      	
      </div> <!-- /row -->

    </div> <!-- /container -->
    
</div> <!-- /main -->