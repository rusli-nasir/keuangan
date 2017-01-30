<style type="text/css">
	.edit{color:blue;}
	.delete{color:red;}
</style>
<div class="main">

    <div class="container">

      <div class="row">   	
      	
      	<div class="col-md-12">		
      		
      		<div class="widget stacked widget-table action-table">
				
				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Daftar Pengeluaran <?php echo $nama_sekolah.' '.$bulan; ?></h3>
					<a class='btn btn-xs btn-primary pull-right' href="<?php echo base_url('keuangan/export_bulanan_out_yayasan/'.$sekolah_id.'/'.$month) ?>">Print</a>
					<a class='btn btn-xs btn-info pull-right' onclick='goBack()' style="margin-right:10px;">Back</a>
				</div> <!-- /widget-header -->
				
				<div class="widget-content">
					<table class="table table-striped table-bordered">
						<thead>
							<th>No</th>
							<th>Tanggal</th>
							<th>Uraian</th>
							<th>Keterangan</th>
							<th style="color:orange;">Jumlah (<?php echo number_format($total_bulanan_out, 0 , '' , '.' ) ?>)</th>
						</thead>
						<tbody>
							<?php 
							$num = 1;
							foreach ($list_bulanan_out as $row_list_bulanan_out) { ?>								
								<tr>
									<td><?php echo $num++ ?></td>
									<td><?php echo $row_list_bulanan_out['date_created'] ?></td>
									<td><?php echo $row_list_bulanan_out['nama_kategori'] ?></td>
									<td><?php echo $row_list_bulanan_out['keterangan'] ?></td>
									<td><?php echo number_format($row_list_bulanan_out['amount'], 0 , '' , '.' ); ?></td>
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