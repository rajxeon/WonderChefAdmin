<!DOCTYPE html>
<?php $this->load->view('components/L1'); ?>

<script>

$(document).ready(function(){
	
	url="<?php echo $baseUrl.'index.php/restApi/getPostTree'; ?>"
	
	$.post(url,{},function(data){
		html=(data[0].g);
		$('.tree').html(html);
	})
	
});

</script>
	<div class="page-content">
	 
		<div class="row">
			<div class="col-xs-12">
				<!-- PAGE CONTENT BEGINS -->
				<div class="tree"></tree>
				<!-- PAGE CONTENT ENDS -->
			</div> 
		</div> 
	</div> 
				
<?php $this->load->view('components/L2'); ?>