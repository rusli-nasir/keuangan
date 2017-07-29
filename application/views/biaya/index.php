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
					<h3>Daftar Pembayaran</h3>
					<a class="btn btn-xs btn-info pull-right" data-target="#add" data-toggle="modal" href="#">Tambah Pembayaran</a>
				</div> <!-- /widget-header -->
			
				<div class="widget-content">
					
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Pembayaran</th>
								<th>Jurusan</th>
								<th>Tahun Masuk</th>
								<th>Semester</th>
								<th>Gender</th>
								<th>Biaya</th>
								<th>Tanggal Dibuat</th>
								<th>Tanggal Diperbarui</th>
								<th>Oleh</th>
								<!-- <th>Kontrol</th> -->
							</tr>
						</thead>
						<tbody>
							<?php 
							$i=1;
							foreach ($biaya as $row) { ?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['nama_kategori'] ?></td>
									<td><?php echo $row['nama_jurusan'] ?></td>
									<td><?php echo $row['tahun_masuk'] ?></td>
									<td><?php echo $row['semester'] ?></td>
									<td><?php echo $row['gender'] ?></td>
									<td><?php echo 'Rp. '.number_format($row['biaya'], 0 , '' , '.' ).',-'; ?></td>
									<td><?php echo $row['date_created'] ?></td>
									<td><?php echo $row['date_updated'] ?></td>
									<td><?php echo $row['username'] ?></td>
									<!-- <td>
										<a class="btn btn-xs btn-primary" href="<?php //echo base_url('biaya/edit_biaya/'.$row['id']) ;?>"><i class="btn-icon-only icon-edit"></i></a>
										<a class="btn btn-xs btn-danger" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php //echo base_url('biaya/delete_biaya/'.$row['id']) ;?>"><i class="btn-icon-only icon-trash"></i></a>
									</td> -->
								</tr>
							<?php $i++;
							 } ?>
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
          <h4 class="modal-title" id="mySmallModalLabel">Tambah Pembayaran</h4>
        </div>
        <div class="modal-body form-group">
        	<form method="post" action="<?php echo base_url().'biaya/add_biaya' ?>">
        		<div class="form-group">
				    <label>Nama Pembayaran :</label>
				    <input type="text" class="form-control" name="nama_pembayaran" required>
				</div>
				<div class="form-group">
				    <label>Jurusan :</label>
				    <select class="form-control" name="jurusan">
				    	<option value="(null)">Pilih</option>
				    	<?php foreach ($jurusan as $row_jurusan) {
				    		echo "<option value='".$row_jurusan['id']."'>".$row_jurusan['nama_jurusan']."</option>";
				    	} ?>
				    </select>
				</div>
				<div class="form-group">
				    <label>Tahun masuk :</label>
				    <select name="tahun_masuk" class="form-control">
				    	<option value="">Pilih</option>
				    	<option>2013</option>
				    	<option>2014</option>
				    	<option>2015</option>
				    	<option>2016</option>
				    </select>
				</div>
				<div class="form-group">
				    <label>Semester :</label>
				    <select name="semester" class="form-control">
				    	<option value="">Pilih</option>
				    	<?php foreach ($semester as $row_semester) {
				    		echo "<option value='".$row_semester['id']."'>".$row_semester['semester']."</option>";
				    	} ?>
				    </select>
				</div>
				<div class="form-group">
				    <label>Gender :</label>
				    <select name="gender" class="form-control">
				    	<option value="">Pilih</option>
				    	<option>L</option>
				    	<option>P</option>
				    </select>
				</div>
				<div class="form-group">
				    <label>Biaya :</label>
				    <input type="number" class="form-control" name="biaya" placeholder="50000" required>
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