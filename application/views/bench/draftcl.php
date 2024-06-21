<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<script >
function printPage()
{
window.print();
}
</script>
<html >
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>securities appellate tribunal</title>
	<!---<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet">--->
	<link href="<?=base_url('asset/admin_css_final/styles.css')?>" rel="stylesheet">
	<link href="<?=base_url('asset/admin_css_final/bootstrap.min.css')?>" rel="stylesheet">
	<link href="<?=base_url('asset/admin_css_final/bootstrap_limitless.min.css')?>" rel="stylesheet">
	<link href="<?= base_url('asset/APTEL_files/jquery-confirm.css');?>" rel="stylesheet">	
	<link href="<?=base_url('asset/admin_css_final/layout.min.css')?>" rel="stylesheet">
	<link href="<?=base_url('asset/admin_css_final/components.min.css')?>" rel="stylesheet">
	<link href="<?=base_url('asset/admin_css_final/colors.min.css')?>" rel="stylesheet">
    <link href="<?= base_url('asset/APTEL_files/fontawesome.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('asset/admin_css_final/customs.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('asset/admin_css_final/dataTables.bootstrap4.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('asset/admin_css_final/buttons.dataTables.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('asset/admin_css_final/jquery.dataTables.min.css'); ?>" rel="stylesheet">	
</head>	
<body > 
	<div class="container"> 
	    <div id="testdiv" class="printtext" style="visibility: visible;"><a href="javascript:printPage();">   <font size="4" color="red">    Print</font> </a></div>
    <table cellspacing="1" cellpadding="1" border="1" width="95%" class="fixed header_content" align="center" style="text-align:center" bgcolor="#F0F8FF">
        <tr>
            <td colspan="10" align="center" style="text-align:center"><b> Daily Causelist</b> </td>
        </tr>
		<tr>
            <td colspan="10" align="center" style="text-align:center"><b> Securities Appellate Tribunal,Mumbai, Maharashtra 400021 </b> </td>
        </tr>
		<tr>
            <td colspan="10" align="center" style="text-align:center"><b> Causelist for Date: <?=date('d/m/Y',strtotime($listing_date));?> </b> </td>
        </tr>
        <tr>
            <td colspan="10" align="center" style="text-align:center"><b><u></u></b>
        </tr>
        <!--tr>
            <td colspan="10" align="center" style="color:red; text-align:center"><b><u><b>(There will be no sitting of this Bench.Next date may be obtained from Court Master at 11.00 A.M.)</b><br><br>                    </u></b></td>
        </tr-->
<?php		if(!empty($benches)): ?>

<?php
foreach($benches as $row1): 
extract($row1);    
$flag=1;
 ?>

<tr align="left">
<!--td align="center"><?//=$bench_no?></td-->
<td colspan="5" align="center" size="2"><font color="blue" size="2"><b>COURT NO: <?=$court_no?></b>
<b>    TIME: <?= date('h:i a ', strtotime($from_time)); ?></b></font></td></tr>
<tr>

<td colspan="2" align="center"><font color="blue" size="2"><b><?php echo "CORAM:" ;?></b></font></td>
<td  align="left" nowrap="nowrap" size="2"><font color="blue" size="2">
<?php
	
	$benchJudgeData=$this->db->get_where('bench_judge',['from_list_date'=>$from_list_date,'from_time'=>$from_time])->result_array();
	//print_r($benchJudgeData); die;
	foreach($benchJudgeData as $benchJudgeRow):
	//extract($benchJudgeRow);
		$judge_code= $benchJudgeRow['judge_code'];
		$query = $this->db->select('judge_name')->get_where('master_judge', ['judge_code' => $judge_code])->row_array();
			
			$judge=$query['judge_name'];
			  
			echo "<b>HON'BLE      ".$judge.'</b><br>';
			
		endforeach;
?>
</font></td>

<td align="left" nowrap="nowrap"><font color="blue" size="2"><b>PRESIDING:<?php $presiding=$row1['presiding'];
$query = $this->db->select('judge_name')->get_where('master_judge', ['judge_code' => $presiding])->row_array(); echo $query['judge_name'];  ?></b></font></td>

