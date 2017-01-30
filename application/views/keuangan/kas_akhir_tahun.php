<style type="text/css">
	.edit{color:blue;}
	.delete{color:red;}
</style>
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
				<?php 
					$sekolah_id 		= $this->session->sekolah_id;
				?>
				
				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Uang Kas Akhir Tahun 2015 <?php echo $nama_sekolah; ?></h3>
					<a class='btn btn-xs btn-info pull-right' onclick='goBack()'>Back</a>
				</div> <!-- /widget-header -->
				
				<div class="widget-content">	
					<table class="table table-striped table-bordered">
						<thead>
							<th>No</th>
							<th>Tanggal</th>
							<th>Keterangan</th>
							<th style="color:orange;">Jumlah (<?php echo number_format($get_total_in_harian_lainnya, 0 , '' , '.' ) ?>)</th>
						</thead>
						<tbody>
							<?php 
							$num = 1;
							foreach ($payment_lainnya as $row_payment_lainnya) { ?>								
								<tr>
									<td><?php echo $num++ ?></td>
									<td><?php echo $row_payment_lainnya['date_created'] ?></td>
									<td><?php echo $row_payment_lainnya['keterangan'] ?></td>
									<td><?php echo number_format($row_payment_lainnya['amount'], 0 , '' , '.' );
										if ($this->session->privilege_id == '1') { ?>
											<a class="edit" href="<?php echo base_url('keuangan/get_payment_id_lainnya/'.$row_payment_lainnya['id']) ;?>" title="Edit"><i class="icon-edit"></i></a>
											<a class="delete" onclick="return confirm('Yakin menghapus data ini ?');" href="<?php echo base_url('keuangan/delete_harian_in_lainnya/'.$row_payment_lainnya['id']) ;?>" title="Delete"><i class="icon-trash"></i></a>
									<?php }  ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>						
				</div> <!-- /widget-content -->
			
			</div> <!-- /widget -->
								
	      </div> <!-- /span6 -->
      	
      </div> <!-- /row -->

    </div> <!-- /container -->
    
</div> <!-- /main -->