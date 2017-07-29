<section class="content">
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
	            <div class="box-header with-border">
	              <h3 class="box-title">Penerimaan</h3>
	            </div>
	            <!-- /.box-header -->
	            <!-- form start -->
	            <form role="form" method="post" action="<?php echo base_url().'keuangan/edit_payment_in_lainnya' ?>">
	            <input type="hidden" value="<?php echo $edit['id']; ?>" name="id">
		        <input type="hidden" value="<?php echo $keterangan; ?>" name="opsi_bulan">
	              <div class="box-body">
	              	<div class="row">
          				<div class="col-md-4">
          					<div class="form-group">
								<label>Penerimaan :</label>
								<select name="kategori_keuangan_id" required>
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
          				<div class="col-md-4">
          					<?php if ($this->session->privilege_id == '2') { ?>
	        				<div class="form-group">
							    <label>Tanggal Penerimaan :</label>
							    <input type="text" class="form-control" id="datepickerIndo" name="date_entry" readonly required value="<?php echo $edit['date_created']; ?>">
							</div>
		        			<?php } ?>
          				</div>
	              	</div>
	              	<div class="row">
	              		<div class="col-md-12">
	              			<div class="form-group">
								<label>Keterangan</label>
		        				<textarea class="form-control" required cols="5" rows="5" name="keterangan"><?php echo $edit['keterangan'] ?></textarea>
							</div>
	              		</div>
	              	</div>
	              </div>
	              <!-- /.box-body -->

	              <div class="box-footer">
	                <button type="submit" class="btn btn-success pull-right margin">Simpan</button>
	              	<button type="button" class="btn btn-danger pull-right margin" onclick='goBack()'>Batal</button>
	              </div>
	            </form>
	        </div>
		</div>
	</div>
</section>