</tr>

        <tr>
            <th>S.No.</th>
            <th>Case No/Diary Number.</th>
            <th>Party Name</th>
            <th>Name of Counsel For Appellant(Mr./Mrs.)</th>
            <th>Name of Counsel For Respondent(Mr./Mrs.)</th>
        </tr>

<?php
  $i=1;
	$purposeData=$this->db->select("DISTINCT(purpose)")->get_where('cause_list',['listing_date'=>$listing_date])->result_array();
			//print_r($this->db->last_query());  //die;
			//print_r($purposeData); //die;
			foreach($purposeData as $purpose):
			//extract($benchJudgeRow);
			$purpose_code= $purpose['purpose'];
			$query = $this->db->select('purpose_name')->get_where('master_purpose', ['purpose_code' => $purpose_code])->row_array();
			//print_r($this->db->last_query());  //die;
			//print_r($query); die;
				
				$purpose=$query['purpose_name'];
			 
		//	echo "<td><b>  ".$purpose.'</b></td><br>';
			 
		
	
?>

        <?php
		$caseallo=$this->db->get_where('cause_list',['listing_date'=>$listing_date, 'bench_nature'=>$bench_nature, 'purpose'=>$purpose_code, ])->result_array();
		//echo $purpose=$caseallo['purpose']; die;
	//	print_r($this->db->last_query());  die;
        if(!empty($caseallo) && is_array($caseallo))
		{
           ?>
		 
		
			<tr>
<td  colspan="5" align="left" nowrap="nowrap" size="2"><font color="blue" size="2">PURPOSE:<?php echo $purpose; ?></font></td>
</tr>
			
		<?php	
           foreach($caseallo as  $row){ 
		  // echo $row['filing_no']; die;
               $dd =$this->db->get_where('sat_case_detail',['filing_no'=>$row['filing_no']])->row_array();
			   //print_r($this->db->last_query());  die;
			   //print_r( $dd); die;
               $petName=$dd['pet_name'];
               $resName=$dd['res_name'];
               $fil_no=$row['filing_no'];
			  // $purpose=$row['purpose'];
			   $dfr=$diary=substr($fil_no,6,9);
              
               //applicant advocat
               $advnames='';
               if(!empty($dd['pet_adv'])){
				 //  echo "jhgjhjh"; die;
                   $advname = $this->db->get_where('master_advocate',['adv_code'=>$dd['pet_adv']])->row_array();
				   //print_r($this->db->last_query());  die;
                  $pet_adv= $advname['adv_name'];
               }
               
               //Respondent advocat
               $res_adv='';
			  
               if(!empty($dd)&& $dd['res_adv']>0){
                   $advname = $this->db->get_where('master_advocate',['adv_code'=>$dd['res_adv']])->row_array();
				   
				   //print_r($this->db->last_query());  die;
				   if(!empty($advname)){
                   $res_adv= $advname['adv_name'];
				   }
               }
               
               
               
               ?>
			 
            
          
              <tr>
                <td><b><?php echo $i; ?></td>
                <td><center><?php echo $dfr;
				?></center>
                    <!--span><center>In</center></span-->
                    <?php ?>
                </td>
                   <td><?php echo $petName; ?><span style="color:red"><b>Vs </b></span><?php echo $resName; ?></td>
                <td><?php echo $pet_adv; ?></td>
                <td><?php echo $res_adv; ?></td>
              </tr>
          <?php $i++;  } 
        }
		/*else{
            echo "Cases not listed for this bench";//die;
        } */
		
		//1931510750882022
		?>
		


 <?php endforeach; endforeach;  endif; ?>

	</table>
	   
    <table cellspacing="1" cellpadding="1" border="0" width="95%" class="fixed" align="center" bgcolor="#F0F8FF">
        <tr>
            <td align="right" colspan="5" style="text-align:right">
                </br></br></br></br>Registrar</br>SAT</td>
        </tr>
    </table>
    </div> 
</body>

<script type="text/javascript">
	var base_url='', salt='';
	base_url ='<?php echo base_url(); ?>';
    salt='<?php echo hash("sha512",strtotime(date("d-m-Y i:H:s"))); ?>';
</script>
</html>
