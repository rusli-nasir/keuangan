<style type="text/css">
	.edit{color:blue;}
	.delete{color:red;}
</style>
<div class="main">

    <div class="container">

      <div class="row">   	
      	
      	<div class="col-md-10">		
      		<div class="row container">
      			<form method="post" action="<?php echo base_url().'main/permintaan' ?>">
				<div class="form-group col-md-10">
					<select name="bulan" required>
						<option value="">Pilih</option>
						<?php foreach ($list_bulan as $row_list_bulan) {
							echo "<option value='".$row_list_bulan['bulan_in_code']."'>".$row_list_bulan['bulan']."</option>";
						} ?>
					</select>
					<select name="tahun" required>
						<option value="">Pilih</option>
						<option>2015</option>
						<option>2016</option>
						<option>2017</option>
						<option>2018</option>
					</select>
					<input type="submit" class="btn btn-sm btn-danger" value="Ok">
				</div>
			</form>
      		</div>
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
					<h3>Daftar Permintaan Bulan <?php echo nice_date($month, 'M Y'); ?></h3>
					<?php if ($this->session->privilege_id =='1') {
						echo "<a class='btn btn-xs btn-info pull-right' data-target='#add' data-toggle='modal' href='#'>Permintaan</a>";
					} elseif ($this->session->privilege_id =='2') {
						echo "<a class='btn btn-xs btn-info pull-right' onclick='goBack()'>Back</a>";
					} ?>
				</div> <!-- /widget-header -->
				
				<div class="widget-content">
					<!-- <div class="table-responsive">					 -->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th rowspan='2' class="thtable num">No</th>
								<th rowspan='2' class="thtable num2">Tanggal</th>
								<th rowspan='2' class="thtable">Keterangan</th>
								<th rowspan='2' class="thtable amount">Jumlah <br><b style='color:red;'><?php echo number_format($total_permintaan, 0 , '' , '.' ); ?></b></th>
								<th rowspan='2' class="thtable num2">Status</th>
								<?php if ($this->session->privilege_id != '1') {
									echo "<th rowspan='2' class='thtable'>Sekolah</th>";
								} ?>								
								<th rowspan='2' class="thtable num">Kontrol</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=1; foreach ($list_permintaan as $row) { ?>
								<tr>
									<td class="thtable2"><?php echo $i++; ?></td>
									<td class="thtable2"><?php echo nice_date($row['date_created'], 'd-m-Y'); ?></td>
									<td class="thtable2"><?php echo $row['keterangan']; ?></td>
									<td class="thtable2"><?php echo number_format($row['amount'], 0 , '' , '.' ); ?></td>
									<td class="thtable2" style="text-align:center">
										<?php if (($this->session->privilege_id != '1' and $row['status'] != 'Terkirim') or ($this->session->privilege_id == '1')) {
											echo $row['status'];
										} else {
											echo '<div style="color:red";><b>------</b></div>';
										}?>
									</td>
									<?php if ($this->session->privilege_id != '1') {
										echo "<td class='thtable2'>".$row['nama_sekolah']."</td>";
									} ?>									
									<td class="thtable2" style="text-align:center;">
										<?php if ($row['status']!='Diterima' and $this->session->privilege_id=='1') { ?>
											<a class="edit" href="#" data-target='#edit<?php echo $row['id']; ?>' data-toggle='modal' title="Edit"><i class="icon-edit"></i></a>
											<a class="delete" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_permintaan/'.$row['id']) ;?>" title="Delete"><i class="icon-trash"></i></a>
										<?php } else if($row['status'] != 'Diterima' and $this->session->privilege_id == '2'){
												if ($row['status'] == 'Ditolak') { ?>
													<a class="edit" onclick="return confirm('Apakah Permintaan ini Diterima ?');" href="<?php echo base_url('keuangan/permintaan_diterima/'.$row['id']) ;?>" title="Diterima"><i class="icon-ok"></i></a>
										<?php	} else if($row['status'] == 'Diterima') { ?>
													<a class="delete" onclick="return confirm('Apakah Permintaan ini Ditolak ?');" href="<?php echo base_url('keuangan/permintaan_ditolak/'.$row['id']) ;?>" title="Ditolak"><i class="icon-remove"></i></a>
										<?php	} else { ?>
													<a class="edit" onclick="return confirm('Apakah Permintaan ini Diterima ?');" href="<?php echo base_url('keuangan/permintaan_diterima/'.$row['id']) ;?>" title="Diterima"><i class="icon-ok"></i></a>&nbsp;
													<a class="delete" onclick="return confirm('Apakah Permintaan ini Ditolak ?');" href="<?php echo base_url('keuangan/permintaan_ditolak/'.$row['id']) ;?>" title="Ditolak"><i class="icon-remove"></i></a>
										<?php } } ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						</table>
					<!-- </div> -->
				</div> <!-- /widget-content -->
			
			</div> <!-- /widget -->
								
	      </div> <!-- /span6 -->
      	
      </div> <!-- /row -->

    </div> <!-- /container -->
    
</div> <!-- /main -->

<?php foreach ($list_permintaan as $row2) { ?>
<!-- MODAL Edit -->
<div id="edit<?php echo $row2['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
    	<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Edit Permintaan</h4>
        </div>
        <div class="modal-body form-group">
        	<form method="post" action="<?php echo base_url().'keuangan/edit_permintaan' ?>">
        		<input type="hidden" name="id" value="<?php echo $row2['id'] ?>">
        		<div class="row">
        			<div class="col-md-8 col-left">
        				<div class="form-group">
							<label>Keterangan :</label>
							<input type="text" name="keterangan" class="form-control" required value="<?php echo $row2['keterangan']; ?>">
						</div>
        			</div>
        			<div class="col-md-4">
        				<div class="form-group">
						    <label>Jumlah :</label>
						    <input type="number" class="form-control" name="amount" required value="<?php echo $row2['amount']; ?>">
						</div>
        			</div>
        		</div>
        		<div class="form-group">
					<button type="submit" class="btn btn-default">Simpan</button>
				</div> <!-- /.form-group -->
        	</form>
        </div>
    </div>
  </div>
</div>
<!-- END MODAL Edit -->
<?php } ?>

<!-- MODAL ADD -->
<div id="add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
    	<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mySmallModalLabel">Form Permintaan</h4>
        </div>
        <div class="modal-body form-group">
        	<form method="post" action="<?php echo base_url().'keuangan/add_permintaan' ?>">
        		<div class="row">
        			<div class="col-md-8 col-left">
        				<div class="form-group">
							<label>Keterangan :</label>
							<input type="text" name="keterangan" class="form-control" required>
						</div>
        			</div>
        			<div class="col-md-4">
        				<div class="form-group">
						    <label>Jumlah :</label>
						    <input type="number" class="form-control" name="amount" required>
						</div>
        			</div>
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