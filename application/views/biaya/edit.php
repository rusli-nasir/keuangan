<div class="main">

    <div class="container">

      <div class="row">   	
      	
      	<div class="col-md-5">		
      		
      		<div class="widget stacked widget-table action-table">
				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Edit Pembayaran</h3>
					<a class="btn btn-xs btn-info pull-right" onclick="goBack()">Back</a>
				</div> <!-- /widget-header -->
			
				<div class="widget-content">
					<form method="post" action="<?php echo base_url().'biaya/update_biaya/'.$biaya['id']; ?>" class="col-md-12">
						<div class="form-group under_widget">
							<label>Nama Pembayaran :</label>
						    <input type="text" class="form-control" name="nama_pembayaran" required value="<?php echo $biaya['nama_kategori']; ?>">
						</div> <!-- /.form-group -->

						<div class="form-group ">
				            <label>Jurusan</label>
				           <select class="form-control" name="jurusan">
						    	<option value="<?php echo $biaya['jurusan_id']; ?>"><?php echo $biaya['nama_jurusan']; ?></option>
						    	<option value="">Pilih</option>
						    	<?php foreach ($jurusan as $row_jurusan) {
						    		echo "<option value='".$row_jurusan['id']."'>".$row_jurusan['nama_jurusan']."</option>";
						    	} ?>
						    </select>
				        </div> <!-- /.form-group -->

				        <div class="form-group">
						    <label>Tahun masuk :</label>
						    <select name="tahun_masuk" class="form-control">
						    	<option><?php echo $biaya['tahun_masuk']; ?></option>
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
						    	<option value="<?php echo $biaya['id_semester']; ?>"><?php echo $biaya['semester']; ?></option>
						    	<option value="">Pilih</option>
						    	<?php foreach ($semester as $row_semester) {
						    		echo "<option value='".$row_semester['id']."'>".$row_semester['semester']."</option>";
						    	} ?>
						    </select>
						</div>

						<div class="form-group">
						    <label>Gender :</label>
						    <select name="gender" class="form-control">
						    	<option><?php echo $biaya['gender']; ?></option>
						    	<option value="">Pilih</option>
						    	<option>L</option>
						    	<option>P</option>
						    </select>
						</div>

						<div class="form-group">
						    <label>Biaya :</label>
						    <input type="number" class="form-control" name="biaya" placeholder="50000" required value="<?php echo $biaya['biaya']; ?>">
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