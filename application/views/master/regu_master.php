<?php 
$this->load->view("admin/header"); 
$this->load->view("admin/sidebar");
$token= $this->efiling_model->getToken();
?>

<?= form_fieldset('Add Regulator (Master Regulator)').
'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'.
'<div class="date-div text-success">Date & Time : &nbsp;<small>'.date('D M d, Y H:i:s').'&nbsp;IST</small></div>';
?>
<div class="container">
    <div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
              <div class="container">
              	<span> 
                  	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                      Add Regulator
                    </button>
                </span>
              	 <table id="example" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Order Passing Authority</th>
                                <th>Regulator Type</th>
                                <th>Fee</th>
                                <th>Status.</th>
                                <th>User.</th>
                                <th>Date of Entry</th>
                                <th>Action</th>        
                            </tr>
                        </thead>
                        <tbody>
                         <?php
                         if(is_array(@$checklist)){
                          $i=1;
                          foreach($checklist as $val){  
                              $status=$val->status;
                              $fee=$val->fee;
                              $id=$val->order_pass_auth_id;
                              $userid=$val->userid;
                              $entrydate=date('d/m/Y',strtotime($val->entry_date));
                              $regulatortype =$val->regulator_type;
                              $orderpassingauthority =$val->order_passing_authority;
                              ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td>
                                
                                <label title="<?php echo $orderpassingauthority; ?>"><?php echo substr($orderpassingauthority,0,50); ?></label>

                                </td>
                                <td><?php echo $regulatortype; ?></td>
                                <td><?php echo $fee; ?></td>
                                <td><?php echo $status; ?></td>
                                <td><?php echo $userid; ?></td>
                                <td><?php echo $entrydate; ?></td>
                                <td>
                                <a href="javascript:void(0)" onclick="deleteregulator('<?php echo $id; ?>');">Delete</a>&nbsp;&nbsp;
                                <a href="javascript:void(0)"  data-toggle="modal" data-target="#exampleModal" onclick="editregulator('<?php echo $id; ?>');">Edit</a>
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
        <h5 class="modal-title" id="exampleModalLabel">Add Checklist</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  <input type="hidden" id="idval" name="idval" value="">
		  <input type="hidden" id="action" name="action" value="add">
          <div class="form-group">
            <label for="exampleFormControlSelect1">Regulator Type</label>
            <select class="form-control" id="rtype" name="rtype" required >
              <option value="">---Select Regulator Type ---</option>
              <option value="1">SEBI</option>
              <option value="3">IRDAI</option>
              <option value="2">PFRDA</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="exampleFormControlInput1">Order Passing Authority</label>
            <input type="text" class="form-control" id="op_name" name="op_name" placeholder="Please enter Order passing Authority" required >
          </div>
          
          <div class="form-group">
            <label for="exampleFormControlInput1">Regulator Fee</label>
            <input type="text" class="form-control" id="rfee" name="rfee" placeholder="Please enter fee" required >
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
    var op_name = document.getElementById("op_name").value;
    var status = document.getElementById("status").value;
    var token= '<?php echo $token; ?>';
    var rtype= document.getElementById("rtype").value;
    
    var action= document.getElementById("action").value;
    var idval= document.getElementById("idval").value;
    var rfee= document.getElementById("rfee").value;
    
    var rtypename=$('#rtype').find("option:selected").text();
    var dataa={};
    dataa['status']=status,
    dataa['rtype']=rtype,
    dataa['op_name']=op_name,
    dataa['token']=token,
    dataa['rtypename']=rtypename,
    dataa['rfee']=rfee,
    dataa['action']=action,
    dataa['idval']=idval,
    
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'addregulator',
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
   
   
   
   function deleteregulator(e){
        var token= '<?php echo $token; ?>';
        var x = confirm("Are you sure you want to delete?");
   	    var dataa={};
        dataa['id']=e,
        dataa['token']=token
    	if (x) {
           	$.ajax({
        	    dataType: 'json',
                type: "POST",
                url: base_url+'deleteregulator',
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
   
   function editregulator(val){
       var token= '<?php echo $token; ?>';
        var x = confirm("Are you sure you want to modify record ?");
   	    var dataa={};
        dataa['id']=val,
        dataa['token']=token
    	if (x) {
           	$.ajax({
        	    dataType: 'json',
                type: "POST",
                url: base_url+'editregulator',
                data: dataa,
                cache: false,
        		beforeSend: function(){
        			//$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
        		},
                success: function (resp) {
                	$('#op_name').val(resp.order_passing_authority);
                	$("#status option[value='"+resp.status+"']").attr("selected","selected");
                	$("#rtype option[value='"+resp.recordid+"']").attr("selected","selected");
                	$('#idval').val(resp.idval);
                	$('#rfee').val(resp.rfee);
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