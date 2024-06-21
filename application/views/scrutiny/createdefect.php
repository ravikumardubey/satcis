<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<form name="form2" method="post" action="<?php echo base_url();?>defectLetter_actions">
<div class="content" >
   <!-- - <div class="col-sm-12 div-padd">
        	 <div class="col-ld-12" id="diaryDetail" style="display:block;padding: 20px;">
        	 	<div class="row">
                    <div class="col-md-4">
                    <?php $diaryNo = $filingno; ?>
                        <div><label for="name"><span class="custom"><font color="red">*</font></span>AL No.</label></div>
                        <div>
                            <select name="diaryNo" class="form-control" id="diaryNo">
                                <option value="">Select AL No.</option>
                                <?php
                                $obj =$this->db->query("select  filing_no from  scrutiny where filing_no='$diaryNo' AND notification_date is not null and (defects='Y' or 
                                defects is NULL) order by filing_no DESC");
                  
                     
                                $objval= $obj->result();
                                if(!empty($objval)){
                                    foreach($objval as $row){
                                          $count_data =$this->efiling_model->getColumn('defect_letter','flag','filing_no',$row->filing_no);
                                         if($count_data !='FI') {                                 
                                            $token_number = ltrim(substr($row->filing_no, 5, 6), '0');
                                            $selected_val = '';
                                            if ($diaryNo == $token_number) {
                                                $selected_val = 'selected';
                                            }
                                            echo "<option value=" . htmlspecialchars($token_number) . " $selected_val >" . htmlspecialchars($token_number) . "</option>";
                                         }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <?php $diaryYear = isset($_REQUEST['diaryYear']) ? $_REQUEST['diaryYear'] : ''; ?>
                        <div><label for="name"><span class="custom"><font color="red">*</font></span>Year</label></div>
                        <div><input type="text" name="diaryYear" id="diaryYear" maxlength="4"   onkeypress="return isNumberKey(event)" class="form-control"    value="<?php echo htmlspecialchars(htmlentities($diaryYear)); ?>"/></div>
                    </div>
                    <div class="col-md-4" style="margin-top: 28px;">
                        <button type="button" class="btn btn-success" name="defectsubmit" onclick="diary();">Submit</button>
                    </div>
            	</div>
    		</div>
		</div> -->
		
		
		     <?php
         
                $fil_no = $filingno;
            $st = $this->efiling_model->data_list_where('objection_details','filing_no',$fil_no);
            foreach ($st as $row) {
                $filing_no = htmlspecialchars($row->filing_no);
            }
            if ($filing_no == ""){
                echo "Data Not Found...................";
            }
            else {
             
            $stva = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
            foreach ($stva as $row) {
                $petName = $stva->pet_name;
                $caseType = $stva->case_type;
                $resName = $stva->res_name;
                $fDate = $stva->dt_of_filing;
                $dateOfFiling = explode("-", $fDate);
                $statu = $stva->status;
                $ref_filing_no_ii =$stva->ref_filing_no;

                if ($statu == 'P')
                    $statusName = 'Pending';
                if ($statu == 'D')
                    $statusName = 'Disposal';
            }
            
            $sqld = $this->efiling_model->data_list_where('defect_letter','filing_no',$filing_no);
            foreach ($sqld as $rowdef) {
                $defect = $rowdef->defect_name;
                $registrar =  $rowdef->reg_name;
                $flag =  $rowdef->flag;
            }
            
            if ($defect == '') {
                $maxten = $this->efiling_model->data_list_where('master_defectletter','case_type',$caseType);
                foreach ($maxten as $rowl) {
                    $defect_line1 = $rowl->defect_lineno1;
                    $defect_line2 = $rowl->defect_lineno2;
                    $defect_line3 = $rowl->defect_lineno3;
                    $defect_line4 = $rowl->defect_lineno4;
                    $defect_line5 = $rowl->defect_lineno5;
                    $defect_line6 = $rowl->defect_lineno6;
                    $defect_line7 = $rowl->defect_lineno7;
                }
            }
            list($yy, $month, $date) = explode('-', $fDate);
            $notification_date1 = $date . '/' . $month . '/' . $yy;
            ?>
            <input type="hidden" class="txt" name="filingNo" id="filingNo" value="<?php echo $filing_no; ?>" maxlength="700"/>
            <table border="1" width="90%">
                <?php
                if ($flag == 'FI') {
                    ?>
                    <tr>
                        <td width="50%" align="center">
                            <br>
                            <b class="text-danger">Final Defect Letter already Submitted ...</b><br>
                        </td>
                    </tr>
                    <?php  }else{ ?>
                <tr>
                    <td width="50%">

                        <table width="100%" border="1" cellspacing="1" cellpadding="1" align="center">
                            <tr>
                                <td align="center" colspan="2">
                                    <legend><b>Top Matter:</b></legend>
								
                                     <div class="col-sm-9">
                                     	<textarea name="defect" id="editor" value="" width="500px">
                                				<?php
                                                if ($defect != '') {
                                                    echo htmlspecialchars($defect);
                                                }
                                                  if ($defect == '') {
                                                      echo $defect_line1;
                                                      $diaryNo  =substr($filingno,7,4);
                                                      $diaryYear=substr($filingno,11,4);
                                                      ?>
                                                      &nbsp; <?php echo $diaryNo . ' of ' . $diaryYear; ?> &nbsp;
                                                      <?php
                                                      echo $defect_line2;
                                                      ?>
                                                      &nbsp; <?php echo $notification_date1; ?>
                                                      <?php
                                                      echo $defect_line3;
                                                      ?>
                                                      &nbsp; <?php
                                                      if ($caseType == '1') {
                                                          $decision_dateii = $this->efiling_model->getColumn('lower_court_detail','decision_date','filing_no',$filing_no); 
                                                          list($yy, $month, $date) = explode('-', $decision_dateii);
                                                          $decision_dateii11 = $date . '/' . $month . '/' . $yy;
                                                          echo $decision_dateii11;
                                                      }
                                                      if ($caseType > '1') {
                                                          $disposal_dateii = $this->efiling_model->getColumn('case_disposal','disposal_date','filing_no',$filing_no); 
                                                          list($yy, $month, $date) = explode('-', $disposal_dateii);
                                                          $disposal_date11 = $date . '/' . $month . '/' . $yy;
                                                          echo $disposal_date11;
                                                      }
                                                      ?>
                                                      <?php
                                                      echo $defect_line4;
                                                      ?>
                                                      &nbsp; <?php
                                                      if ($caseType == '1') {
                                                          $commissionii = $this->efiling_model->getColumn('lower_court_detail','commission','filing_no',$filing_no);  
                                                          if ($commissionii > '0' OR $commissionii != '') {
                                                              echo $commission_name = $this->efiling_model->getColumn('master_commission','full_name','id',$commissionii);  
                                                          }
                                                      }
                                        
                                        
                                                      if ($caseType > '1') {
                                                          if ($ref_filing_no_ii != '') {
                                                              $bench_idd =$this->efiling_model->getColumn('sat_case_detail','bench','filing_no',$ref_filing_no_ii); 
                                                          }
                                                          if ($bench_idd > '0') {
                                                              $bench_name = $this->efiling_model->getColumn('master_benches','name','bench_code',$bench_idd);  
                                                          }
                                                          echo $bench_name;
                                                      }
                                                      ?>
                                                      <?php
                                                      echo $defect_line5;
                                                      echo $defect_line6;
                                                      ?>
                                                      &nbsp;
                                                      <?php
                                                      if ($caseType > '1') {
                                                          $sqldis = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$ref_filing_no_ii); 
                                                          foreach ($sqldis as $rowl) {
                                                              $case_type1 = $rowl->case_type;
                                                              $case_no1 = $rowl->case_no;
                                                              $case_num = substr($case_no1, 4, 7);
                                                              $case_num11 = ltrim($case_num, 0);
                                                              $case_year11 = substr($case_no, 11, 4);
                                                          }
                                                          if ($case_type1 != '' OR $case_type1 > '0') {
                                                              $caseTypeName =$this->efiling_model->getColumn('master_case_type','case_type_name','case_type_code',$case_type1);  
                                                          }
                                                          if ($case_no1 != '') {
                                                              echo $caseTypeName . " No." . $case_num11 . " of " . $case_year11;
                                                          }
                                                          if ($case_no1 == '') {
                                                              $case_numxx = substr($ref_filing_no_ii, 5, 6);
                                                              $case_num11xx = ltrim($case_numxx, 0);
                                                              $case_year11xx = substr($ref_filing_no_ii, 11, 4);
                                                              echo "AL No" . $case_num11xx . " of " . $case_year11xx;
                                                          }
                                                      }
                                        
                                                      if ($caseType == '1') {
                                                          $sqldis =  $this->efiling_model->data_list_where('lower_court_detail','filing_no',$filing_no); 
                                                          foreach ($sqldis as $rowl) {
                                                              $case_type_lower = $rowl->case_type;
                                                              $case_no_lower = $rowl->case_no;
                                                              $case_year_lower =  $rowl->case_year;
                                                              if ($case_type_lower != '' OR $case_type_lower > '0') {
                                                                  $caseTypeName =$this->efiling_model->getColumn('master_case_type','case_type_name','case_type_code',$case_type_lower);
                                                                  echo $caseTypeName . " No. $case_no_lower of $case_year_lower under ";
                                                              }
                                                              ?>
                                                              &nbsp;
                                                              <?php
                                                          }
                                                      }
                                                      ?>
                                                      &nbsp;
                                                      <?php
                                                      echo $defect_line7;
                                        
                                                  }
                                                  ?>
                                              </textarea>
                                               <script>
                                       			 ClassicEditor
                                                .create( document.querySelector( '#editor' ) )
                                                .catch( error => {
                                                    console.error( error );
                                                    
                                                } );
                                                 
                                   			   </script>	
                                 
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="table">
                            <tr>
                                <td width="45%"><b> Defect Description</b></td>
                            </tr>
                            <tr>
                                <td width="100%">
                                    <table border="1" width="100%">
                                        <?php
                                        $ii = 1;
                                        $st = $this->efiling_model->data_list_where('objection_details','filing_no',$filing_no); 
                                     // echo "<pre>";  print_r($st);die;
                                        foreach ($st as $row) {
                                            if($row->status=='Yes'){
                                                $objectionCode = htmlspecialchars($row->objection_code);
                                                if ($objectionCode > '0') {
                                                    $objName =$this->efiling_model->getColumn('master_objection','objection_name','id',$objectionCode);
                                                    if ($objName != "") {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $ii; ?></td>
                                                            <td><?php echo $objName; ?></td>
                                                            <td ><input type="text" name="comment[]" size="50"  rows="10" cols="20" value="<?php echo htmlspecialchars($row->comments); ?>"></td>
                                                        </tr>
                                                        <?php
                                                        $objName = "";
                                                        $ii++;
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Bottom Matter</b></td>
                            </tr>
                            <tr>
                                <td>
                                <textarea rows="4" cols="100" name="defectName1">
                                Kindly note that as required under Rule 25(2) of the Appellate Tribunal for 
                                Electricity (Procedure, From, Fee and Record of Proceeding) Rules, 2007, the above
                                defects are to be cured within a period of seven days from the date of receipt of this letter
                                by you.
                                </textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                      <select name="registrar" id="registrar" class="form-control" style="width:200px">
                                        <option value="Registrar" <?php if ($registrar == 'Registrar') {echo 'selected';} ?> >Registrar
                                        </option>
                                        <option value="Deputy Registrar" <?php if ($registrar == 'Deputy Registrar') {echo 'selected';} ?> >Deputy Registrar</option>
                                    </select>                               
 								</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="submit" name="nextsubmit" id="nextsubmit" value="Create Letter"   class="btn btn-sucess"/>
                                    &nbsp; &nbsp; <input type="submit" name="nextsubmit" id="nextsubmit"   value="Final Letter Create"    class="btn btn-sucess"/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </table>
                        <?php }
                         ?>
    </div>
</form>
<script>
function diary() {
    with (document.form2) {
		target = '_self';
        action = base_url+'createdefect';
        submit();
    }
}
</script>        
<?php $this->load->view("admin/footer"); ?>				