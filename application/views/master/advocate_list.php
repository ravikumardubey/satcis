<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); 
$token= $this->efiling_model->getToken();?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content" style="padding-top:0px;">
	<div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
              <div class="container">
			   <h6>New Advocate List</h6>
			   <div id="msgvvsa"></div>
              	 <table id="example" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Name</th>
                                <th>Enrollment No</th>
                                <th>City</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Register Date</th>
                                <th>Action</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                         <?php $i=1;foreach($adv as $val){ 
                             $status='';$color='';
                             if($val->status=='1'){ $status= "Active"; $color='btn-success'; $action= "Varified"; }else{ $status=  "Non Active";$color='btn-primary';$action= "Not Varified";  }
                             ?>
                            <tr style="background-color:<?php echo $color;  ?>">
                                <td><?php echo $i; ?></td>
                                <td><?php echo $val->adv_name; ?></td>
                                <td><?php echo $val->adv_reg; ?></td>
                                <td><?php echo $val->adv_dis; ?></td>
                                <td><?php echo $val->adv_mobile; ?></td>
                                <td><?php  echo $status; ?></td>
                                <td><?php echo $val->entry_date; ?></td>
                                <td>
                                <button type="button" class="btn <?php echo $color; ?>" data-toggle="modal" data-target="#exampleModalCenter" onclick="action_approve('<?php echo $val->id; ?>','<?php echo $val->status; ?>');">
                                  <?php echo $action; ?>
                                </button>
                                </td>
                                <td>
                                  <button type="button" class="btn btn-danger"  onclick="delete_adv('<?php echo $val->id; ?>');">
                                 Delete
                                </button>
                                </td>
                                
                            </tr>
                         <?php $i++;} ?>   
                        </tbody>
                    </table>
              </div>
        </div>
	</div>
	
	
	<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Advocate</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
              <div id="msg"></div>
      <input type="hidden" name="adv_id" id="adv_id" value="">
          <div class="form-group">
            <label for="exampleFormControlSelect1">Status</label>
            <select class="form-control" id="status">
              <option value="0">Not Verified</option>
              <option value="1">Varified</option>
            </select>
          </div>
          <div class="form-group">
            <label for="exampleFormControlTextarea1">Remark</label>
            <textarea class="form-control" id="remark" name="remark" rows="3"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="action_approve_action();">Save</button>
      </div>
    </div>
  </div>
</div>
</div>
 <?php $this->load->view("admin/footer"); ?>


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


    function action_approve(val,status){
    	$("#status option[value='"+status+"']").attr("selected","selected");
    	$("#adv_id").val(val);  
    }  

      
    function action_approve_action(){
    	var status =$("#status").val();
    	var remark =$("#remark").val();
    	var adv_id =$("#adv_id").val();
    	var dataa={};
        dataa['adv_id']=adv_id;
        dataa['status']=status;
        dataa['remark']=remark;
    	var x = confirm("Are you sure you want to varified?");
    	if (x) {
         	$.ajax({
                 type: "POST",
                 url: base_url+"adv_varified",
                 data: dataa,
                 cache: false,
                 success: function (resp) {
                 	var resp = JSON.parse(resp);
                 	if(resp.data=='success') {
                 		$('#msg').html('<span style="color:green"><h2>'+resp.massage+'</h2></span>');
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
    
    
    
    
     function delete_adv(val){
    	var adv_id =val;
    	var dataa={};
        dataa['adv_id']=adv_id;
        dataa['token']='<?php echo $token; ?>';
    	var x = confirm("Are you sure you want to delete?");
    	if (x) {
         	$.ajax({
                 type: "POST",
                 url: base_url+"deleteadv",
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
	