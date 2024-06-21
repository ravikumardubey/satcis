
 <thead>
        <tr>
            <th>Sr. No. </th>
            <th>Appellant Name</th>
            <th>Designation</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead> 
<?php
$id=$this->input->post('id',true);
$party=$this->input->post('party',true);
$salt=$this->input->post('salt',true); 
if($party=='appleant'){
    $vals=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
    if($vals[0]->pet_name!=''){
        $petName=$vals[0]->pet_name;
        if (is_numeric($vals[0]->pet_name)) {
            $orgname=$this->efiling_model->data_list_where('master_org','org_id',$vals[0]->pet_name);
            $petName=$orgname[0]->org_name;
        }
        $type="main";
        ?>
        <tr style="color:green">
            <td><?php echo 1; ?></td>
            <td><?php echo $petName; ?>(R-1)</td>
            <td><?php echo $vals[0]->pet_degingnation ?></td>
            <td> <?php echo $vals[0]->petmobile ?></td>
            <td><?php echo $vals[0]->pet_email ?></td>
            <td><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1" data-toggle="modal" data-target="#exampleModal"  onclick="editParty('<?php echo $row->id; ?>','<?php echo $appleant; ?>','<?php echo $type; ?>')"></td>
            <td></td>
        </tr>
      <?php 
    }
    $st=$this->efiling_model->delete_event('sat_temp_additional_party', 'id', $id);
    $feesd=$this->efiling_model->data_list_where('sat_temp_additional_party','salt',$salt);
    $i=2;
    foreach ($feesd as $row){
        $id=$row->id;
        $petName=$row->pet_name;
        if (is_numeric($row->pet_name)) {
            $orgname=$this->efiling_model->data_list_where('master_org','org_id',$row->pet_name);
            $petName=$orgname[0]->org_name;
        }
        $appleant="appleant";
        $type="add";
        ?>
       	        
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $petName; ?>(A-<?php echo $i; ?>)</td>
            <td><?php echo $row->pet_degingnation; ?></td>
            <td><?php echo $row->pet_mobile; ?></td>
            <td><?php echo $row->pet_email; ?></td>
            <td><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1" data-toggle="modal" data-target="#exampleModal"  onclick="editParty('<?php echo $row->id; ?>','<?php echo $appleant; ?>','<?php echo $type; ?>')"></td>
            <td><input type="button" name="nextsubmit" id="nextsubmit" value="Delete" class="btn1" onclick="deleteParty('<?php echo $row->id; ?>','<?php echo $appleant; ?>')"></td>	
        </tr>
    <?php
    $i++;
    }
}




















if($party=='res'){
    
    $vals=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
     if($vals[0]->resname!=''){
        $petName=$vals[0]->resname;
        if (is_numeric($vals[0]->resname)) {
            $orgname=$this->efiling_model->data_list_where('master_org','org_id',$vals[0]->resname);
            $petName=$orgname[0]->org_name;
        }
        $appleant="res";
        $type="main";
        ?>                       
     	<tr style="color:green">
          <td><?php echo 1; ?></td>
            <td><?php echo $petName; ?>(R-1)</td>
            <td><?php echo $vals[0]->pet_degingnation ?></td>
            <td> <?php echo $vals[0]->res_mobile ?></td>
            <td><?php echo $vals[0]->res_email ?></td>
            <td><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1" data-toggle="modal" data-target="#exampleModal"  onclick="editParty('<?php echo $row->id; ?>','<?php echo $appleant; ?>','<?php echo $type; ?>')"></td>
    		<td></td>
        </tr>
      <?php } 
      $st=$this->efiling_model->delete_event('sat_temp_additional_res', 'id', $id);
      $appleant="res";
      $type="add";
      $feesd=$this->efiling_model->data_list_where('sat_temp_additional_res','salt',$salt);
      $i=2;
      foreach ($feesd as $row){
          $resName=$row->res_name;
          if (is_numeric($row->res_name)) {
              $orgname=$this->efiling_model->data_list_where('master_org','org_id',$row->res_name);
              $resName=$orgname[0]->orgdisp_name;
          }
          $id=$row->id;
          $type="main";
          ?>
        <tr>
             <td><?php echo $i;?></td>
            <td><?php echo  $resName;?>(R-<?php echo $i;?>)</td>
            <td><?php echo $row->res_degingnation;?></td>
            <td><?php echo $row->res_mobile;?></td>
            <td><?php echo $row->res_email;?></td>
            <td><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1" data-toggle="modal" data-target="#exampleModal"  onclick="editParty('<?php echo $row->id; ?>','<?php echo $appleant; ?>','<?php echo $type; ?>')"></td>
            <td><input type="button" name="nextsubmit" id="nextsubmit" value="Delete" class="btn1" onclick="deleteParty('<?php echo $row->id; ?>','<?php echo $appleant; ?>')"></td>
        </tr>
        <?php
        $i++;
    }
}
?>