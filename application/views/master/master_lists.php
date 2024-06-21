<?php 
$this->load->view("admin/header"); 
$this->load->view("admin/sidebar");
$token= $this->efiling_model->getToken();
?>

<?= form_fieldset('Master Document').
'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'.
'   ';
?>
<div class="container">
    <div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
              <div class="container">
              	<span> 
                  	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                      Add Master Document
                    </button>
                </span>
              	 <table id="example" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Doc Type</th>
                                <th>Doc Name</th>
                                <th>Status.</th>
                                <th>Date of Entry</th>
                                <th>Action</th>        
                            </tr>
                        </thead>
                        <tbody>
                         <?php
                         if(is_array(@$mdocs)){
                          $i=1;
                          foreach($mdocs as $val){  
                              $status=$val->status;
                              $statusval='Active';
                              if($status=='0'){
                                  $statusval='Un Active';
                              }
                              $id=$val->id;
                              $userid=$val->userid;
                              $docname=$val->docname;
                              $doctype=$val->doctype;
                              ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $doctype; ?></td>
                                <td>
                                <label title="<?php echo $docname; ?>"><?php echo $docname; ?></label>
                                </td>
                                <td><?php echo $statusval; ?></td>
                                <td><?php echo $val->entry_date; ?></td>  
                                <td>
                                <a href="javascript:void(0)" onclick="deletecheck('<?php echo $id; ?>');">Delete</a>&nbsp;&nbsp;
                                <!-- <a href="javascript:void(0)" onclick="deletecheck('<?php echo $id; ?>');">Edit</a>- -->
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
        <h5 class="modal-title" id="exampleModalLabel">Add Document</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label for="exampleFormControlInput1">Document Name</label>
            <input type="text" class="form-control" id="d_name" name="d_name" placeholder="Please Enter name" required >
          </div>
          
          <div class="form-group">
            <label for="exampleFormControlSelect1">Status</label>
            <select class="form-control" id="status" name="status" required >
              <option value="1">Active</option>
              <option value="0">Un-Active</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="exampleFormControlSelect1">Status</label>
            <select class="form-control" id="d_type" name="d_type" required >
              <option value="ia">IA</option>
              <option value="rpepcp">RP/EP/CP</option>
              <option value="caveate">Caveate</option>
              <option value="app">APPEAL</option>
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
    var d_name = document.getElementById("d_name").value;
    var status = document.getElementById("status").value;
    var d_type = document.getElementById("d_type").value;
    var token= '<?php echo $token; ?>';
    var dataa={};
    dataa['status']=status,
    dataa['d_name']=d_name,
    dataa['d_type']=d_type,
    dataa['token']=token,
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'adddocmaster',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		$.alert({
					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success</b>',
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
        var x = confirm("Are you sure  want to delete?");
   	    var dataa={};
        dataa['id']=e,
        dataa['token']=token
    	if (x) {
           	$.ajax({
        	    dataType: 'json',
        	    dataType: 'json',
                type: "POST",
                url: base_url+'deletedocmaster',
                data: dataa,
                cache: false,
        		beforeSend: function(){
        			//$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
        		},
                success: function (resp) {
                	if(resp.data=='success') {
                		$.alert({
        					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success</b>',
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