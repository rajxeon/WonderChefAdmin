<!DOCTYPE html>
<?php $this->load->view('components/L1'); ?>
<link rel="shortcut icon" type="image/png" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function(){
	$('#myTable').DataTable();	
	
})
</script>
	<div class="page-content">
	 
		<div class="row">
		
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
									$str.='<td>'.$discipline->name.'</td>';
									$str.='<td>'.$discipline->description.'</td>';
									$str.='<td><i class="glyphicon glyphicon-trash" title="Delete" style="color:#f66; cursor:pointer"></i></td>';
									
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
				
<?php $this->load->view('components/L2'); ?>