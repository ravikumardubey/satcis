<?php 
$this->load->view("admin/header"); 
$this->load->view("admin/sidebar");
$token= $this->efiling_model->getToken();
?>

<?= form_fieldset('USER ROLE MASTER').
'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'.
'<div class="date-div text-success">Date & Time : &nbsp;<small>'.date('D M d, Y H:i:s').'&nbsp;IST</small></div>';
?>

<div class="container">
    <div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
              <div class="container">
              	<span> 
                  	<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> 
                      Add Regulator
                    </button>-->
                </span>
              	 <table id="example" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>User Name</th>
                                <th>Email </th>
                                <th>Phone No</th>
                                <th>Status.</th>
                                <th>User Type</th>
                                <th>Date of Entry</th>
                                <th>Action</th>        
                            </tr>
                        </thead>
                        <tbody>
                         <?php
                         if(is_array(@$checklist)){
                          $i=1;
                          foreach($checklist as $val){  
                              $id=$val->id;
                              $status=$val->verified;
                              $name=$val->fname;
                              $email=$val->email;
                              $phone=$val->mobilenumber;
                              $role=$val->user_type;
                              $entrydate=date('d/m/Y',strtotime($val->dinsert_date));
                              
                              ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                               
                                <td><?php echo $name; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $phone; ?></td>
                                <td><?php echo $status; ?></td>
                                <td><?php echo $role; ?></td>
                                <td><?php echo $entrydate; ?></td>
                                <td>
                                <!-- <a href="javascript:void(0)" onclick="deleteregulator('<?php echo $id; ?>');">Delete</a>&nbsp;&nbsp; -->
                                <a href="javascript:void(0)" onclick="editusrole('<?php echo $id; ?>');">Edit</a>
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
        <h5 class="modal-title" id="exampleModalLabel">Edit Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  <input type="hidden" id="idval" name="idval" value="">
		  <input type="hidden" id="action" name="action" value="add">
          <div class="form-group">
            <label for="exampleFormControlSelect1">Role Type</label>
            <select class="form-control" id="rtype" name="rtype" required >
              <option value="0">---Select Role Type ---</option>
              <option value="2">Scrutiny Clerk</option>
              <option value="3">Deputy Registrar</option>
              <option value="4">Registrar</option>
            </select>
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
        <button type="button" class="btn btn-primary"  onclick="submitrole();">Save changes</button>
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

    function submitrole(){
    var status = document.getElementById("status").value;
    var token= '<?php echo $token; ?>';
    var rtype= document.getElementById("rtype").value;
    
    var action= document.getElementById("action").value;
    var idval= document.getElementById("idval").value;
    
    var rtypename=$('#rtype').find("option:selected").text();
    var dataa={};
    dataa['status']=status,
    dataa['rtype']=rtype,
    dataa['token']=token,
    dataa['action']=action,
    dataa['idval']=idval,
    
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'updaterole',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		$.alert({
					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Congrates</b>',
					content: '<p class="text-success">Role Updated successfully.</p>',
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

    function editusrole(val){
       var token= '<?php echo $token; ?>';
        var x = confirm("Are you sure you want to modify record ?");
       
   	    var dataa={};
        dataa['id']=val,
        dataa['token']=token
    	if (x) {
           	$.ajax({
        	    dataType: 'json',
                type: "POST",
                url: base_url+'editusrole',
                data: dataa,
                cache: false,
                success: function (resp) {
                  $("#status option, #rtype option").removeAttr("selected");
                	$("#status option[value='"+resp.status+"']").attr("selected","selected");
                  $("#rtype option[value='"+resp.role+"']").attr("selected","selected");
                	$('#idval').val(resp.idval);
                	$('#action').val('edit');
                	$('#exampleModal').modal('show');
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




<?php $this->load->view("admin/footer"); ?>