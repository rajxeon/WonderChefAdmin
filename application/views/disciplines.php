<!DOCTYPE html>
<?php $this->load->view('components/L1'); ?>
<link rel="shortcut icon" type="image/png" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script>

function show_filtered(e){
	filter_value=$(e).attr('data-filter_value');
	filter_name=$(e).attr('data-filter_name');
	console.log(filter_value,filter_name);
	window.location="<?php echo $baseUrl;?>"+'index.php/welcome/associates_filtered/'+filter_name+'/'+filter_value;
}

function deleteDiscipline(id){
	if(!confirm("Are you sure?")){return;}	
	url="<?php echo $baseUrl.'index.php/restApi/deleteDisciplines'; ?>"
	
	$.post(url,{id:id},function(data){
		if(data=="true"){
			location.reload();
		}
		else{
			$.notify( "Error encountered while deleting", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
		}
	})
	
}

$(document).ready(function(){
	$('#myTable').DataTable();	
	
	$('#dec_form').on('submit',function(e){
		e.preventDefault();
		name=$('#discipline_name').val();
		description=$('#discipline_description').val();
		
		url="<?php echo $baseUrl.'index.php/restApi/addNewDisciplines'; ?>"
		
		$.post(url,{'name':name,'description':description},function(data){
			if(data=="true"){
				location.reload();
			}
			else{
				$.notify( "Error encountered while inserting", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
			}
		})
		
	})
	
	
})
</script>
	<div class="page-content">
	 
		<div class="row">
			<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#myModal"> + Add</button>
			<div class="col-xs-12">
				<table class="table table-stripped" id="myTable">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Description</th>
							<th>Operations</th>
						</tr>
						<tbody>
							<?php
								$counter=0;
								foreach($disciplines as $discipline){
									
									$counter++;
									$str='<tr>';									
									$str.='<td>'.$counter.'</td>';
									$str.='<td style="cursor:pointer; color:#5aaaff"
									onclick="show_filtered($(this))" data-filter_value="'.$discipline->name.'" data-filter_name="by_discipline">'.$discipline->name.'</a></td>';
									$str.='<td>'.$discipline->description.'</td>';
									$str.='<td><i class="glyphicon glyphicon-trash" title="Delete" onclick="deleteDiscipline('.$discipline->id.')" style="color:#f66; cursor:pointer"></i></td>';
									
									$str.='</tr>';
									echo $str;
								}
								
							 ?>
						</tbody>
					</thead>
				</table>
				 
			</div> 
		</div> 
	</div> 
				
 

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add new Discipline</h4>
      </div>
      <div class="modal-body">
        <form id="dec_form">
		
			<div class="form-group">
				<label>Discipline Name</label>
				<input type="text" class="form-control" id="discipline_name"  placeholder="Discipline  Name" required>	 
			</div>
			
			<div class="form-group">
				<label>Discipline Description</label>
				<input type="text" class="form-control" id="discipline_description"  placeholder="Discipline  Description" required>	 
			</div>
			
			
		
		
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary btn-sm">Save Discipline</button>
		  </div>
	  </form>
    </div>
  </div>
</div>			
	
<?php $this->load->view('components/L2'); ?>