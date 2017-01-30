<div class="footer">

	<div class="container">

		<div class="row">

			<div id="footer-copyright" class="col-md-6">
				&copy; 16-17
			</div> <!-- /span6 -->

			<div id="footer-terms" class="col-md-6">
				<!-- Theme by <a href="http://jumpstartui.com" target="_blank">Jumpstart UI</a> -->
			</div> <!-- /.span6 -->

		</div> <!-- /row -->

	</div> <!-- /container -->

</div> <!-- /footer -->

	<!-- Le javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="<?php echo theme_js; ?>libs/jquery-1.9.1.min.js"></script>
	<script language="javascript">
    $(document).ready(function(){
    	$('#kelas').change(function(){
    		$.post("<?php echo base_url();?>siswa/get_siswa/"+$('#kelas').val(),{},function(obj){
	            $('#siswa').html(obj);
	        });
	    });
	    $('#siswa').change(function(){
    		$.post("<?php echo base_url();?>keuangan/get_payment_category/"+$('#siswa').val(),{},function(obj){
	            $('#payment').html(obj);
	        });
	    });
	    $('#payment').change(function(){
    		$.post("<?php echo base_url();?>keuangan/get_payment_annualy/"+$('#payment').val(),{},function(obj){
	            $('#annualy').html(obj);
	        });
	    });
    });
	</script>
	<script src="<?php echo theme_js; ?>libs/jquery-ui-1.10.0.custom.min.js"></script>
	<script src="<?php echo theme_js; ?>libs/bootstrap.min.js"></script>

	<script src="<?php echo theme_js; ?>plugins/flot/jquery.flot.js"></script>
	<script src="<?php echo theme_js; ?>plugins/flot/jquery.flot.pie.js"></script>
	<script src="<?php echo theme_js; ?>plugins/flot/jquery.flot.resize.js"></script>

	<script src="<?php echo theme_js; ?>Application.js"></script>

	<script src="<?php echo theme_js; ?>charts/area.js"></script>
	<script src="<?php echo theme_js; ?>charts/donut.js"></script>
	<script type="text/javascript">
	  function goBack() {
	 	window.history.back();
	  }
	</script>
	<script type="text/javascript">
		$(".date-picker-yayasan").datepicker({dateFormat: 'yy-mm-dd'});
		$(".date-picker").datepicker({dateFormat:'yy-mm-dd', minDate: new Date(2016,6,1), maxDate: new Date(2017,5,30) });
		$(".date-pickerindo").datepicker({dateFormat: 'dd-mm-yy'});
	</script>

  </body>
</html>
