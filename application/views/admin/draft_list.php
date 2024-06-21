<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content" style="padding-top:0px;">
	<div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
              <div class="container">
			<h6>Draft List</h6>
              	 <table id="example" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Reference No</th>
                                <th>Party</th>
                                <th>bench</th>
                                <th>Sub bench</th>
                                <th>Year</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                         <?php 
                         if(is_array(@$draft)){
                         $i=1;foreach($draft as $val){ 
                             if(is_numeric($val->pet_name)){
                                 $party= $this->efiling_model->data_list_where('master_org','org_id',$val->pet_name);
                             }
                             $bench= $this->efiling_model->data_list_where('master_benches','bench_code',$val->bench);                            
                             $jurisdiction= $this->efiling_model->data_list_where('master_psstatus','state_code',$val->sub_bench);
                             
                             ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo 'R-'.$val->salt; ?></td>
                                <td><?php echo isset($party[0]->org_name)?$party[0]->org_name:$val->pet_name; ?></td>
                                <td><?php echo  $bench[0]->name ?></td>
                                <td><?php echo  $jurisdiction[0]->state_name; ?></td>
                                <td><?php echo date('Y'); ?></td>
                                <td><button class="dbox__action__btn" style="border: 0px; border-radius: 29px;"><a href="<?php echo base_url();?>draftrefiling/<?php echo $val->salt; ?>/<?php echo $val->tab_no; ?>">Edit-Final</a></button></dt>
                            </tr>
                         <?php $i++;} } ?>   
                        </tbody>
                    </table>
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


    </script>