<!DOCTYPE html>
<html>
<head>
  <title>Report Table</title>
    <link rel="stylesheet" href="<?php echo baseAdminLte; ?>dist/css/AdminLTE.min.css">
    <style>
      table{
        border: 10pt;
        /*width: 95%;*/
        border-color: #111111;
      }
      td{
        padding-right: 15px;
        padding-left: 15px;
        /*white-space: nowrap;*/
      }

    </style>
</head>
<body>
	<div id="outtable">
    <h2><b><?php echo $keterangan; ?></b></h2>
    <table class="table table-bordered table-Striped">
      <thead> 
        <tr> 
          <th rowspan="2" class="bg-light-blue color-palette">No</th>
          <th rowspan="2" class="bg-light-blue color-palette">Nama</th>
          <th rowspan="2" class="bg-light-blue color-palette">Kelas</th>
          <th colspan="12" style="text-align: center" class="bg-light-blue color-palette">Tahun Ajaran <?php echo $tahunajaran; ?></th>
          <?php 
            foreach ($kategoriPaymentNonSPP as $key) {
              echo "<th rowspan='2' class='bg-light-blue color-palette'>".$key['nama_kategori']."</th>"; 
            } 
            echo "<th rowspan='2' class='bg-light-blue color-palette'>Total Penerimaan <br>".number_format($totalPenerimaan, 0 , '' , '.' )."</th>";
          ?>
        </tr> 
        <tr>
          <?php foreach ($bulan as $key) {
            echo "<th class='bg-green color-palette' style='vertical-align: middle;'>".$key."</th>";
          } ?>
        </tr>
      </thead>
      <tbody> 
        <?php 
        $x = 1;
        foreach ($payment as $key) { ?>
          <tr>
            <td><?php echo $x++; ?></td>
            <td><?php echo $key['Nama'] ?></td>
            <td><?php echo $key['Kelas'] ?></td>
            <?php 
              foreach ($key['SPP'] as $keySPP) {

                if (!$keySPP['data']) {
                    echo "<td></td>";
                 } else {
                    echo "<td>";
                    foreach ($keySPP['data'] as $keySPP2) {
                      $amountSPP = number_format($keySPP2['Amount'], 0 , '' , '.' );
                      if ($keySPP2['Debt'] and $type == '1') {
                        echo $amountSPP.' (<b>'.$keySPP2['Tahun'].'</b>)';
                      } else if ($keySPP2['Debt'] and ($type == '2' or $type == '3')){
                        echo $amountSPP.' ('.$keySPP2['Created'].') <b>'.$keySPP2['Tahun'].'</b> <br>';
                      } else if($type == '2' or $type == '3'){
                        echo $amountSPP.' ('.$keySPP2['Created'].') <br>';
                      } else {
                        echo $amountSPP.'<br>';
                      }
                    }
                    echo "</td>";
                 }
              }

              foreach ($key['PaymentNonSPP'] as $keyNonSPP) {
                echo "<td>";
                  foreach ($keyNonSPP['Data'] as $keyNonSPPDetail) {
                    echo number_format($keyNonSPPDetail['Amount'], 0 , '' , '.' );
                    echo $here.' ('.$keyNonSPPDetail['Created'].')<br>';
                  } 
                echo "</td>";
              }
              echo "<td>".number_format($key['Total'], 0 , '' , '.' )."</td>"
            ?>
          </tr>
        <?php } ?>
      </tbody>
	  </table>
    <?php if ($paymentLainnya) { ?>
    <br><br>
    <h2><b>Penerimaan Lainnya</b></h2>
    <table class="table table-bordered">
      <thead>
        <tr>
         <th class="bg-light-blue color-palette">No</th>
         <th class="bg-light-blue color-palette">Tanggal</th>
         <th class="bg-light-blue color-palette">Keterangan</th>
         <th class="bg-light-blue color-palette">Jumlah</th>
        </tr> 
      </thead>
      <tbody> 
        <?php 
        $x = 1;
        foreach ($paymentLainnya as $key) { ?>
          <tr>
            <td><?php echo $x++; ?></td>
            <td style="white-space: nowrap;"><?php echo date_format(date_create($key['date_created']),"Y-m-d"); ?></td>
            <td style="white-space: nowrap;"><?php echo $key['keterangan'] ?></td>
            <td><?php echo number_format($key['amount'], 0 , '' , '.' ); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php } ?>
	 </div>
</body>
</html>