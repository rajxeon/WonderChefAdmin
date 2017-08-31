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

 
	

$(document).ready(function(){
	fetchTree();
	
	
	$(document).on('click','#delete_post_modal',function(event ){
		 
		if(!confirm("All the child nodes will also gets deleted.Are you sure?")){
			return;
		}
		
		id=$('#postSubmit').attr('data-postid');
		
		url="<?php echo $baseUrl.'index.php/restApi/deletePostWithChild'; ?>"
 
		
		$.post(url,{id:id},function(data){
			console.log(data);
			if(data=='true'){
				$.notify( "Successfully deleted", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
				$('#close_modal').click();
				fetchTree();
			}	
			else{
				$.notify( "Error encountered while performing a delete operation", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
			}
		})
		
	});
	
	
	$(document).on('click','.nodes',function(event ){
		event.stopImmediatePropagation();		
		$('#positionsModal').modal('show');
		$('#postSubmit').attr('data-postId',$(this).attr("data-nodeId"));
		
		//Get the data for the node
		url="<?php echo $baseUrl.'index.php/restApi/getPositionDetails'; ?>"
		
		$.post(url,{id:$(this).attr("data-nodeId")},function(data){			 
			$('#position_name_modal').val(data.name);
			$('#position_description_modal').val(data.description);
			
			dropHtml='<select class="form-control" id="parent_id_modal">'+$('#parent_id').html()+"</select>";
			dropHtml=dropHtml.replace('selected','');
			dropHtml=dropHtml.replace('value="'+data.parent+'"','value="'+data.parent+'" selected');		 
			
			$('#parentDropHolder').html(dropHtml);
		})
		
	})
	
	$(document).on('submit','#add_position_form',function(e){
		e.preventDefault();
		position_name=$('#position_name').val();
		parent_id=$('#parent_id').val();
		
		 
		if(position_name.length==0){
			$.notify( "Name can not be empty", {position:"bottom left", elementPosition: 'bottom left',className: 'warning'} );
			return;
		}
		 
		
		url="<?php echo $baseUrl.'index.php/restApi/addposition'; ?>"
		
		$.post(url,{position_name:position_name,parent_id:parent_id},function(data){			
			if(data){
				//Close the modal
				 
				$.notify( "Position added successfully", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
				fetchTree();
			}
			else{
				$.notify( "Something went wrong", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
			}
			
		});
	});
	
	$(document).on('submit','#postion_details_form',function(e){
		e.preventDefault();
		name=$('#position_name_modal').val();
		description=$('#position_description_modal').val();
		parent=$('#parent_id_modal').val();
		
		id=$('#postSubmit').attr('data-postid');
		 
		url="<?php echo $baseUrl.'index.php/restApi/updatePositionDetails'; ?>"
		
		$.post(url,{id:id,name:name,description:description,parent:parent},function(data){
			  
			 
			if(data=='true'){
				$.notify( "Successfully updates", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
				fetchTree();
				$('#close_modal').click();	
				
			}
			else{
				$.notify( "Something went wrong", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
			}
			
		
		})
		
		
	});
	
});

</script>
	<div class="page-content">
	 
		<div class="row">
			<div class="col-xs-12">
				<!-- PAGE CONTENT BEGINS -->
				
				<div class="col-lg-6">
				
					<form id="add_position_form">
						
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
						  
						<button type="submit"  class="btn btn-success btn-sm btn-flat pull-right"> + Add New Position</button>
					</form>
					 
				</div>
				<div class="col-lg-6">
					<h3>Positions Hierarchy</h3>
					<div class="tree"></tree>
				</div>
				
				<!-- PAGE CONTENT ENDS -->
			</div> 
		</div> 
	</div> 

	<!-- Modal -->
<div class="modal fade" id="positionsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-briefcase"></i> Position Details</h4>
      </div>
	  <form  id="postion_details_form">
      <div class="modal-body">
        
		
		  <div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label >Position Name</label>
					<input type="text" class="form-control" id="position_name_modal" placeholder="Name" required>
				  </div>
			</div>
			<div class="col-lg-6">
				  <div class="form-group">
					<label>Parent</label>
					<div id="parentDropHolder"></div>
				  </div>
			</div>
		  </div>
		  
		  
		   <div class="form-group">
			<label>Description Name</label>
			<textarea class="form-control" id="position_description_modal"></textarea>			
		  </div>
		  
		   <span><a href="#">78 associates</a> under this position</span>
		
      </div>
      <div class="modal-footer">
        <button type="button" id="delete_post_modal"  class="btn btn-danger btn-sm ">
		<i class="glyphicon glyphicon-trash"></i> 		 
		Delete</button>
        <button type="submit" id="postSubmit"   class="btn btn-success btn-sm" ><i class="glyphicon glyphicon-floppy-save"></i>  Save changes</button>
		<button type="button" id="close_modal"  class="btn btn-primary btn-sm close_modal" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-circle"></i>   Close</button>
      </div>
	  
	  </form>
    </div>
  </div>
</div>
	
	
<?php $this->load->view('components/L2'); ?>