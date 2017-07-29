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
				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Daftar Siswa</h3>
					<a class="btn btn-xs btn-info pull-right" data-target="#add" data-toggle="modal" href="#">Tambah siswa</a>
				</div> <!-- /widget-header -->
				
				<div class="widget-content">
					
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>No</th>
								<th>NIS</th>
								<th>Nama Siswa</th>
								<th>Kelas</th>
								<th>Jurusan</th>
								<th>Group</th>
								<th>Gender</th>
								<th>Tahun Masuk</th>
								<th>Tanggal Diperbarui</th>
								<th>Oleh</th>
								<!-- <th>Kontrol</th> -->
							</tr>
						</thead>
						<tbody>
							<?php 
							$i=1;
							foreach ($siswa as $row) { ?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['nis'] ?></td>
									<td><?php echo $row['nama_siswa'] ?></td>
									<td><?php echo $row['kelas'] ?></td>
									<td><?php echo $row['nama_jurusan'] ?></td>
									<td><?php echo $row['group'] ?></td>
									<td><?php echo $row['gender'] ?></td>
									<td><?php echo $row['tahun_masuk'] ?></td>
									<td><?php echo $row['date_updated'] ?></td>
									<td><?php echo $row['username'] ?></td>
									<!-- <td>
										<a class="btn btn-xs btn-primary" href="<?php //echo base_url('siswa/edit_siswa/'.$row['id']) ;?>"><i class="btn-icon-only icon-edit"></i></a>
										<a class="btn btn-xs btn-danger" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php //echo base_url('siswa/delete_siswa/'.$row['id']) ;?>"><i class="btn-icon-only icon-trash"></i></a>
									</td> -->
									</td>
								</tr>
							<?php $i++; } ?>
							</tbody>
						</table>
					
				</div> <!-- /widget-content -->
			
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
          <h4 class="modal-title" id="mySmallModalLabel">Tambah Siswa</h4>
        </div>
        <div class="modal-body form-group">
        	<form method="post" action="<?php echo base_url().'siswa/add_siswa' ?>">
        		<div class="form-group">
				    <label>NIS :</label>
				    <input type="number" class="form-control" name="nis" required>
				</div>
        		<div class="form-group">
				    <label>Nama siswa :</label>
				    <input type="text" class="form-control" name="nama_siswa" required>
				</div>
				<div class="form-group">
				    <label>Jenis Kelamin :</label>
				    <select name="gender" class="form-control" required>
				    	<option value="">Pilih</option>
				    	<option value="L">Laki-laki</option>
				    	<option value="P">Perempuan</option>
				    </select>
				</div>
				<div class="form-group">
				    <label>Tahun Masuk :</label>
				    <select name="tahun_masuk" class="form-control" required>
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
						<option value="">Pilih</option>
						<?php foreach ($kelas_siswa as $row_kelas) { 
							echo "<option value='".$row_kelas['id']."'>".$row_kelas['kelas']." ".$row_kelas['nama_jurusan']." ".$row_kelas['group']."</option>";
						} ?>
					</select>
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