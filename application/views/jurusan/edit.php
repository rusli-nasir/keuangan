<div class="main">

    <div class="container">

      <div class="row">   	
      	
      	<div class="col-md-6">		
      		
      		<div class="widget stacked widget-table action-table">
				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Edit Jurusan</h3>
				</div> <!-- /widget-header -->
				
				<div class="widget-content">
					
					<form method="post" action="<?php echo base_url().'jurusan/update_jurusan/'.$jurusan['id']; ?>" class="col-md-12">
		        		<div class="form-group under_widget">
						    <label>Nama Jurusan :</label>
						    <input type="text" class="form-control" name="nama_jurusan" required value="<?php echo $jurusan['nama_jurusan']; ?>">
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