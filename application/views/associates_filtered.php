<!DOCTYPE html>
<?php $this->load->view('components/L1'); ?>
<style>
.ckBox{zoom: 0.5;}
</style>
<script>

function getAndDisplayChefs(){
	filter_name="<?php echo $filter_name ?>"
	filter_value="<?php echo $filter_value ?>"
	
	$('#head_l').html(filter_value);
	
	url="<?php echo $baseUrl.'index.php/restApi/filter_associates'; ?>"
	chef_edit_url="<?php echo $baseUrl.'index.php/welcome/chef_edit/'; ?>"
	$.post(url,{filter_value:filter_value,filter_name:filter_name},function(data){
		if(data.length==0){
			alert('Invalid filter. Nothing could be found.');
		}
		else{
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
				
				str+="<td><a href='"+chef_edit_url+data.id+"'><i class='glyphicon glyphicon-pencil primary' style='color: #2a9bff;cursor: pointer;' title=' Edit' ></i></a>  &nbsp;&nbsp;&nbsp; ";
				str+="<i class='glyphicon glyphicon-trash' onclick='delete_chef("+data.id+")' style='color: #ff2a93;cursor: pointer;' title='Delete'></i> &nbsp;&nbsp;&nbsp;"; 				 								
				str+="<i class='glyphicon glyphicon-eye-open' style='color: #ff932a;cursor: pointer;' title='View Profile'></i> </td>"; 		
				str+='</tr>';		
			})
			
			
			$('#myTable tbody').html(str);
			$('#spiner').hide(0);
			$('#myTable').DataTable();	
			
		}
	})
}

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
$(document).ready(function(){
	getAndDisplayChefs();
	
});

</script>

	<div class="page-content">
	 
		<div class="row">
			<div class="col-xs-12" id="ttbale">
				<h5 id="head_l"></h5>
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