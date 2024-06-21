<?php 
$this->load->view("admin/header"); 
$this->load->view("admin/sidebar");
$token= $this->efiling_model->getToken();
?>

<?= form_fieldset('Add MA (Miscellaneous Application)').
'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'.
'<div class="date-div text-success">Date & Time : &nbsp;<small>'.date('D M d, Y H:i:s').'&nbsp;IST</small></div>';
?>
<div class="container">
    <div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
              <div class="container">
              	<span> 
                  	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                      Add MA
                    </button>
                </span>
              	 <table id="example" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>MA Nature</th>
                                <th>MA Fee</th>
                                <th>Status.</th>
                                <th>User.</th>
                                <th>Date of Entry</th>
                                <th>Action</th>        
                            </tr>
                        </thead>
                        <tbody>
                         <?php
                         if(is_array(@$ma)){
                          $i=1;
                          foreach($ma as $val){  
                              $status=$val->status;
                              $naturename=$val->nature_name;
                              $ma_fee=$val->ma_fee;
                              $userid=$val->userid;
                              $entrydate=date('d/m/Y',strtotime($val->entry_date));
                              $id =$val->id;
                              ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td>
                             
                                <label title="<?php echo $naturename; ?>"><?php echo substr($naturename,0,50); ?></label>

                                </td>
                                 <td><?php echo $ma_fee; ?></td>
                                <td><?php echo $status; ?></td>
                                <td><?php echo $userid; ?></td>
                                <td><?php echo $entrydate; ?></td>
                                <td>
                                <a href="javascript:void(0)" onclick="deletema('<?php echo $id; ?>');">Delete</a>&nbsp;&nbsp;
                                <a href="javascript:void(0)"  data-toggle="modal" data-target="#exampleModal" onclick="editma('<?php echo $id; ?>');">Edit</a>
                                </td>                      						
                            </tr>   
                         <?php $i++; } 
                         } ?>   
                        </tbody>
                    </table>
              </div>
        </div>
	</div>
</div>









<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add MA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  <input type="hidden" id="idval" name="idval" value="">
		  <input type="hidden" id="action" name="action" value="add">

          <div class="form-group">
            <label for="exampleFormControlInput1">MA Name</label>
            <input type="text" class="form-control" id="ma_name" name="ma_name" placeholder="Please enter ma name" required >
          </div>
          
          <div class="form-group">
            <label for="exampleFormControlInput1">MA Fee</label>
            <input type="text" class="form-control" id="ma_fee" name="ma_fee" placeholder="Please enter ma fee" required >
          </div>

          <div class="form-group">
            <label for="exampleFormControlSelect1">Status</label>
            <select class="form-control" id="status" name="status" required >
              <option value="1">Active</option>
              <option value="0">Un-Active</option>
            </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary"  onclick="submitregulator();">Save changes</button>
      </div>
    </div>
  </div>
</div>


</div>
<script>
    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        } );
    } );
    
    
   function submitregulator(){
    var ma_name = document.getElementById("ma_name").value;
    var status = document.getElementById("status").value;
    var token= '<?php echo $token; ?>';
    var ma_fee= document.getElementById("ma_fee").value;
    var action= document.getElementById("action").value;
    var idval= document.getElementById("idval").value;
    var dataa={};
    dataa['status']=status,
    dataa['ma_name']=ma_name,
    dataa['ma_fee']=ma_fee,
    dataa['token']=token,
    dataa['action']=action,
    dataa['idval']=idval,
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'addmanature',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		$.alert({
					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Congrates</b>',
					content: '<p class="text-success">Check list added successfully.</p>',
					animationSpeed: 2000
				});
				setTimeout(function(){location.reload()}, 3000);
			}else if(resp.error != '0') {
				$.alert(resp.massage);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
		}
	 });
   }
   
   
   
   function deletema(e){
        var token= '<?php echo $token; ?>';
        var x = confirm("Are you sure you want to delete?");
   	    var dataa={};
        dataa['id']=e,
        dataa['token']=token
    	if (x) {
           	$.ajax({
        	    dataType: 'json',
                type: "POST",
                url: base_url+'deletema',
                data: dataa,
                cache: false,
        		beforeSend: function(){
        			//$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
        		},
                success: function (resp) {
                	if(resp.data=='success') {
                		$.alert({
        					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Congrates</b>',
        					content: '<p class="text-success">Check list deleted successfully.</p>',
        					animationSpeed: 2000
        				});
        				setTimeout(function(){location.reload()}, 3000);
        			}else if(resp.error != '0') {
        				$.alert(resp.massage);
        			}
                },
                error: function(){
        			$.alert("Surver busy,try later.");
        		},
        		complete: function(){
        		}
             });
         }
   }
   
   function editma(val){
       var token= '<?php echo $token; ?>';
        var x = confirm("Are you sure you want to modify record ?");
   	    var dataa={};
        dataa['id']=val,
        dataa['token']=token
    	if (x) {
           	$.ajax({
        	    dataType: 'json',
                type: "POST",
                url: base_url+'editma',
                data: dataa,
                cache: false,
        		beforeSend: function(){
        			//$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
        		},
                success: function (resp) {
                	$('#ma_name').val(resp.naturename);
                	$("#status option[value='"+resp.status+"']").attr("selected","selected");
                	$('#idval').val(resp.idval);
                	$('#ma_fee').val(resp.fee);
                	$('#action').val('edit');
                },
                error: function(){
        			$.alert("Surver busy,try later.");
        		},
        		complete: function(){
        		}
             });
         }
   }
   
   
</script>
 <?= form_fieldset_close(); ?>
  <?php $this->load->view("admin/footer"); ?>