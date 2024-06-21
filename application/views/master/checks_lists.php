<?php 
$this->load->view("admin/header"); 
$this->load->view("admin/sidebar");
$token= $this->efiling_model->getToken();
?>

<?= form_fieldset('Add Check List (Master Check List)').
'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'.
'<div class="date-div text-success">Date & Time : &nbsp;<small>'.date('D M d, Y H:i:s').'&nbsp;IST</small></div>';
?>
<div class="container">
    <div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
              <div class="container">
              	<span> 
                  	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                      Add Checklist
                    </button>
                </span>
              	 <table id="example" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Check List Name</th>
                                 <th>Status.</th>
                                <th>User.</th>
                                <th>Date of Entry</th>
                                <th>Action one</th>  
                                <th>Action two</th>    
                                <th>Action three</th>  
                                <th>Action</th>        
                            </tr>
                        </thead>
                        <tbody>
                         <?php
                         if(is_array(@$checklist)){
                          $i=1;
                          foreach($checklist as $val){  
                              $status=$val->status;
                              $id=$val->id;
                              $userid=$val->userid;
                              $c_name=$val->c_name;
                              $short_name =$val->shortname;
                              ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td>
                                <label title="<?php echo $short_name; ?>"><?php echo substr($short_name,0,50); ?></label>
                                </td>
                                <td><?php echo $status; ?></td>
                                <td><?php echo $userid; ?></td>
                                <td><?php echo $val->entry_time; ?></td>
                                <td><?php echo $val->action_one; ?></td>
                                <td><?php echo $val->action_two; ?></td>
                                <td><?php echo $val->action_three; ?></td>
                                <td>
                                <a href="javascript:void(0)" onclick="deletecheck('<?php echo $id; ?>');">Delete</a>&nbsp;&nbsp;
                                <a href="javascript:void(0)" onclick="deletecheck('<?php echo $id; ?>');">Edit</a>
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
          <div class="form-group">
            <label for="exampleFormControlInput1">Checklist Name</label>
            <input type="text" class="form-control" id="c_name" name="c_name" placeholder="Please Enter name" required >
          </div>
          
          <div class="form-group">
            <label for="exampleFormControlInput1">Short Name</label>
            <input type="text" class="form-control" id="short_name"  name="short_name" placeholder="Please short name" required >
          </div>
          
           <div class="form-group">
            <label for="exampleFormControlInput1">Action One</label>
            <input type="text" class="form-control" id="action_one"  name="action_one" placeholder="Please action one" required >
          </div>
          
           <div class="form-group">
            <label for="exampleFormControlInput1">Action Two</label>
            <input type="text" class="form-control" id="action_two"  name="action_two" placeholder="Please action two" required >
          </div>
          
           <div class="form-group">
            <label for="exampleFormControlInput1">Action Three</label>
            <input type="text" class="form-control" id="action_three"  name="action_three" placeholder="Please action three" required >
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
        <button type="button" class="btn btn-primary" onclick="submitcmaster();">Save changes</button>
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
    
    
   function submitcmaster(){
    var c_name = document.getElementById("c_name").value;
    var shortname = document.getElementById("short_name").value;
    var status = document.getElementById("status").value;
    var token= '<?php echo $token; ?>';
    var action_one= document.getElementById("action_one").value;
    var action_two= document.getElementById("action_two").value;
    var action_three= document.getElementById("action_three").value;
    var dataa={};
    dataa['status']=status,
    dataa['shortname']=shortname,
    dataa['c_name']=c_name,
    dataa['token']=token,
    dataa['action_one']=action_one,
    dataa['action_two']=action_two,
    dataa['action_three']=action_three
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'addchecklist',
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
   
   
   
   function deletecheck(e){
        var token= '<?php echo $token; ?>';
        var x = confirm("Are you sure you want to varified?");
   	    var dataa={};
        dataa['id']=e,
        dataa['token']=token
    	if (x) {
           	$.ajax({
        	    dataType: 'json',
        	    dataType: 'json',
                type: "POST",
                url: base_url+'deletecheck',
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
</script>
 <?= form_fieldset_close(); ?>
  <?php $this->load->view("admin/footer"); ?>