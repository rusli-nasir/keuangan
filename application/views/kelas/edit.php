<div class="main">

    <div class="container">

      <div class="row">   	
      	
      	<div class="col-md-5">		
      		
      		<div class="widget stacked widget-table action-table">
				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Edit Kelas</h3>
					<a class="btn btn-xs btn-info pull-right" onclick="goBack()">Back</a>
				</div> <!-- /widget-header -->
			
				<div class="widget-content">
					<form method="post" action="<?php echo base_url().'kelas/update_kelas/'.$data_kelas['id']; ?>" class="col-md-12">
						<div class="form-group under_widget">
							<label>Kelas</label>
							<select class="form-control" name="kelas" required>
								<?php
								 echo "<option value='".$data_kelas['kelas_id']."'>".$data_kelas['kelas']."</option>";
								 echo "<option value=''>-</option>";
								 foreach ($jenis_kelas as $row_kelas) { 
									echo "<option value='".$row_kelas['id']."'>".$row_kelas['kelas']."</option>";
								} ?>
							</select>
						</div>
		        		<div class="form-group">
						    <label for="exampleInputEmail1">Jurusan :</label>
						    <select class="form-control" name="jurusan" required>
						    	<?php 
						    	echo "<option value='".$data_kelas['jurusan_id']."'>".$data_kelas['nama_jurusan']."</option>";
						    	echo "<option value=''>-</option>";
						    	foreach ($jurusan as $row_jurusan) {
						    		echo "<option value='".$row_jurusan['id']."'>".$row_jurusan['nama_jurusan']."</option>";
						    	} ?>
						    </select>
						</div>
						<div class="form-group">
							<label>Group</label>
							<select class="form-control" name="group">
								<?php if (!empty($data_kelas['group'])) {
									echo "<option value='".$data_kelas['group']."'>".$data_kelas['group']."</option>";
								} ?>
								<option value="">-</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
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