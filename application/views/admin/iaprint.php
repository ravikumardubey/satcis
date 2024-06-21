<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt</title>
    <script>
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

<body style="font-size:16px; font-family: 'Times New Roman', Times, serif">

<?php

error_reporting(0);

$curYear = date('Y');
$curMonth = date('m');
$curDay = date('d');
$dateprint="$curDay/$curMonth/$curYear";
$curdate="$curYear-$curMonth-$curDay";
$iano=$iano;
$iano=rtrim($iano,",");
$iaNo1=explode(',',$iano);
$iaYear=$year;
$iaYear=rtrim($iaYear," ");
$filingNo = '';
$filedby='';
    for($i=0;$i<=sizeof($iaNo1)-1;$i++){
		if($iaNo1[0]!=""){
    		$sqlia="select * from satma_detail where ia_no='$iaNo1[$i]' and SUBSTR(ia_filing_no,12,4)='$iaYear'";
    		$query=$this->db->query($sqlia);
    		$data = $query->result();
    		foreach ($data as $row){
    		    $filingNo=$row->filing_no;
    		    $filedby=$row->filed_by;
    		}
	    }
	}
	
	$case_no='';
	$sql22="select * from sat_case_detail where filing_no='$filingNo'";
	$query=$this->db->query($sql22);
	$data = $query->result();
	foreach ($data as $row){
     	$petName=$row->pet_name;
    	$resName=$row->res_name;
     	$case_no=$row->case_no;
    	$case_type=$row->case_type;
     	$resName=$row->res_name;
     	$fDate=$row->dt_of_filing;
     	$pet_adv=$row->pet_adv;
    	if($case_no!=""){
    		$case_numaa = substr($case_no,4,7);
    		$case_num1aa=ltrim($case_numaa,0);
    		$case_year1aa = substr($case_no,11,4);
    	}
    	if($case_type!=""){
    			$stQ = "select short_name from master_case_type where case_type_code ='$case_type'";
    			$query=$this->db->query($stQ);
	            $data = $query->result();
	            $case_type_short_name=$data[0]->short_name;
    	}if($filedby==1){
    			$filedbyName=$petName;
    	}if($filedby==2){
    			$filedbyName=$resName;
    	}
 }
?>
<div style="position: relative;">
    <p style="text-align:center; font-family: Arial, Helvetica, sans-serif; font-size: 20px; margin: 0; line-height: 2.6;">
    <u><b>RECEIPT</b></u></p>
    <p style="text-align:center; font-size: 24px; margin: 0;"><u>Securities Appellate Tribunal</u></p>
    <p style="text-align:center; margin: 0;">Earnest House, House 107, NCPA Marg, Nariman Point, Mumbai, Maharashtra - 400021</p>
    <div style="overflow: hidden;">
        <div style="float: left; width: 50%;">
            <p>
                <?php
                if ($case_no != "") {
                    $case_numaa = substr($case_no, 4, 7);
                    $case_num1aa = ltrim($case_numaa, 0);
                    $case_year1aa = substr($case_no, 11, 4);
                    echo $case_type_short_name . '/' . $case_num1aa . '/' . $case_year1aa;
                } else {
                    $filing_No = substr($filingNo, 5, 6);
                    $filing_No = ltrim($filing_No, 0);
                    $filingYear = substr($filingNo, 11, 4);
                    echo "AL No. :- $filing_No/$filingYear";
                }
                ?>
            </p>
            <p style="margin: 0;">CASE TYPE:- <?php echo $case_type_short_name; ?></p>
            <p><b>Appellant Name :- <?php echo $petName.$this->efiling_model->fn_addition_party($filingNo,'1'); ?></b></p>
            <p><b>Respondent Name :- <?php echo $resName.$this->efiling_model->fn_addition_party($filingNo,'2');; ?></b></p>
        </div>
        <div style="float: right; width: 50%; text-align: right;">
            <p>DATE OF FILING : <?php echo $dateprint; ?></p>
            <img src="<?php echo base_url(); ?>qrcodeci/<?php echo $image; ?>" height="100px"></img>
        </div>
    </div>
   
    <table border="1" cellpadding="3" style="width:100%; border-collapse: collapse;">
       <tr>
    	   <th>IA No</th>
    	   <th>IA Year</th>
    	   <th>IA Nature</th>
    	   <th>Applicant</th>
	   </tr>
	   <?php 
	    $ia_requestt = rtrim($iano,', ');
	    $sqlia="select * from satma_detail where ia_no in ($ia_requestt) and filing_no = '".$filingNo."' and status = 'P' ";
	    $query=$this->db->query($sqlia);
	    $data = $query->result();
	    foreach ($data as $row){
		   $filed_by1=$row->filed_by;
		   $filingNo=$row->filing_no;
		   $date=$row->entry_date;
		   $ianature=$row->ia_nature;
		   $filedby=$row->additional_party;
	
		   $filed_byww=explode(',',  $filedby);
		   $filedby= ltrim(rtrim($filedby,', '),',');
		   $filedbyName = '';
			if (in_array("1", $filed_byww)){
    			if($filed_by1=='2'){
    			    $filedbyName.=$resName.' (Res.-1),';
    			}
			}
			$filedbyName='';
			if($filedby != '') {
            $sqladd1 = "select * from additional_party where  party_id IN($filedby) order by partysrno";
            $query=$this->db->query($sqladd1);
            $data = $query->result();
			$i=1;
		    $party_flag='';	
		    foreach ($data as $row1) {
				    $id = $row1->party_id;
				    $flass_type = 'App.-';
				    if ($row1->party_flag == 2) {
				        $flass_type = 'Res.-';
				    }
				    $filedbyName .= $row1->pet_name.'('.$flass_type.$row1->partysrno.'), ';
				}
			}
			if($filedbyName=='' && $filed_by1=='1'){
			     $filedbyName=$petName. ' (App.-1)';
			}
			if($ianature==12){
				 $natureName=$row['matter'];
			}else{
			$stQ = "select nature_name from moster_ma_nature where nature_code= '$ianature'";
			$query=$this->db->query($stQ);
			$data = $query->result();
			$natureName=$data[0]->nature_name;
		} ?>
		 <tr>
		 <td><?php echo $row->ia_no; ?></td>
		 <td><?php echo $iaYear; ?></td>
		 <td><?php echo $natureName; $natureName="";?></td>
		 <td><?php echo $filedbyName ;?></td>
		 </tr>
		<?php } ?>
    </table>
    <table border="0" style="width:100%;">
        <tr>
    	   <td style="width:100%;" valign="top">
          <?php
                 $date_fing = date('Y-m-d');
                 $stnameaccount_details = "select * from sat_account_details where filing_no = '$filingNo'
                 and  dt_of_filing = '$date_fing' AND fee_type='IA'";
                 $query=$this->db->query($stnameaccount_details);
                 $data = $query->result();
                 $total_feee = 0;        
                 if(!empty($data)) { 
                     foreach ($data as $value) {
                       $total_feee =  $total_feee +  $value->amount;
                    }
                 }             
                 ?>
                <p><b>Amount Received :- RS. <?php echo $total_feee; ?></b></p>
    	   </td>
    </table>
    <img src="<?php echo base_url(); ?>asset/images/bg.jpg" style="position:absolute; left:0; right:0; top:0; bottom:0; margin:auto; z-index: -1;">
</div>
</body>
</html>
