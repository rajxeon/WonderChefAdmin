<!DOCTYPE html>
<?php $this->load->view('components/L1'); ?>
<script>
	function getClientDetails(){
		client_id="<?php echo $client_id; ?>";

		url="<?php echo $baseUrl.'index.php/restApi/getClientDetails'; ?>"
		$.post(url,{client_id:client_id},function(data){
			if(data.length==0){
				$('#c_holder').html('No client found');
			}
			else{

			}
			console.log(data);
		})

	}


	$(document).ready(function(){
		getClientDetails();
	});
</script>
	<div class="page-content">

		<div class="row">
			<div class="col-xs-12" id="c_holder">
				<!-- PAGE CONTENT BEGINS -->

				<!-- PAGE CONTENT ENDS -->
			</div>
		</div>
	</div>

<?php $this->load->view('components/L2'); ?>
