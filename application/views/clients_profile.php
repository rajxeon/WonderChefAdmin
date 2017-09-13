<!DOCTYPE html>
<?php $this->load->view('components/L1'); ?>
<style>
.meet{    padding-left: 25px;margin-bottom: 20px;    border-left: 2px dotted #9500ff;    margin-left: 23px;}
.round{display: inline-block; padding: 6px 12px; background: white; border-radius: 100%; border: 1px solid #4a4a4a; position: absolute; left: 20px; color: #fff; margin-top: -9px; font-weight: bold;}
.round:hover{color:#000; background: #fff !important; cursor: pointer;}
</style>
<script>

function getRandomColor() {
  var letters = '56789ABCD';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 9)];
  }
  return color;
}

	function getClientDetails(){
		client_id="<?php echo $client_id; ?>";

		url="<?php echo $baseUrl.'index.php/restApi/getClientDetails'; ?>"
		$.post(url,{client_id:client_id},function(data){
			if(data.length==0){
				$('#c_holder').html('No client found');
				return;
			}

			meeting_data=data[1];
			//console.log(meeting_data);

			$('#client_name').html('<i class="glyphicon glyphicon-user"> </i> '+data[0].name);
			$('#client_phone').html('<i class="glyphicon glyphicon-phone"> </i> '+data[0].phone);
			$('#client_address').html('<i class="glyphicon glyphicon-map-marker"> </i> '+data[0].address);
			$('#followups').html(''+data[0].follow_ups);

			meeting_html='';
			$(meeting_data).each(function(index,val){
				meeting_html+='<div class="meet" style="border-color:'+getRandomColor()+'">';
				meeting_html+='<div class="round" data-meeting-id="'+val.id+'" title="Change Summery" style="background-color:'+getRandomColor()+'">'+(index+1)+'</div>';
				meeting_html+='<div><b>Title:</b> '+val.subject+'</div>';
				meeting_html+='<div><b>Details:</b> '+val.details+'</div>';
				meeting_html+='<div><b>Time:</b> '+val.datetime+'</div>';
				meeting_html+='<div><b>Summary:</b> '+val.summary+'</div>';
				meeting_html+='</div>';
			})
			$('#meetings').html(meeting_html);
		})

	}

	$(document).on('click','#summary_submit',function(){
		summary=$('#summary_ta').val();
		id=$(this).attr('data-meeting-id');
		url="<?php echo $baseUrl.'index.php/restApi/editMeetingSummary'; ?>"

		$.post(url,{id:id,summary:summary},function(data){
			if(data=="true"){
				$.notify( "Updated successfully", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
				$('#summary_modal').modal('hide');
				getClientDetails();
			}
			else{
				$.notify( "Error in updating", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
			}
		});


	});

	$(document).on('click','.round',function(){
		id=$(this).attr('data-meeting-id');
		$('#summary_submit').attr('data-meeting-id',id);
		url="<?php echo $baseUrl.'index.php/restApi/getMeetingSummary'; ?>"
		$('#summary_modal').modal('show');
		$.post(url,{id:id},function(data){
			summary=data.summary;
			$('#summary_ta').val(summary);

		});


	})

	$(document).ready(function(){
		getClientDetails();
	});
</script>
<div class="page-content">

 <div class="row">
		 <div class="col-xs-6">
			 <h3 id="client_name" style="color: #438eb9;"></h3>
			 <h5 id="client_phone"></h5>
			 <h5 id="client_address"></h5>
			 <h5 id="client_name"></h5>
			 <hr />
			 <h2 class=""><i class="glyphicon glyphicon-briefcase"></i> Follow ups</h2>
			 <div id="followups"></div>
			 <hr />
			 <h2 class=""><i class="glyphicon glyphicon-calendar"></i> Meeting Timeline</h2>
			 <br />
			 <div id="meetings"></div>
			 <div style="margin-bottom: 40px;"></div>
		 <div>
		 <div class="col-xs-6"></div>

	 </div>
 </div>
</div>




<div id="summary_modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Meeting Summary</h4>
      </div>
      <div class="modal-body">
        <textarea id="summary_ta" style="    height: 140px;width: 100%;"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-sm" id="summary_submit" data-meeting-id="">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>








<?php $this->load->view('components/L2'); ?>
