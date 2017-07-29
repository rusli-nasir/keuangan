<section class="content">
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
	            <div class="box-header with-border">
	              <h3 class="box-title">Pembayaran</h3>
	            </div>
	            <!-- /.box-header -->
	            <!-- form start -->
	            <form role="form" method="post" action="<?php echo base_url('keuangan/edit_payment_in') ?>">
	              <input type="hidden" value="<?php echo $edit['pay_id']; ?>" name="id">
	              <div class="box-body">
	              	<div class="row">
	              		<div class="col-md-6">
	              			<div class="form-group">
								<label>Kelas</label>
								<select class="form-control" name="kelas" id="kelas" required>
									<option value="<?php echo $edit['kelas_sekolah_id'] ?>"><?php echo $edit['kelas'].' '.$edit['nama_jurusan']; ?></option>
									<?php foreach ($data['kelasJurusan'] as $row_kelas) {
					                  echo "<option value='".$row_kelas['id']."'>".$row_kelas['kelas']."</option>";
					                } ?>
								</select>
							</div>
			                <div class="form-group">
							    <label>Pembayaran Keuangan :</label>
							    <select name="kategori_keuangan_id" class="form-control" required id="payment">
							    	<option value="<?php echo $edit['kategori_keuangan_id'] ?>"><?php echo $edit['nama_kategori'] ?></option>
							    </select>
							</div>
			                <div class="form-group">
							    <label>Jumlah :</label>
							    <input type="number" class="form-control" name="amount" required value="<?php echo $edit['amount'] ?>">
							</div>
	              		</div>
	              		<div class="col-md-6">
	              			<div class="form-group">
								<label>Siswa :</label>
								<select name="siswa_id" id="siswa" class="form-control" required>
									<option value="<?php echo $edit['siswa_id'] ?>"><?php echo $edit['nama_siswa']; ?></option>
								</select>
							</div>
			                <div class="form-group">
							    <label>Bulan :</label>
							    <select name="annualy" class="form-control" required id="annualy">
							    	<option value="<?php echo $edit['bulan_in_code'] ?>"><?php echo $edit['bulan'] ?></option>
							    </select>
							</div>
							<div class="form-group">
							    <label>Tahun :</label>
							    <select name="years" class="form-control" required>
							    	<option><?php echo $edit['tahun'] ?></option>
							    	<option><?php echo $edit['tahun']-1 ?></option>
							    	<option><?php echo $edit['tahun']+1 ?></option>
							    </select>
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