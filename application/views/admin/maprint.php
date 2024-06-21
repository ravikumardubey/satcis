<?php
$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 = "$curDay/$curMonth/$curYear";
$userdata=$this->session->userdata('login_success');
$user_id=$userdata[0]->id;
?>
<!DOCTYPE html>
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


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$curYear = date('Y');
$curMonth = date('m');
$curDay = date('d');
$dateprint = "$curDay/$curMonth/$curYear";
$curdate = "$curYear-$curMonth-$curDay";

$type = $doctype;
$filingNo = $filingNo;
$mano = $docFilingNo;
$docid = $pid;

if($type=='ma'){
$docd = $this->db->query("select * from ma_detail where  filing_no='$filingNo' AND  ma_id='$docid'");
$docdv = $docd->result();
    if(!empty($docdv)){
        foreach ($docdv as $val) {
            $addparty = $val->additional_party;
            $matter = $val->matter;
            $main_party= $val->main_party;
            $doc=$val->doc;
        }
    }else{
        echo "Record not found";die;
    }
}


if($type=='va'){
    $docd = $this->db->query("select * from vakalatnama_detail where  filing_no='$filingNo' AND  vakalatnama_no='$mano'");
    $docdv = $docd->result();
    foreach ($docdv as $val) {
        $addparty = $val->add_party_code;
        $matter = $val->matter;
        $total_fee = $val->total_fee;
        $main_party= $val->party_flag;
        $doc=isset($val->doc_id)?$val->doc_id:23;
    }
}

if($type=='oth'){
    $docd = $this->db->query("select * from ma_detail where  filing_no='$filingNo' AND  ma_filing_no='$mano'");
    $docdv = $docd->result();
    foreach ($docdv as $val) {
        $addparty = $val->additional_party;
        $matter = $val->matter;
        $total_fee = $val->total_fee;
        $main_party= $val->main_party;
        $doc=$val->doc;
    }
}


$ptype='2';
if($main_party=='P'){
    $ptype='1';
}
$partyid = explode(",", $addparty);
$pid = rtrim($addparty, ',');
$len = sizeof($partyid);
for ($k = 0; $k < $len; $k++) {
    if ($partyid[$k] == 1) {
        $sql = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingNo);
        foreach ($sql as $row) {
            $flass_type = 'A-';
            if ($ptype == 2) {
                $flass_type = 'R-';
            }
            if ($ptype == 2) {
                $mainparty = $row->res_name.'('.$flass_type.'1)';
            }
            if ($ptype == 1) {
                $mainparty = $row->pet_name.'('.$flass_type.'1)';
            }
        }
    }
}

if($addparty!='TP'){
    $sqladd1 = $this->db->query("select * from additional_party where  party_id IN($pid) order by partysrno");
    $sql_party11 = $sqladd1->result();
    $pet_name11 = '';
    foreach ($sql_party11 as $row) {
        $id = $row->party_id;
        $flass_type = 'A-';
    if ($ptype == 2) {
        $flass_type = 'R-';
    }
        $pet_name11 .= $row->pet_name.'('.$flass_type.$row->partysrno.'), ';
    }
}

if($addparty=='TP'){
    $sqladd1 =  $this->db->query("select petname from certified_copy_thirdparty where  filing_no='$filingNo'  ORDER BY  id DESC LIMIT 1");
    $sql_party11 = $sqladd1->result();
    $pet_name11 = '';
    foreach ($sql_party11 as $row) {
        $id = $row->petname;
        $pet_name11 = $row->petname.'(T- 1)';
    }
}

$sql22 = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingNo);
foreach ($sql22 as $row) {
    $petName = $row->pet_name;
    $resName = $row->res_name;
    $case_no = $row->case_no;
    $case_type = $row->case_type;
    $resName = $row->res_name;
    $fDate = $row->dt_of_filing;
    $pet_adv = $row->pet_adv;
    $case_no_con = '';
    if ($case_type != "") {
        $stQ = $this->efiling_model->data_list_where('master_case_type','case_type_code',$case_type);
        $case_type_short_name = $stQ[0]->short_name;
    }
}
$hscquery =  $this->efiling_model->data_list_where('master_document','did',$doc);
$natureName = $hscquery[0]->d_name;
?>

<body style="font-size:16px; font-family: 'Times New Roman', Times, serif">
<div id="testdiv" class="pr-hide"><a href="javascript:printPage();">
        <font color="red" size="1"><img src="<?php echo base_url(); ?>asset/images/print.gif" class="no-print" border='0'/></font></a>
</div>

<div style="position: relative;">
    <p style="text-align:center; font-family: Arial, Helvetica, sans-serif; font-size: 20px; margin: 0; line-height: 2.6;"><u><b>RECEIPT</b></u></p>
    <p style="text-align:center; font-size: 24px; margin: 0;"><u>Securities Appellate Tribunal</u></p>
    <p style="text-align:center; margin: 0;"> Lok Nayak Bhawan, Ministry of Finance, Department of Revenue 4th Floor, ‘A’ Wing, Khan Market, New Delhi, Delhi 110003</p>
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
                
        </div>
        <div style="float: right; width: 50%; text-align: right;">
            <p>DATE OF FILING : <?php echo $dateprint; ?></p>
             
        </div>
    </div>
    <p style="margin: 0;">CASE TYPE:- <?php echo $case_type_short_name; ?></p>
    <p><b>Appellant Name :- <?php echo $petName.$this->efiling_model->fn_addition_party($filingNo,'1');?></b></p>
    <p><b>Respondent Name :- <?php echo $resName.$this->efiling_model->fn_addition_party($filingNo,'2');?></b></p>
    
    <?php
    if ($type == 'ma') {
        $mano = trim($mano, ' ');
        $no = 'MF No';
        $year = ' Year';
        $nature = ' Nature';
        $mdate = ' Date';
        $total = " Fee";
        $stdate = $this->db->query("select dt_of_filing from ma_detail where filing_no='$filingNo' and ma_filing_no='$mano'");
        $stdate=$stdate->result();
        $date = $stdate[0]->dt_of_filing;
        $date1 = explode('-', $date);
    }

    if ($type == 'va') {
        $mano = trim($mano, ' ');
        $no = 'MF No';
        $year = 'Year';
        $nature = ' Nature';
        $mdate = ' Date';
        $total = " Fee";
        $stdate = $this->db->query("select entry_date from vakalatnama_detail where filing_no='$filingNo' and vakalatnama_no='$mano'");
        $stdate=$stdate->result();
        $date = $stdate[0]->entry_date;
        $date1 = explode('-', $date);
    }
    
    if ($type == 'oth') {
        $mano = trim($mano, ' ');
        $no = 'MF No';
        $year = 'Year';
        $nature = ' Type Of Recipt';
        
        
    }

    ?>
    <table border="1" cellpadding="3" style="width:100%; border-collapse: collapse;">
        <tr>
            <td><b> <?php echo $no; ?></b></td>
            <td><b> <?php echo $year; ?></b></td>
            <td><b> <?php echo $nature; ?></b></td>
            <td><b> Filed By </b></td>
        </tr>
        <tr>
            <td><?php echo $mano; ?></td>
            <td><?php echo $date1[0]; ?></td>
            <td><?php echo $natureName; $natureName = ""; ?></td>
             <td><?php if($mainparty !='') { $mainparty = $mainparty.','; } echo $mainparty.$pet_name11; ?></td>
        </tr>
    </table>

    <img src="<?php echo base_url(); ?>asset/images/bg.jpg" style="position:absolute; left:0; right:0; top:0; bottom:0; margin:auto; z-index: -1;">
</div>
</body>
</html>