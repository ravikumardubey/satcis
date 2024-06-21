<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>


<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content" style="padding-top:0px;">
	<div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
              <div class="container">
			<h6>User  List</h6>
              	 <table id="example" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Login Id</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Register Date</th>
                                <th>Detail </th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                         <?php 
                         $i=1;foreach($users as $val){ 
                             $status='';$color='';

                             if($val->verified=='1'){ 
                                 $status= "Approved"; 
                                 $color='btn  btn-success'; 
                                 $action= "Approved"; 
                             }else{ 
                                 $status=  "Pending";
                                 $color='btn btn-warning';
                                 $action= "Approval Pending";  
                             }
                             if($val->verified=='2'){
                                 $action= "Rejected"; 
                                 $color='btn  btn-danger';
                             }
                             ?>
                            <tr style="background-color:<?php echo $color;  ?>">
                                <td><?php echo $i; ?></td>
                                <td><?php echo ucfirst($val->fname.' '.$val->lname); ?></td>
                                <td><?php echo $val->user_type; ?></td>
                                <td><?php echo $val->loginid; ?></td>
                                <td><?php echo $val->mobilenumber; ?></td>
                                <td><?php  echo $status; ?></td>
                                <td><?php  echo $val->dinsert_date; ?></td>
                                <td><a href="<?php echo base_url(); ?>/userdetails/<?php echo hash('sha256','.'.$val->idproof_upd); ?>/<?php echo $val->id;?>" target="_blank"><?php  echo $val->idptype.' - '.$val->id_number; ?></a></td>
                                <td>
                                <button  type ='button' data-toggle="modal" data-target="#exampleModal" class="<?php echo $color; ?>" onclick="action_approve('<?php echo $val->id; ?>','<?php echo $val->verified; ?>');">
                                  <?php echo $action; ?>
                                </button>
                                </dt>
                            </tr>
                         <?php $i++;} ?>   
                        </tbody>
                    </table>
              </div>
        </div>
	</div>
	
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLongTitle">Edit User</h5>
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
              <option value="1">Approved</option>
              <option value="2">Rejected </option>
              <option value="0">Pending</option>
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



<!--User Details-->	
	<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">User Details </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="record">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
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


    function action_approve(val,status){
    	$("#status option[value='"+status+"']").attr("selected","selected");
    	$("#adv_id").val(val);  
    }  


    function viewdetails(id){
    	var dataa={};
        dataa['adv_id']=id;
     	$.ajax({
             type: "POST",
             url: base_url+"user_view",
             data: dataa,
             cache: false,
             success: function (resp) {
             	var resp = JSON.parse(resp);
             	if(resp.data=='success') {
             		$('#record').html(resp.value);
     			}
             },
             error: function (request, error) {
 				$('#loading_modal').fadeOut(200);
             }
         });
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
                 url: base_url+"user_varified",
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
    </script>
		 <?php $this->load->view("admin/footer"); ?>