<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); error_reporting(0); ?>
<div class="content" style="padding-top:0px;">
	<div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
            <?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','id'=>'other_fee','autocomplete'=>'off']) ?>
                <div class="content clearfix">
                    <a href="<?php echo base_url(); ?>defective_list">Back</a>
                    <?= form_fieldset('Pending  Defects'). ''; ?>
                      	<div class="row">
                      	
                      	   <table class="table">
                              <thead>
                                <tr>
                                  <th scope="col">S.NO</th>
                                  <th scope="col">Re-defect Date</th>
                                  <th scope="col">Defect Type</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                ini_set('display_errors', 1);
                                ini_set('display_startup_errors', 1);
                                error_reporting(E_ALL);
                                
                                $state= $this->efiling_model->data_list_where('objection_details','filing_no',$filingno);
                                $i=1;
                                $subobjname11='';
                                foreach($state as $row1){
                                    if($row1->status!='Yes' ){
                                    $objection_code=$row1->objection_code;
                                    $obj= $this->efiling_model->data_list_where('master_objection','id',$objection_code);
                                    $subobjname11=$obj[0]->objection_name;
                                    $string = str_replace(' ', '', $row1->comments);
                                    $val= strlen($string);
                                    if ($val>3) {
                                        $subobjname11=$row1->comments;
                                    }
                                ?>
                                <tr>
                                      <td><?php echo htmlspecialchars($i);?></td>
                                      <td><?php echo htmlspecialchars(date('d/m/Y',strtotime($row1->entry_dt)));  ?></td>
                                      <td><?php echo $subobjname11;?></td>
                                </tr>
                          		 <?php $i++;  } 
                                }
                          		 ?>
                              </tbody>
                            </table>
                      	</div>
                    <?= form_fieldset_close(); ?>
                </div>
            <?= form_close();?>
        </div>
	</div>
</div>	
<?php $this->load->view("admin/footer"); ?>