<?php
//echo "<pre>";print_r($_REQUEST);die;
$cur_date = date('Y-m-d'); 
$dateprint =  date('d-m-Y'); 
$curdate =date('Y-m-d');    


$uri_request=$_SERVER['REQUEST_URI'];
$url_array=explode('?',$uri_request);
$parameters=@$url_array[1];
$parameters_array=explode('&',$parameters);
$spcl_char=['<'=>'','>'=>'','/\/'=>'','\\'=>'','('=>'',')'=>'','!'=>'','^'=>'',"'"=>''];
for($i=0; $i < count($parameters_array); $i++) :;
$getPara_array=explode("=",$parameters_array[$i]);
$paraName=@$getPara_array[0];
$getPvalue=@$getPara_array[1];
$_REQUEST[$paraName]=htmlspecialchars($getPvalue);
endfor;
foreach($_REQUEST as $key=>$val):;
if(is_array($val)){
    foreach($val as $key1=>$val1):;
    if(is_array($val1)){
        foreach($val1 as $key2=>$val2):;
        if(is_array($val2)) {
            $innerData[$key1][$key2]=$val2;
        }
        else    $innerData[$key1][$key2]=htmlspecialchars(strtr($val2, $spcl_char));
        endforeach;
    }
    else $innerData[$key1]=htmlspecialchars(strtr($val1, $spcl_char));
    endforeach;
    $_REQUEST[$key]=$innerData;
}
else $_REQUEST[$key]=htmlspecialchars(strtr($val, $spcl_char));
endforeach;


$defect = trim($_REQUEST['defect']);
$defectName = $_REQUEST['defectName'];
$defectName1 = $_REQUEST['defectName1'];
$registrar = $_REQUEST['registrar'];
$filingNo = $_REQUEST['filingNo'];
$diaryNo = $_REQUEST['diaryNo'];
$diaryYear = $_REQUEST['diaryYear'];
$objcode = $_REQUEST['id'];
$comment = $_REQUEST['comment'];
$nextsubmit = $_REQUEST['nextsubmit'];
$finalsubmit = $_REQUEST['finalsubmit'];
$submit_type = $_REQUEST['nextsubmit'];
$flag = 'CL';
if ($submit_type == 'Create Letter') {
    $flag = 'CL';
}
if ($submit_type == 'Final Letter Create') {
    $flag = 'FI';
}
$obj =$this->db->query("select count(filing_no) from defect_letter where filing_no='$filingNo'");
$objval= $obj->result();
if(!empty($objval)){
    $sttotal=$objval[0]->count;
}
if ($sttotal == 0) {
    $data = array(
        'filing_no'  =>  $filingNo,
        'defect_name'   =>  $defect,
        'defect_footer'      =>  $defectName1,
        'reg_name'      =>  $registrar,
        'create_date'      =>  $curdate,
        'flag'      =>  $flag
    );
    $ext = $this->db->insert('defect_letter', $data);
} else {
    for ($ii = 0; $ii < sizeof($comment); $ii++) {
        $data=array(
            'comments'=>$comment[$ii],
        );
        $where =array('objection_code'=>$objcode[$ii],'filing_no'=>$filingNo);
        $st=$this->efiling_model->update_data_where('objection_details', $where,$data);
    }
    $final_date = date('Y-m-d');
    $data=array(
        'final_date'=>$final_date,
        'defect_name'=>$defect,
        'defect_footer'=>$defectName1,
        'reg_name'=>$registrar,
        'flag'=>$flag,
    );
    $st=$this->efiling_model->update_data('defect_letter', $data,'filing_no', $filingNo); 
}

