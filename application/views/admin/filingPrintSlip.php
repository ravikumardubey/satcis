<html>
<head><title>Efiling Invoice</title></head>
<body class="b1" id="testdiv" style="font-family: Arial; font-size: 14px;">
<form name="form2" method="post" action="">
    <div id="btnPrint" class="pr-hide"><a onclick = "startDownload()" target="_blank" href="<?php echo base_url(); ?>payslip/<?php echo $filing_no; ?>">
		 <font color="red" size="1"><img  src="<?php echo base_url(); ?>asset/images/print.gif" class="no-print" border='0'/></font></a>
    </div>
    <div class="container margin-top-30 defect-pattern">
        <?php
        error_reporting(0);
        $curYear = date('Y');
        $curMonth = date('m');
        $curDay = date('d');
        $dateprint = "$curDay/$curMonth/$curYear";
        $curdate = "$curYear-$curMonth-$curDay";
        $filingNo = isset($_REQUEST['filingno'])?$_REQUEST['filingno']:$filing_no;
        $fno=$filingNo;
        $iaYear = isset($_REQUEST['year'])?$_REQUEST['year']:'';
        $sql22 = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingNo);
        foreach($sql22 as $row) {
            $petName = $row->pet_name;
            $resName = $row->res_name;
            $case_no = $row->case_no;
            $case_type ='1';
            $resName = $row->res_name;
            $fDate = $row->dt_of_filing;
            $pet_adv = $row->pet_adv;
            if ($case_no != "") {
                $case_numaa = substr($case_no, 4, 7);
                $case_num1aa = ltrim($case_numaa, 0);
                $case_year1aa = substr($case_no, 11, 4);
            }
            if ($case_type != "") {
                $stQ = $this->efiling_model->data_list_where('master_case_type','case_type_code',$case_type);
                $case_type_short_name = $stQ[0]->short_name;
            }
            $apealtype=$row->apeal_type;
            $ipenalty=$row->ipenalty;
            $rimpugnedorder=$row->rimpugnedorder;
            $iorderdate=$row->iorderdate;
            $opauthority=$row->opauthority;
            $iordernumber=$row->iordernumber;
            $delayinfiling=$row->delayinfiling; 
            $act=$row->act; 
            ?>
            <div class="row ">
                <div class="col-sm-12 fl-center"> <b><center><u>RECEIPT</u>  <center></b></div>
                <div class="col-sm-12 fl-center"><b><center><u>SECURITIES APPELLATE TRIBUNAL</u> <center> </b> </div>
                <div class="col-sm-12"><center>Earnest House, House 107, NCPA Marg, Nariman Point, Mumbai, Maharashtra 400021</center></div>
            </div>
            <div class="row " style="margin-top: 15px;">
                <div class="col-sm-6">
                    <?php
                    $filing_No = substr($filingNo, 5, 6);
                    $filing_No = ltrim($filing_No, 0);
                    $filingYear = substr($filingNo, 11, 4);
                    echo " $filing_No/$filingYear";
                    ?>
                </div>
                <div class="col-sm-6 text-right" style="float: right;">
                    <div> Date: <?php echo $dateprint; ?></div>
                    <img src="<?php echo base_url(); ?>qrcodeci/<?php echo $image; ?>" height="100px"></img>
                </div>
            </div>
                <div class="row ">
                    <div class="col-sm-6">
                        Case Type:-<?php echo $case_type_short_name; ?>
                    </div>
                </div>
            <div class="row ">
                <div class="col-sm-6">
                    Appellant Name:-  <?php echo $petName; ?> <?php echo $this->efiling_model->fn_addition_party($fno,'1');?>
                </div>
            </div>
            <div class="row ">
                <div class="col-sm-6">
                    Respondent Name:- <?php   echo $resName;  ?> <?php echo $this->efiling_model->fn_addition_partyr($fno,'2');?>
                </div>
            </div>
            <div class="row" style="margin-bottom: 10px;margin-top: 20px;">
                <table class="table" style="width:100%; font-size: 12px;border: 5px;" cellpadding="0" cellspacing="0">
                    <tr><td colspan="4" align="center" style="padding-bottom: 10px"><font color="#510812" size="3"><b><center>Regulator Details<center></b></font></td> </tr>
                    <tr>
                        <th style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: left; padding: 10px 0;">Apeal Type</th>
                        <th style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: left;">ACTS/ Rules</th>
                        <th style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: left;">Order Passing Authority</th>
                        <th style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: left;">Imposition of Penalty </th>
                        <th style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: left;">Impugned Order Number</th>
                        <th style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: left;">Impugned Order Date</th>
                        <th style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: left;">Date of receipt of Impugned Order</th>
                        <th style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: left;">Delay in Filing</th>
                    </tr>
                    <?php
                    $act_name='-';
                    $order_passing_authority='-';
                    if($apealtype!='') {
                        $actde = $this->efiling_model->data_list_where('master_act','id',$act);
                        if (!empty($actde)) {
                            $act_name = $actde[0]->act_full_name;
                        }
                        
                        $optde = $this->efiling_model->data_list_where('master_regulator','id',$opauthority);
                        if (!empty($optde)) {
                            $order_passing_authority = $optde[0]->order_passing_authority;
                        }
                        
                        ?>
                        <tr>
                            <td style="padding: 10px 0;"><?php echo htmlspecialchars($apealtype); ?></td>
                            <td style="padding: 10px 0;"><?php echo htmlspecialchars($act_name); ?></td>
                            <td style="padding: 10px 0;"><?php echo htmlspecialchars($order_passing_authority); ?></td>
                            <td style="padding: 10px 0;"><?php echo htmlspecialchars($ipenalty); ?></td>
                            <td style="padding: 10px 0;"><?php echo htmlspecialchars($iordernumber); ?></td>
                            <td style="padding: 10px 0;"><?php echo htmlspecialchars($iorderdate); ?></td>
                            <td style="padding: 10px 0;"><?php echo htmlspecialchars($rimpugnedorder); ?></td>
                            <td style="padding: 10px 0;"><?php echo htmlspecialchars($delayinfiling); ?></td>
                        </tr>
                        <?php
                } else {?>
                     <tr> 
                        <td style="padding: 10px 0;">- </td>
                        <td style="padding: 10px 0;">- </td>
                        <td style="padding: 10px 0;">- </td>
                        <td style="padding: 10px 0;">- </td>
                        <td style="padding: 10px 0;">-</td>
                        <td style="padding: 10px 0;">-</td>
                        <td style="padding: 10px 0;">-</td>
                     </tr>
               <?php  }
               $sqlia = $this->efiling_model->data_list_where('satma_detail','filing_no',$filingNo);
               $total = count($sqlia);
               ?>
                </table>
            </div>
            <div class="row ">
                <div class="col-sm-6"><b>No. of MA :-  <?php echo htmlspecialchars($total); ?> </b></div>
            </div>
            <?php
            $sum = 0;
            $resum = 0;
            $lessrs = 0;
            $case_t = $this->efiling_model->data_list_where('sat_account_details','filing_no',$filingNo);
            foreach ($case_t as $row) {
                $fee_amount = $row->fee_amount;
                $amount = $row->amount;
                $court_fee = $row->court_fee;
                $ia_fee = $row->ia_fee;
                $other_fee = $row->other_fee;
                $sum = $fee_amount + $ia_fee + $other_fee;
                $resum = $resum + $amount;
                if ($sum > 0) {
                    $lessrs = $sum - $resum;
                }
            }
            ?>
            <br>
            <div class="row " style="margin-bottom: 10px;margin-top: 20px;">
                <div class="col-sm-6"><b>Amount Received:- <?php echo htmlspecialchars($resum); ?> </b></div>
            </div>
            <?php } ?>
        <div class="pagebreak">
        </div>
        <img src="<?php echo base_url();?>asset/images/bg.jpg" style="position:absolute; left:0; right:0; top:0; bottom:0; margin:auto; z-index: -1;">
</form>
</body>

 <tr> <td colspan="6" align="right" > <p style="font-size: 9px;">Computer Generated Reciept No Need of Signature*******</p> </td> </tr>
 <tr> <td colspan="6" align="right"><p style="font-size: 9px;"><?php echo date("l jS \of F Y h:i:s A"); ?></p></td> </tr>
</html>