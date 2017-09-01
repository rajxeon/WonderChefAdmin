<!DOCTYPE html>
<?php $this->load->view('components/L1'); ?> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
label{ font-weight:bold}
</style>
<script>

$.fn.enterKey = function (fnc) {
    return this.each(function () {
        $(this).keypress(function (ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '13') {
                fnc.call(this, ev);
            }
        })
    })
}

function get_disciplines(id){
	url="<?php echo $baseUrl.'index.php/restApi/getDisciplines'; ?>"
	$.post(url,{id:id},function(data){
		//disciplines_box
		data=data.split("-");
		str='<ol style="float: right;">';
		$(data).each(function(index,elem){
			if(elem.length==0) return; 
			str+='<li>'+elem+'</li>';
		});
		str+='</ol>';
		$('#disciplines_box').html(str);
	});
	
}


 
$(document).ready(function(){
	
	//Get the disciplines for this chef
	
	get_disciplines("<?php echo $chef->id; ?>");
	
	
	$("#disciplines_dd").enterKey(function (e) {
		e.preventDefault();
		discipline=$("#disciplines_dd").val();
		id="<?php echo $chef->id; ?>";
		url="<?php echo $baseUrl.'index.php/restApi/addDisciplines'; ?>"
		
		 
		$.post(url,{id:id,discipline:discipline},function(data){
			if(data=='true'){
				$.notify( "Successfully added", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
				 get_disciplines(id);
			}	
			else{
				$.notify( "Error encountered while adding", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
			}
		})
		
	})
	
	
	var availableTags = [
	<?php 
		$str='';
		foreach($disciplines as $discipline){
			$str.='"'.$discipline->name.'",';
		}
		echo $str;
	?>
	  
	];

	$( function() {

	$( "#disciplines_dd" ).autocomplete({
	  source: availableTags
	});
	} );

	
	$(document).on('submit','#edit_from',function(e){
		e.preventDefault();
		
		name=$('#assoc_name').val();
		phone=$('#assoc_phone').val();
		address=$('#assoc_address').val();
		position=$('#assoc_position').val();
		id=$('#assoc_id').val();
		
		if($('#assoc_visibility').is(':checked')){
			active=1;
		}
		else{
			active=2;
		}
		
		url="<?php echo $baseUrl.'index.php/restApi/updateChef'; ?>"
		$.post(url,{id:id,name:name,phone:phone,address:address,active:active,position:position},function(data){
			 
			if(data=='true'){
				$.notify( "Successfully updated", {position:"bottom left", elementPosition: 'bottom left',className: 'success'} );
				  
			}	
			else{
				$.notify( "Error encountered while performing a update operation", {position:"bottom left", elementPosition: 'bottom left',className: 'danger'} );
			}
		});
	})
	
});

</script>
	<div class="page-content">
	 <?php // var_dump($positions); ?>
		<div class="row">
			<form id="edit_from">
			<input type="hidden" id="assoc_id" value="<?php echo $chef->id; ?>">
			<div class="col-xs-6">
				<div class="form-group">
					<label>Associate Name</label>
					<input type="text" class="form-control" id="assoc_name"  placeholder="Associate  Name" value="<?php echo $chef->name; ?>" required>	 
				</div>
				
				<div class="form-group">
					<label>Phone</label>
					<input type="number" class="form-control" id="assoc_phone"  placeholder="Phone" value="<?php echo $chef->phone; ?>" required>
				</div>
				
				<div class="form-group">
					<label>Address</label>
					<input type="text" class="form-control" id="assoc_address"  placeholder="Address" value="<?php echo $chef->address; ?>" required>
				</div>
				
				<div class="form-group">
					<label>Position</label>
					<select class="form-control" id="assoc_position">
						<option value="0">None</option>
						<?php 
							foreach($positions as $position){
								if($chef->position==$position->id){
									echo '<option value="'.$position->id.'" selected>'.$position->name.'</option>';
								}
								else{
									echo '<option value="'.$position->id.'">'.$position->name.'</option>';	
								}
								
							}
						?>
					</select>
					
				</div>
				
				
				<button type="submit" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-saved"></i> Submit</button>
			</div>
			<div class="col-xs-6">
			
				<div class="col-lg-6">
					<label>Member since</label>
					<p><?php echo $chef->addedOn ?></p>
					
					<label>Experience</label>
					<p><?php 
						$experience=(int)$chef->experience;
						$experience_days=$experience*365;
						
						$diff = abs(strtotime(date("Y-m-d")) - strtotime($chef->addedOn));
						 
						$years = floor($diff / (365*60*60*24));
						
						$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
						$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
										
						$total_experience_days=$experience_days+$days;
						
						$years = floor($total_experience_days / (365));
						$months = floor(($total_experience_days - $years*365 ) / (12));
						
						echo $years." years ".$months." months";
					//echo $chef->addedOn 
					
					?></p>
					<label>Rating</label>
					<p><?php echo $chef->rating ?><br><small>Based on <?php echo $chef->rating_count ?> reviews</small></p>
					
				</div>
				<div class="col-lg-6" style="text-align: right;">
					<label>Visibility</label>
					<input id="assoc_visibility" type="checkbox" <?php if($chef->active=="1") echo "checked"; ?> class="ace ace-switch ace-switch-6">
					<span class="lbl middle"></span>	
					<hr>
					<label>Disciplines</label>
					<div id="disciplines_box" style="clear:both">
						<img src="<?php echo base_url('assets/images/ajaxSpiner.gif'); ?>" style="width:20px; margin-bottom:10px">						 
					</div>
					<br  style="clear:both"> 
				 
					
					
					<div class="ui-widget">
					  <label for="tags">Add: </label>
					  <input id="disciplines_dd">
					</div>
				</div>				
				
			</div>
			
			</form>
		</div> 
	</div> 
				
<?php $this->load->view('components/L2'); ?>