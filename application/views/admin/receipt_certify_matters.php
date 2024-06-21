<?php
$certify_copy_id=$cid;
$filingNo=$filing_no;
$query_str="select cp.id as certify_copy_id,cno.filing_no,cp.created_on,cp.certify_number,cno.case_no,cno.dt_of_filing,cno.pet_name,cno.res_name 
from sat_case_detail as cno inner join certified_copy as cp on cp.filling_no=cno.filing_no where cp.filling_no='$filingNo' AND cp.certify_number='$certify_copy_id'";
$query=$this->db->query($query_str);
$row1=$query->result();

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
    <img src="<?php echo base_url(); ?>asset/images/bg.jpg" style="position:absolute; left:0; right:0; top:0; bottom:0; margin:auto; z-index:0;">
    <body style="font-size:16px; font-family: 'Times New Roman', Times, serif">
    <div style="position: relative;">
        <p style="text-align:center; font-family: Arial, Helvetica, sans-serif; font-size: 20px; margin: 0; line-height: 2.6;">
            <u><b>RECEIPT</b></u></p>
        <p style="text-align:center; font-size: 24px; margin: 0;"><u>Securities Appellate Tribunal</u></p>
        <p style="text-align:center; margin: 0;">Earnest House, House 107, NCPA Marg, Nariman Point, Mumbai, Maharashtra 400021</p>
        <div style="overflow: hidden;">
            <div style="float: left; width: 50%;">
                <span>
                    <?php
                    $val= substr($row1[0]->filing_no,5);
                    $a=substr_replace($val ,"",-4);
                    $b= substr($val, -4);
                    $val= $a.'/'.$b;
                    echo "AL No.:- $val";
                    ?></span>                
                <p> <strong>Appellant/Petitioner Name    :- </strong> <?php echo $row1[0]->pet_name; ?><?php echo $this->efiling_model->fn_addition_party($filingNo,'1'); ?></p>
                <p><strong>Respondent  Name                 :-</strong><?php echo $row1[0]->res_name; ?><?php echo $this->efiling_model->fn_addition_party($filingNo,'2'); ?></p>
            </div>
             <div>
                <span style="float: right; width: 50%; text-align: right;">
                    <p>DATE OF FILING : <?php echo  date("d-m-Y", strtotime($row1[0]->created_on)); ?></p>
                </span>
            </div>             
        </div>
        
       	<table datatable="ng" border="1" id="examples" class="table table-striped table-bordered" cellspacing="0"  width="100%" style="width:100%;border-collapse:collapse">
                        <thead>
                            <tr>
                                <th scope="col">Sr. No</th>
                                <th scope="col">AL No.</th>
                                <th scope="col">CFR No</th>
                                <th scope="col">No.Page</th>
                                <th scope="col">No.Set</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $total=0;
                        $certify_copy_id=$row1[0]->certify_copy_id;
                        $case_details="select * from certified_copy_matters where certify_copy_id='$certify_copy_id'";
                        $query=$this->db->query($case_details);
                        $rowva=$query->result();
                        $i=1;
                        foreach($rowva as $row) {
                            $val= substr($row->filling_no,7);
                            $a=substr_replace($val ,"",-4);
                            $b= substr($val, -4);
                            $val= $a.'/'.$b;
                            $total+=$row->total;
                            ?>
                            <tr>
                               <td align="center" scope="row"><?php echo $i; ?></td>
                               <td align="center"><?php echo $val; ?></td>
                               <td align="center"><?php echo   $certify_copy_id; ?></td>
                               <td align="center"><?php echo $row->no_page; ?></td>
                               <td align="center"><?php echo $row->no_set; ?></td>
                               <td align="right"><?php echo $row->total; ?>.00</td>
                           </tr>
                       		<?php $i++;} ?>
                       		<tr> 
                       		   <td colspan="5" align="right"><strong>Total</strong></td>
                               <td align="right"><strong><?php echo $total; ?>.00</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <table border="0" style="width:100%;">
                        <tr>
                            <td style="width:100%" valign="top">
                                <p><b>&nbsp;</b></p>
                                <p><b>Amount Received :- <?php echo $total; ?>.00</b></p>
                            </td>
                            <td valign="top" style="text-align: center;">
                                <p>&nbsp</p>                               
                            </td>
                        </tr>
                    </table>       				
    	
    		 <table border="0" style="width:100%;">
                        <tr>
                            <td style="width:100%" valign="top">
                                <p><b>&nbsp;</b></p>
                                <p><b>&nbsp;</b></p>
                            </td>
                            <td valign="top" style="text-align: center;">
                                <p>COUNTER&nbsp;ASSISTANT</p>
                                <img src="<?php echo base_url(); ?>asset/images/stamp.jpg" style="width:100px;">
                            </td>
                        </tr>
                    </table>
    		
    	</div>
    </body>
    
</html>


