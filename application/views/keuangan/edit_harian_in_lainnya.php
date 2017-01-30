<div class="main">

    <div class="container">

      <div class="row">

      	<div class="col-md-8">

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
					<h3>Edit Penerimaan</h3>
					<a class='btn btn-xs btn-info pull-right' onclick='goBack()'>Back</a>
				</div> <!-- /widget-header -->

				<div class="widget-content">

					<form method="post" action="<?php echo base_url().'keuangan/edit_payment_in_lainnya' ?>">
		        		<input type="hidden" value="<?php echo $edit['id']; ?>" name="id">
		        		<input type="hidden" value="<?php echo $keterangan; ?>" name="opsi_bulan">
		        		<div class="row" style="margin:10px;">
		        			<div class="col-md-4 col-left">
				        		<div class="form-group">
								    <label>Penerimaan Keuangan :</label>
								    <select name="kategori_keuangan_id" class="form-control" required>
								    	<?php
								    	echo "<option value='".$edit['kategori_id']."'>".$edit['nama_kategori']."</option>";
								    	foreach ($list_penerimaan_lainnya as $row_lainnya) {
								    		echo "<option value='".$row_lainnya['id']."'>".$row_lainnya['nama_kategori']."</option>";
								    	} ?>
								    </select>
								</div>
		        			</div>
		        			<div class="col-md-4">
		        				<div class="form-group">
								    <label>Jumlah :</label>
								    <input type="number" class="form-control" name="amount" required value="<?php echo $edit['amount']; ?>">
								</div>
		        			</div>
		        			<?php if ($this->session->privilege_id == '2') { ?>
			        			<div class="col-md-4">
			        				<div class="form-group">
									    <label>Tanggal Penerimaan :</label>
									    <input type="text" class="form-control date-pickerindo" name="date_entry" readonly required value="<?php echo $edit['date_created']; ?>">
									</div>
			        			</div>
		        			<?php } ?>
		        		</div>
		        		<div class="row" style="margin:10px;">
		        			<div class="col-md-12 col-left">
		        				<label>Keterangan</label>
		        				<textarea class="form-control" required cols="5" rows="5" name="keterangan"><?php echo $edit['keterangan'] ?></textarea>
		        			</div>
		        		</div>
		        		<div class="form-group" style="margin-left:40px">
							<button type="submit" class="btn btn-default">Simpan</button>
						</div> <!-- /.form-group -->
		        	</form>

				</div> <!-- /widget-content -->

			</div> <!-- /widget -->

	      </div> <!-- /span6 -->

      </div> <!-- /row -->

    </div> <!-- /container -->

</div> <!-- /main -->
