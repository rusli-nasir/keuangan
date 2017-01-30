<style type="text/css">
	.edit{color:blue;}
	.delete{color:red;}
</style>
<div class="main">

    <div class="container">

      <div class="row">   	
      	
      	<div class="col-md-10">		
      		<div class="widget stacked widget-table action-table">
				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Daftar Pembayaran Komite</h3>
				</div> <!-- /widget-header -->
				
				<div class="widget-content">
					<!-- <div class="table-responsive">					 -->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<th>No</th>
							<th>NIS</th>
							<th>Nama</th>
							<th>Kelas</th>
							<th>Pembayaran</th>
							<th>Sisa</th>
						</thead>
						<tbody>
							<?php 
							$i=1;
							foreach ($list_pembayaran_komite as $row_list_pembayaran_komite) { ?>
								<tr>
									<td><?php echo $i++; ?></td>
									<td><?php echo $row_list_pembayaran_komite['NIS'] ?></td>
									<td><?php echo $row_list_pembayaran_komite['Nama_Siswa'] ?></td>
									<td><?php echo $row_list_pembayaran_komite['Kelas'] ?></td>
									<td><?php echo $row_list_pembayaran_komite['Pembayaran'] ?></td>
									<td><?php echo $row_list_pembayaran_komite['Sisa'] ?></td>
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