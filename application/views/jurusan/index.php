<div class="main">

    <div class="container">

      <div class="row">   	
      	
      	<div class="col-md-6">		
      		
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
					<h3>Daftar Jurusan</h3>
					<a class="btn btn-xs btn-info pull-right" data-target="#add" data-toggle="modal" href="#">Tambah Jurusan</a>
				</div> <!-- /widget-header -->
				
				<div class="widget-content">
					
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Jurusan</th>
								<th>Tanggal Diperbarui</th>
								<!-- <th>Kontrol</th> -->
							</tr>
						</thead>
						<tbody>
							<?php 
							$i=1;
							foreach ($jurusan as $row) { ?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $row['nama_jurusan'] ?></td>
									<td><?php echo $row['date_updated'] ?></td>
									<!-- <td>
										<a class="btn btn-xs btn-primary" href="<?php //echo base_url('jurusan/edit_jurusan/'.$row['id']) ;?>"><i class="btn-icon-only icon-edit"></i></a>
										<a class="btn btn-xs btn-danger" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php //echo base_url('jurusan/delete_jurusan/'.$row['id']) ;?>"><i class="btn-icon-only icon-trash"></i></a>
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
          <h4 class="modal-title" id="mySmallModalLabel">Tambah Jurusan</h4>
        </div>
        <div class="modal-body form-group">
        	<form method="post" action="<?php echo base_url().'jurusan/add_jurusan' ?>">
        		<div class="form-group">
				    <label>Nama Jurusan :</label>
				    <input type="text" class="form-control" name="nama_jurusan" required>
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