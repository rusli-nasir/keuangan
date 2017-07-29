<style type="text/css">
    .col-left{padding-left: 50px;}
</style>
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
                </div> <!-- /widget-header -->

                <div class="widget-content">
                    <form method="post" action="<?php echo base_url().'keuangan/edit_payment_out' ?>">
                        <br>
                        <input type="hidden" name="id" value="<?php echo $row_edit['id']; ?>">
                        <input type="hidden" name="opsi_bulan" value="<?php echo $keterangan; ?>">
                        <div class="row">
                            <div class="col-md-4 col-left">
                                <div class="form-group">
                                    <label>Jenis Pengeluaran</label>
                                    <select class="form-control" name="kategori_pengeluaran" required>
                                        <option value="<?php echo $row_edit['kategori_keuangan_pengeluaran_id'] ?>"><?php echo $row_edit['nama_kategori'] ?></option>
                                        <?php foreach ($list_pengeluaran as $row_list_pengeluaran) {
                                            echo "<option value='".$row_list_pengeluaran['id']."'>".$row_list_pengeluaran['nama_kategori']."</option>";
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jumlah :</label>
                                    <input type="number" class="form-control" name="amount" required value="<?php echo $row_edit['amount']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4" style="padding-right:40px;">
                                <div class="form-group">
                                    <label>Tanggal Pengeluaran :</label>
                                    <input type="text" class="form-control date-pickerindo" name="date_entry" readonly required value="<?php echo $row_edit['date_created']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-left" style="padding-right:40px;">
                                <div class="form-group">
                                    <label>Keterangan :</label>
                                    <input type="text" name="keterangan" class="form-control" required value="<?php echo $row_edit['keterangan']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="padding-left:20px">
                            <button type="submit" class="btn btn-default">Simpan</button>
                        </div> <!-- /.form-group -->
                    </form>
                </div> <!-- /widget-content -->

            </div> <!-- /widget -->

          </div> <!-- /span6 -->

      </div> <!-- /row -->

    </div> <!-- /container -->

</div> <!-- /main -->
