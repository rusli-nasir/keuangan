<nav class="navbar navbar-inverse" role="navigation">

	<div class="container">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <i class="icon-cog"></i>
    </button>
    <a class="navbar-brand" href="./index.html">Login As : <?php echo ucwords(str_replace('_', ' ', $this->session->privilege)); ?></a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav navbar-right">

		<li class="dropdown">
						
			<a href="javscript:;" class="dropdown-toggle" data-toggle="dropdown">
				<i class="icon-user"></i> 
				<?php echo $this->session->username; ?>
				<b class="caret"></b>
			</a>
			
			<ul class="dropdown-menu">
				<li><a href="javascript:;">My Profile</a></li>
				<li class="divider"></li>
				<li><a href="<?php echo base_url('login/logout'); ?>">Logout</a></li>
			</ul>
			
		</li>
    </ul>
    
  </div><!-- /.navbar-collapse -->
</div> <!-- /.container -->
</nav>