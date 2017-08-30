<!DOCTYPE html>
<?php $this->load->view('components/L1'); ?>
	<div class="page-content container">
	 
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-2 col-xs-1 card bg-green">
				<h2 style="float:left"><i class="glyphicon glyphicon-user"></i> Associates</h2>
				<h2 style="float:right"><?php echo $chefCount; ?></h2>
				<br  style="clear:both">
				<br><br>
				<a  href="<?php echo site_url('welcome/chefs'); ?>" style="float:right;color: #fff;">View More...</a>
			</div> 
			<div class="col-lg-3 col-md-3 col-sm-2 col-xs-1 card bg-success">
				
			</div>
		</div> 
	</div> 
				
<?php $this->load->view('components/L2'); ?>