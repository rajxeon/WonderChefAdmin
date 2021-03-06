<!DOCTYPE html>
<?php $this->load->view('components/L1'); ?>
<link rel="shortcut icon" type="image/png" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>


<script src="<?php echo base_url('assets/js/jquery.datetimepicker.full.min.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery.datetimepicker.css'); ?>"/ >
<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>

<script>

function delete_client(id){

		if(!confirm("Are you sure?")){
			return;
		}

		url="<?php echo $baseUrl.'index.php/restApi/deleteclients'; ?>"
		$.post(url,{id:id},function(data){
			if(data){
				$.notify( "Client deleted successfully", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
				getAllClients();
			}
			else{
				$.notify( "Client in deleting chefs", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
			}
		})

	}

function show_follow_ups(id){

	$('#myModalFollowUps').modal('show');
	url="<?php echo $baseUrl.'index.php/restApi/getFollo_ups'; ?>"



	$.post(url,{id:id},function(data){
		client_info=data[0][0];
		meeting_info=data[1];
    tinyMCE.activeEditor.setContent('');
    tinymce.activeEditor.execCommand('mceInsertContent', true, client_info.follow_ups);
		$('#client_id').val(client_info.id);
		if(meeting_info.length==0){
			//No meetings
			$('#meeting_info').html('No meeting');
		}
		else{
			$('#meeting_info').html('Next meeting : <b>'+meeting_info[0].datetime+'</b>');
		}
	});


}

function getAllClients(){
	url="<?php echo $baseUrl.'index.php/restApi/getAllClients'; ?>"
	$.post(url,{},function(data){
			str='';
			$(data).each(function(index,data){

				str+='<tr>';
				str+="<td>"+data.id+"</td>";
				str+="<td>"+data.name+"</td>";
				str+="<td>"+data.phone+"</td>";
				str+="<td>"+data.address+"</td>";
				str+="<td style=\"cursor:pointer; color:#ff7d1a \" onclick='show_follow_ups("+data.id+")'>"+data.follow_ups.substr(0, 13)+"...</td>";
				str+="<td>"+data.last_updated+"</td>";



				str+="<td><a href='"+""+data.id+"'><i class='glyphicon glyphicon-pencil primary' style='color: #2a9bff;cursor: pointer;' title=' Edit' ></i></a>  &nbsp;&nbsp;&nbsp; ";
				str+="<i class='glyphicon glyphicon-trash' onclick='delete_client("+data.id+")' style='color: #ff2a93;cursor: pointer;' title='Delete'></i> &nbsp;&nbsp;&nbsp;";
        str+="<a href='";
				str+="<?php echo base_url('index.php/welcome/clients_profile/'); ?>"+data.id;
        str+="'><i class='glyphicon glyphicon-eye-open' style='color: #ff932a;cursor: pointer;' title='View Profile'></i></a> </td>";

				str+='</tr>';
			})


			$('#myTable tbody').html(str);
			$('#spiner').hide(0);
			$('#myTable').DataTable();


		})
}

$(document).on('submit','#edit_followups_form',function(e){
	e.preventDefault();
	followups=$('#follow_ups_body').val();
	id=$('#client_id').val();

	url="<?php echo $baseUrl.'index.php/restApi/editFollowUps'; ?>"
	$.post(url,{id:id,followups:followups},function(data){
		if(data=='true'){
			$.notify( "Follow ups added successfully", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
			getAllClients();
			$('#myModalFollowUps').modal('hide');
		}
		else{
			$.notify( "Updating of Follow ups failed", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
		}
  })


});

$(document).on('submit','#new_client_form',function(e){
		e.preventDefault();
		chef_name=$('#chef_name').val();
		chef_phone=$('#chef_phone').val();
		chef_address=$('#chef_address').val();


		url="<?php echo $baseUrl.'index.php/restApi/addclient'; ?>"

		$.post(url,{chef_name:chef_name,chef_phone:chef_phone,chef_address:chef_address},function(data){

			if(data){
				//Close the modal
				$('.close_modal').click();
				$.notify( "Client added successfully", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
				getAllClients();
			}

		});

	})

$(document).on('click','#add_new_meeting',function(e){
  e.preventDefault();
  client_id=$('#client_id').val();
  datetime=$('#datetimepicker').val();
  subject=$('#meeting_subject').val();
  details=$('#meeting_body').val();

  if(client_id.length==0 || datetime.length==0 || subject.length==0 || details.length==0){
    $.notify( "Fields can not be empty", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
    return;
  }

  url="<?php echo $baseUrl.'index.php/restApi/add_new_meeting'; ?>"

  $.post(url,{client_id:client_id,datetime:datetime,subject:subject,details:details},function(data){

    if(data=='false'){
      $.notify( "Fields can not be empty", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
      return;
    }
    else if(data=='true'){
      $.notify( "Meeting added successfully", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
      $('#myModalFollowUps').modal('hide');
    }


  })


})

$(document).ready(function(){
	getAllClients();
    $('#datetimepicker').datetimepicker();
     tinymce.init({selector:'textarea'});
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
							<th>Follow Ups</th>
							<th>Last Updated</th>
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



<div class="modal fade" id="myModalFollowUps" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-time"></i> Follow ups</h4>
      </div>
	  <form  id="edit_followups_form">
      <div class="modal-body" >
		  <div class="form-group">

			<textarea class="form-control" id="follow_ups_body" rows="12"></textarea>
		  </div>
		  <input type="hidden" id="client_id" value="0">
		  <div id="meeting_holder" style="height: 50px;">
			<div class="col-lg-2" id="meeting_info"></div>
			<div class="col-lg-10">
        <button type="button" class="btn btn-success btn-xs pull-right" id="add_new_meeting" > + Add New Meeting</button>
        <input id="datetimepicker" type="text" placeholder="Select Time" style="    float: right;padding: 4px;margin: 0 5px;" >
        <input id="meeting_subject" type="text" placeholder="Subject" style="    float: right;padding: 4px;margin: 0 5px;" >
        <input id="meeting_body" type="text" placeholder="Body" style="    float: right;padding: 4px;" >

			</div>
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





<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-cutlery"></i> Add Clients</h4>
      </div>
	  <form  id="new_client_form">
      <div class="modal-body">



		  <div class="form-group">
			<label >Client Name</label>
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




      </div>
      <div class="modal-footer">
        <button type="button"  class="btn btn-default btn-sm close_modal" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm" >Save changes</button>
      </div>

	  </form>
    </div>
  </div>
</div>
<?php $this->load->view('components/L2'); ?>
