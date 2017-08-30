<!DOCTYPE html>
<?php $this->load->view('components/L1'); ?>

<script>

function fetchTree(){
	url="<?php echo $baseUrl.'index.php/restApi/getPostTree'; ?>"
	
	$.post(url,{},function(data){
		html=(data[0].g);
		$('.tree').html(html);
	})
	 
	
}

function submit(){
		 
		position_name=$('#position_name').val();
		parent_id=$('#parent_id').val();
		
		 
		if(position_name.length==0){
			$.notify( "Name can not be empty", {position:"bottom left", elementPosition: 'bottom left',className: 'warning'} );
			return;
		}
		 
		
		url="<?php echo $baseUrl.'index.php/restApi/addposition'; ?>"
		
		$.post(url,{position_name:position_name,parent_id:parent_id},function(data){			
			console.log(data);
			if(data){
				//Close the modal
				$('.close_modal').click();
				$.notify( "Position added successfully", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
				fetchTree();
			}
			else{
				$.notify( "Something went wrong", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
			}
			
		});
		
	}
	

$(document).ready(function(){
	fetchTree();
	
		
	
	
});

</script>
	<div class="page-content">
	 
		<div class="row">
			<div class="col-xs-12">
				<!-- PAGE CONTENT BEGINS -->
				
				<div class="col-lg-6">
				
					<form>
						
						 <div class="form-group">
							<label >Position Name</label>
							<input type="text" class="form-control" id="position_name" placeholder="Position Name" required>
						  </div>
						  <div class="form-group">
							<label >Parent</label>
							<select  class="form-control" id="parent_id">
								<option value="0" selected>---None---</option>
								<?php 
								$str='';
								foreach($jobposts as $post){
									$str.='<option value="'.$post->id.'">'.$post->name.'</option>';								
								} 
								echo $str;
								?>
							</select>
							
						  </div>
						  
						
					</form>
					 <button onclick="submit()" id="add_position_form" class="btn btn-success btn-sm btn-flat pull-right"> + Add New Position</button>
				</div>
				<div class="col-lg-6">
					<h3>Positions Hierarchy</h3>
					<div class="tree"></tree>
				</div>
				
				<!-- PAGE CONTENT ENDS -->
			</div> 
		</div> 
	</div> 
				
<?php $this->load->view('components/L2'); ?>