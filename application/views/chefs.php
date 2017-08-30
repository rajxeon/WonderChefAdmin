<!DOCTYPE html>
<?php $this->load->view('components/L1'); ?>

<link rel="shortcut icon" type="image/png" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<style>
.ckBox{zoom: 0.5;}
</style>
<script>
 
function delete_chef(id){
		
		if(!confirm("Are you sure?")){
			return;
		}
		
		url="<?php echo $baseUrl.'index.php/restApi/deletechefs'; ?>"
		$.post(url,{id:id},function(data){
			console.log(data);
			if(data){
				$.notify( "Chef deleted successfully", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
				getAndDisplayChefs();
			}
			else{
				$.notify( "Error in deleting chefs", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
			}
		})
		
	} 
	
function getAndDisplayChefs(){
			
		//Get the chefs list
		url="<?php echo $baseUrl.'index.php/restApi/getchefs'; ?>"
		 
		 
		$.get(url,{},function(data){
			
			str='';
			$(data).each(function(index,data){
				 
				str+='<tr>';
				str+="<td>"+data.id+"</td>";
				str+="<td>"+data.name+"</td>";
				str+="<td>"+data.phone+"</td>";
				str+="<td>"+data.address+"</td>";
				str+="<td>"+data.rating+"</td>";
				str+="<td>"+data.experience+"</td>"; 
				if(data.active=="1"){
					str+='<td> <input type="checkbox" checked class="form-control ckBox" value="'+data.id+'"></td>'; 	
				}
				else{
					str+='<td> <input type="checkbox" class="form-control ckBox" value="'+data.id+'"></td>'; 
				}
				
				str+="<td><i class='glyphicon glyphicon-pencil primary' style='color: #2a9bff;cursor: pointer;' title='Inline Edit' ></i>  &nbsp;&nbsp;&nbsp; ";
				str+="<i class='glyphicon glyphicon-trash' onclick='delete_chef("+data.id+")' style='color: #ff2a93;cursor: pointer;' title='Delete'></i> &nbsp;&nbsp;&nbsp;"; 				 								
				str+="<i class='glyphicon glyphicon-eye-open' style='color: #ff932a;cursor: pointer;' title='View Profile'></i> </td>"; 		
				str+='</tr>';		
			})
			
			
			$('#myTable tbody').html(str);
			$('#spiner').hide(0);
			$('#myTable').DataTable();	
			 
			
		})
		
	}

$(document).ready(function(){
	
	$(document).on('change','.ckBox',function(elem){
		id=$(this).val();
		
		active=$(this).is(":checked")
		
		url="<?php echo $baseUrl.'index.php/restApi/toggleChefsActive'; ?>"
		
		$.post(url,{id:id,active:active},function(data){
			if(data=="true"){
				$.notify( "Chef edited successfully", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
				//getAndDisplayChefs();
			}
			else{
				$.notify( "Error in editing chef", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
			}
		})
		
	});
	
	$(document).on('submit','#new_chef_form',function(e){
		e.preventDefault();
		chef_name=$('#chef_name').val();
		chef_phone=$('#chef_phone').val();
		chef_address=$('#chef_address').val();
		chef_experience=$('#chef_experience').val();
		
		url="<?php echo $baseUrl.'index.php/restApi/addchefs'; ?>"
		
		$.post(url,{chef_name:chef_name,chef_phone:chef_phone,chef_address:chef_address,chef_experience:chef_experience},function(data){			
			
			if(data){
				//Close the modal
				$('.close_modal').click();
				$.notify( "Chef added successfully", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
				getAndDisplayChefs();
			}
			
		});
		
	})
	
	 
	
	$(document).ready(function(){	
		 getAndDisplayChefs();	 
		
	});
	
	
	
})
				
</script>
	<div class="page-content">
	 
	 <div class="row">
	 
	 <button class="btn btn-success btn-sm btn-flat pull-right" data-toggle="modal" data-target="#myModal"> + Add</button>
	 
			<div class="col-xs-12">
				<table id="myTable" class="table table-stripped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Phone</th>
							<th>Address</th>
							<th>Rating</th>
							<th>Experience</th>						
							<th>Visibility</th>						
							<th>Ops</th>						
						</tr>
					
					</thead>
					<tbody>
						<img id="spiner" src="<?php echo base_url("assets/images/ajaxSpiner.gif") ?>" style="height: 50px;  display: block;  margin: 0 auto;">
					</tbody>
				</table>
				
				
				
			</div> 
		</div> 
	</div> 
				
<?php $this->load->view('components/L2'); ?>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-cutlery"></i> Add Chefs</h4>
      </div>
	  <form  id="new_chef_form">
      <div class="modal-body">
        
		
		
		  <div class="form-group">
			<label >Chef Name</label>
			<input type="text" class="form-control" id="chef_name" placeholder="Name" required>
		  </div>
		  
		  <div class="form-group">
			<label >Phone</label>
			<input type="number" class="form-control" id="chef_phone" placeholder="Phone" required>
		  </div>
		  
		  <div class="form-group">
			<label >Address</label>
			<input type="text" class="form-control" id="chef_address" placeholder="Address" required>
		  </div>
		
	      <div class="form-group">
			<label >Experience</label>
			<input type="number" class="form-control" id="chef_experience" placeholder="Experience" required>
		  </div>
			 
		
		
		
		
      </div>
      <div class="modal-footer">
        <button type="button"  class="btn btn-default btn-sm close_modal" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm" >Save changes</button>
      </div>
	  
	  </form>
    </div>
  </div>
</div>