// final date
$final_date1 =$this->efiling_model->getColumn('defect_letter','final_date','filing_no',$filingNo);  
if($final_date1){
    $newdata=date('d-m-Y',strtotime($final_date1));
}else{
    $newdata=date('d-m-Y');
}
$rowcal =$this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingNo);
foreach ($rowcal as $row) {
    $petName = $row->pet_name;
    $case_no = $row->case_no;
    $caseType = $row->case_type;
    $resName = $row->res_name;
    $fDate = $row->dt_of_filing;
    $pet_adv = $row->pet_adv;
    if ($case_no != "") {
        $case_numaa = substr($case_no, 4, 7);
        $case_num1aa = ltrim($case_numaa, 0);
        $case_year1aa = substr($case_no, 11, 4);
    }
    $line5Value = '';
    if ($caseType == 1) {
        $matt = "Appellant";
        $line5Value = "Advocate for the " . $matt;
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Defect Letter</title>
    <style>
        html {
            background: #ccc;
        }
        body {
            width: 700px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #929292;
            background: #fff;
        }
        td {
            padding: 0 10px;
            text-align: left;
        }
        ol {
            margin-left: 20px;
            padding: 15px;
            line-height: 1.4;
        }
        ol li {
            padding-left: 10px;
        }
        @media print {
            html {
                background: #fff;
            }
            body {
                width: 100%;
                padding: 0;
                border: 0px solid #929292;
            }
        }
    </style>

    <script language="javascript">
        function change(id, newClass) {
            identity = document.getElementById(id);
            identity.className = newClass;

        }
        function printPage() {
            change("testdiv", "true");
            window.print();
        }
    </script>
</head>


<body style="font-size:16px; font-family: 'Times New Roman', Times, serif; text-align: justify;">
<div style="padding-left: 30px;">
    <div id="testdiv" class="pr-hide"><a href="javascript:printPage();">
            <font color="red" size="1"><img src="print.gif" class="no-print" border='0'/></font></a>
    </div>
    <table style="width:100%; border:0;">
        <tr>
            <td style="width:20%;"><img src="emb.png" width="50"></td>
            <td style="width:80%; text-align:right;">
                <div style="text-align:center; display:inline-block; font-size: 14px; font-weight:bold;font-family: Arial, Helvetica, sans-serif;">
                    Securities Appellate Tribunal<br>
                    Earnest House, House 107, NCPA Marg, Nariman Point,<br>
                    Mumbai, Maharashtra 400021<br>
                    Phone:022 2283 7062, Fax : 22021341<br>
                    Email: registrar-sat@nic.in / dyregr-sat@nic.in<br>
                    <span style="font-weight: normal; line-height: 1.6;">Dated : <?php echo $newdata; ?>.</span>
                </div>
            </td>
        </tr>
    </table>

    <p style="text-align: right;"></p>
    <p>To:<br><br>
        <?php
			if($pet_adv!=''){
			   $rowcal =$this->efiling_model->data_list_where('master_advocate','adv_code',$pet_adv);
			   foreach ($rowcal as $row) {
					$stcode = $row->state_code;
					$gender = $row->adv_sex;
					if ($gender == 'M') {
						echo "Mr. " . $row->adv_name . ',';
					}
					if ($gender == 'F') {
						echo "Ms. " . $row->adv_name . ',';
					}
					if($line5Value) {
						echo "<br>";
						echo $line5Value . ',';
					}
					echo "<br>";
					echo $row->address . ',';
					echo "<br>";
					$stateName =$this->efiling_model->getColumn('master_psstatus','state_name','state_code',$stcode);
					echo "<b>";
					echo $stateName;
					echo '-';
					echo $row->adv_pin;
                    echo "<br>";
                    echo $row->email . ',';
					echo "</b>";
				}
			}
        ?>
    </p>
    <p style="text-align:center;">(<b><u>Defects in Filing</u></b>)</p>
    <div style="text-align:center;">
        <?php
        $caseTypeName =$this->efiling_model->getColumn('master_case_type','case_type_name','case_type_code',$caseType);
      if(in_array($caseType,array('5','6','7'))) {
          $case_nonn = '';
		  $display_name = 'Petitioner(s)';
      } else {
          if ($case_num1aa != "" and $case_year1aa != "") {
              $case_nonn = $caseTypeName." No.  $case_num1aa of $case_year1aa  under";
          }
          if ($case_num1aa == "" and $case_year1aa == "") {
              $case_nonn = $caseTypeName." No.  _______ of $diaryYear under";
          }
		  	  $display_name = 'Appellants(s)';
      }
        ?>
        <table style="width:80%; display: inline-block; line-height: 1.4;" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>Ref:</td>
                <td colspan="3"><?php echo $case_nonn; ?>  AL No. <?php echo $diaryNo; ?>  of <?php echo $diaryYear; ?></td>
            </tr>
            <tr>
                <td></td>
                <td> <?php echo $petName.$this->efiling_model->fn_addition_party($filingNo,'1'); ?></td>
                <td>...</td>
                <td><?php echo $display_name; ?></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align:center;">Vs.</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td> <u><?php echo $resName.$this->efiling_model->fn_addition_party($filingNo,'2');; ?></u>
                </td>
                <td>...</td>
                <td><u>Respondents</u></td>
            </tr>
        </table>
    </div>
    <?php
    $sqldis = $this->efiling_model->data_list_where('defect_letter','filing_no',$filingNo); 
    foreach ($sqldis as $row) {
        $dname = $row->defect_name;
        $footer = $row->defect_footer;
        $reg = $row->reg_name;
    }
    ?>
    <p>Sir/Madam,</p>
    <div style="text-indent:40px;"><?php echo $dname; ?></div>
    <ol>
        <?php
        $ii = 1;
        
        $obj =$this->db->query("select * from objection_details where filing_no='$filingNo' and status<>'No' order by objection_code");
        $rowval= $obj->result();
        if(!empty($rowval)){
            foreach ($rowval as $row) {
                $objectionCode = htmlspecialchars($row->objection_code);
                $status = htmlspecialchars($row->status);
                $statuscount = strlen($status);
                if ($statuscount == '6') {
                    $objName = htmlspecialchars($row->comments);
                } else {
                    $objName =$this->efiling_model->getColumn('master_objection','objection_name','id',$objectionCode);
                }
                if ($objName != "") {
                    ?>
                    <li><?php echo html_entity_decode(html_entity_decode(html_entity_decode($objName))); ?></li>
                    <?php
                    $objName = "";
                    $ii++;
                }
            }
        }
        ?>
    </ol>
    <div style="text-indent:40px;"><?php echo $footer; ?></div>
    <p>&nbsp;</p>
    <p style="text-align:right;"><b>(<?php echo $reg; ?>)</b><br> <?php if ($reg == 'Dr. Ashu Sanjeev Tinjan') { ?>Registrar <?php  } else {?>   <?php } ?></p>
</div>
</body>
</html>