<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view("admin/header"); 
$this->load->view("admin/sidebar"); 
$token= $this->efiling_model->getToken();?>
<div class="content" style="padding-top:0px;">
	<div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
              <div class="container">
			<h6>Judge  List</h6>
			 	<span> 
                  	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                      Add Judge
                    </button>
                </span>
			
               <table id="example" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Judge Name</th>
                                <th>Retired</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                         <?php 
                         $i=1;foreach($judge as $val){ 
                             $valre="Yes";
                             if($val->retired=="false"){
                                 $valre="No";
                             }
                             ?>
                            <tr style="background-color:<?php echo $color;  ?>">
                                <td><?php echo $i; ?></td>
                                <td><a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModalCenter" onclick="vieworgdetails('<?php echo $val->id; ?>');"><?php echo ucfirst($val->judge_name); ?></a></td>
                                <td><?php  echo $valre; ?></td>
                                 <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"  onclick="edit_judge('<?php echo $val->id; ?>');">Edit</button></td>
                                <td><button type="button" class="btn btn-danger"  onclick="delete_judge('<?php echo $val->id; ?>');">Delete</button></td>
                            </tr>
                         <?php $i++;} ?>   
                        </tbody>
                    </table>
              </div>
        </div>
	</div>
		</div>
	
	
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Judge </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div id="msgvvsa"></div>
      <div class="row">
         <div class="col-lg-12">
             <div class="row">
             	<input type="hidden" name="adv_id" id="adv_id" value="">
             	<input type="hidden" name="action" id="action" value="add">
             	
              	  <div class="col-lg-6">
                      <div class="form-group">
                        <label for="exampleFormControlInput1">Judge Name</label>
                        <input type="text" class="form-control" id="judgename" value=""  name="judgename" placeholder="Please enter judge name" required >
                      </div>
                  </div>
                  
                  <div class="col-lg-6">
                      <div class="form-group">
                        <label for="exampleFormControlSelect1">Status</label>
                        <select class="form-control" id="status">
                          <option value="1">Active</option>
                          <option value="0">Un Active</option>
                        </select>
                      </div>
                  </div>
                  
                  <div class="col-lg-6">
                      <div class="form-group">
                        <label for="exampleFormControlInput1">Prefix (Ex. Hon'ble)</label>
                        <input type="text" class="form-control" id="prefix" value="" name="prefix" placeholder="Please enter Prefix (Ex. Hon'ble)" required >
                      </div>
                  </div>
                  	
                  <div class="col-lg-6">
                      <div class="form-group">
                        <label for="exampleFormControlSelect1">Display Judge Name</label>
                        <select class="form-control" id="display" name="display">
                          <option value="1">Yes</option>
                          <option value="0">No</option>
                        </select>
                      </div>
                  </div>
                  
                  <div class="col-lg-6">
                      <div class="form-group">
                        <label for="exampleFormControlSelect1">Display Judge Name</label>
                        <select class="form-control" id="designation" name="designation">
                          <option value="1">Technical Member</option>
                          <option value="2">Judicial Member</option>
                        </select>
                      </div>
                  </div>
                  
                  
          	  </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="savejudje();">Save</button>
      </div>
    </div>
  </div>
</div>






<!--User Details-->	
	<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Judge Details </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
<div id="msg"></div>
<div class="modal-body" id="record">
	<div class="container">
      	<div class="row">
    		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad">
          		<div class="panel panel-info">
                    <div class="panel-heading">
                      <h3 class="panel-title" id="username"></h3>
                    </div>
                	<div class="panel-body">
                  	 <div class="row">
                        <div class=" col-md-9 col-lg-9 ">
                          <table class="table table-user-information">
                            <tbody>
                              <tr>
                                <td>Retired:</td>
                                <td id="retired"></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                  	</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

    <script>
    $('.nav-link').click(function() { 
        var content = $(this).data('value');
        if(content!=''){
        	$('.steps').empty().load(base_url+'/efiling/'+content);
        }
    });
    
    
  $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        } );
    } );


    function action_orgapprove(val,status){
    	$("#status option[value='"+status+"']").attr("selected","selected");
    	$("#adv_id").val(val);  
    }  


    function vieworgdetails(id){
    	var dataa={};
        dataa['id']=id;
        dataa['token']='<?php echo $token; ?>';
     	$.ajax({
             type: "POST",
             url: base_url+"judge_view",
             data: dataa,
             cache: false,
             success: function (resp) {
             	var resp = JSON.parse(resp);
             	if(resp) {
             		$('#username').text(resp[0].judge_name);
             		$('#retired').text(resp[0].retired);
     			}
             	if(resp.data=='error') {
             		$('#msg').html('<span style="color:red"><h2>'+resp.massage+'</h2></span>');
     			}
             },
             error: function (request, error) {
 				$('#loading_modal').fadeOut(200);
             }
         });
    }
      

    
    
    function delete_judge(val){
    	var adv_id =val;
    	var token=Math.random().toString(36).slice(2); 
 	    var token_hash=HASH(token+'deletej');
    	var dataa={};
        dataa['org_id']=adv_id;
        dataa['token']=token;
    	var x = confirm("Are you sure  want to delete?");
    	if (x) {
         	$.ajax({
                 type: "POST",
                 url: base_url+"deletejudge/"+token_hash,
                 data: dataa,
                 cache: false,
                 success: function (resp) {
                 	var resp = JSON.parse(resp);
                 	if(resp.data=="success") {
                 		   $('#msgvvsa').html('<span style="color:green"><h2>'+resp.massage+'</h2></span>');
                 			setTimeout(function(){location.reload()}, 3000);
         			}
                 	if(resp.data=='error') {
                 		$('#msg').html('<span style="color:red"><h2>'+resp.massage+'</h2></span>');
         			}
                 },
                 error: function (request, error) {
     				$('#loading_modal').fadeOut(200);
                 }
             });
        } 
    }
    
    
    function edit_judge(val){
        var token=Math.random().toString(36).slice(2); 
 	    var token_hash=HASH(token+'getdata');
    	var dataa={};
        dataa['jujid']=val;
        dataa['token']=token;
     	$.ajax({
             type: "POST",
             url: base_url+"getjudje/"+token_hash,
             data: dataa,
             cache: false,
             success: function (resp) {
             	var resp = JSON.parse(resp);
             	     var a = 0;
             	    if(resp[0].display==true){
             	    	var a = 1;
             	    }
 				    $('#adv_id').val(resp[0].id);  
             		 $('#action').val('edit');
             		 $('#status').val(a);  
             		 $('#prefix').val(resp[0].hon_text);  
             		 $('#display').val(a);
             		 $('#designation').val(resp[0].judge_desg_code);  
             		 $('#judgename').val(resp[0].judge_name);  
             		 
             	if(resp.data=='error') {
             		$('#msg').html('<span style="color:red"><h2>'+resp.massage+'</h2></span>');
     			}
             },
             error: function (request, error) {
 				$('#loading_modal').fadeOut(200);
             }
         });
    }
    
    
   function savejudje(){
        var action =$('#action').val();
        var judgename =$('#judgename').val();
        var status =$('#status').val();
        var prefix =$('#prefix').val();
        var designation=$('#designation').val();
        var appeal_type='';
        var display='';
        var display =$('#display').val();
        if(action=='edit'){
         	var jujid =$('#adv_id').val();
        }
        var token=Math.random().toString(36).slice(2); 
 	    var token_hash=HASH(token+'addj');
    	var dataa={};
    	dataa['action']=action;
        dataa['judgename']=judgename;
        dataa['status']=status;
        dataa['prefix']=prefix;
        dataa['display']=display;
        dataa['token']=token;
        dataa['jujid']=jujid;
        dataa['designation']=designation;
    	var x = confirm("Are you sure  want to add?");
    	if (x) {
         	$.ajax({
                 type: "POST",
                 url: base_url+"addjudge/"+token_hash,
                 data: dataa,
                 cache: false,
                 success: function (resp) {
                 	var resp = JSON.parse(resp);
                 	if(resp.data=="success") {
                 		   $('#msgvvsa').html('<span style="color:green"><h2>'+resp.massage+'</h2></span>');
                 			setTimeout(function(){location.reload()}, 3000);
         			}
                 	if(resp.data=='error') {
                 		$('#msg').html('<span style="color:red"><h2>'+resp.massage+'</h2></span>');
         			}
                 },
                 error: function (request, error) {
     				$('#loading_modal').fadeOut(200);
                 }
             });
        } 
   }
    
    
      
    </script>
 <?php $this->load->view("admin/footer"); ?>