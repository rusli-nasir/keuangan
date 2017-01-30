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
					<h3>Edit Uang Keluar</h3>
					<a class='btn btn-xs btn-info pull-right' onclick='goBack()'>Back</a>
				</div> <!-- /widget-header -->

				<div class="widget-content">

					<form method="post" action="<?php echo base_url().'keuangan/edit_payment_in' ?>">
		        		<input type="hidden" value="<?php echo $edit['pay_id']; ?>" name="id">
		        		<div class="row" style="margin:10px;">
		        			<div class="col-md-6 col-left">
		        				<div class="form-group">
									<label>Kelas</label>
									<select class="form-control" name="kelas" id="kelas" required>
										<option value="<?php echo $edit['kelas_sekolah_id'] ?>"><?php echo $edit['kelas'].' '.$edit['nama_jurusan']; ?></option>
										<?php foreach ($data['kelasJurusan'] as $row_kelas) {
						                  echo "<option value='".$row_kelas['id']."'>".$row_kelas['kelas']."</option>";
						                } ?>
									</select>
								</div>
		        			</div>
		        			<div class="col-md-6">
		        				<div class="form-group">
									<label>Siswa :</label>
									<select name="siswa_id" id="siswa" class="form-control" required>
										<option value="<?php echo $edit['siswa_id'] ?>"><?php echo $edit['nama_siswa']; ?></option>
									</select>
								</div>
		        			</div>
		        		</div>
		        		<div class="row" style="margin:10px;">
		        			<div class="col-md-5 col-left">
				        		<div class="form-group">
								    <label>Pembayaran Keuangan :</label>
								    <select name="kategori_keuangan_id" class="form-control" required id="payment">
								    	<option value="<?php echo $edit['kategori_keuangan_id'] ?>"><?php echo $edit['nama_kategori'] ?></option>
								    </select>
								</div>
		        			</div>
		        			<div class="col-md-4 col-left">
				        		<div class="form-group">
								    <label>Bulan :</label>
								    <select name="annualy" class="form-control" required id="annualy">
								    	<option value="<?php echo $edit['bulan_in_code'] ?>"><?php echo $edit['bulan'] ?></option>
								    </select>
								</div>
		        			</div>
		        			<div class="col-md-3">
		        				<div class="form-group">
								    <label>Jumlah :</label>
								    <input type="number" class="form-control" name="amount" required value="<?php echo $edit['amount'] ?>">
								</div>
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
