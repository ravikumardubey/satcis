
<?php
$curYear = htmlspecialchars(date("Y"));
$curMonth = htmlspecialchars(date("m"));
$curDay = htmlspecialchars(date("d"));
$cur_date = "$curYear-$curMonth-$curDay";
$cur_date1 = "$curDay/$curMonth/$curYear";
$certify_copy_id=$cid;
$filingNo=$filing_no;


    $case_initialization = $this->db->query("select cp.id as certify_copy_id,cp.created_on,cp.party_type,cp.certify_number,
cp.party_ids,cp.year,cno.case_type,cno.filing_no,b.short_name,
 cno.case_no,cno.dt_of_filing,cno.pet_name,cno.res_name from 
sat_case_detail as cno inner join certified_copy as cp on cp.filling_no=cno.filing_no join master_case_type as 
b ON cno.case_type = b.case_type_code  where filing_no='$filingNo' AND certify_number='$certify_copy_id'");
    

    $row =$case_initialization->result();
    $party_name='';
    
    $party_ids=$row[0]->party_ids;
    $ids=explode(',', $party_ids);
    $filtered_array = array_filter($ids); 
    
    if($row[0]->party_type==1){
        $party_name='';
        $i=1;
        foreach($filtered_array as $vals){
            $row1= $this->efiling_model->data_list_where('additional_party','party_id',$vals);
            foreach($row1 as  $row2){
                $party_name .=$row2->pet_name.'A-'.$i.',';              
            }
            if($vals==1){
                $party_name .=$row[0]->pet_name.'(A-'.$i.'),';
            }
        }
        $i++;
    }
    
    if($row[0]->party_type==2){
        $party_name='';
        $i=1;
        foreach($filtered_array as $vals){
            $query1=$this->efiling_model->data_list_where('additional_party','party_id',$vals);
            foreach($query1 as $row2){
                $party_name .=$row2->pet_name.'(R-'.$i.'),';
            }     
            if($vals==1){
                $party_name .=$row-[0]>pet_name.'(R-'.$i.'),';
            }
            $i++;
        }
    }
    
    if($row[0]->party_type==3){
        $party_name='';
        foreach($filtered_array as $vals){
            $query1=$this->efiling_model->data_list_where('additional_party','party_id',$vals);
            foreach($query1 as $row2){
                $party_name .=$row2->pet_name.',';
            }
            if($vals==1){
                $party_name .=$row[0]->pet_name.',';
            }
        }
    }

    $case_details=$this->db->query("select amount from aptel_certify_account_details where certify_id='{$row[0]->certify_copy_id}'");
    $case_details =$case_details->result();
 //   print_r($case_details);die;
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>

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
        <style type="text/css">
        table tr th, table tr td {
            padding:5px;
        }
        .page_wrapper {
            margin: 0 auto;
            width: 100%;
            max-width: 1024px;
            background: #fff;
            padding: 15px;
            height: 100%;
        }
        html, body {
            height: 100%;
        }
        body {
            background: #e6e6e6;
            margin: 0;
        }
        .page_inner {
            position:relative;
            z-index: 1;
        }
        @media print {
         #testdiv {
            display: none;
         }
         .page_wrapper {
            padding: 0;
        }
        }
        </style>
    </head>
    <body style="font-size:16px; font-family: 'Times New Roman', Times, serif">
    <div class="page_wrapper">
    <div class="col-md-12 page_inner">
    <div id="testdiv" class="pr-hide"><a href="javascript:printPage();">
           </a>
    </div>
    <div style="position: relative;">
        <p style="text-align:center; font-family: Arial, Helvetica, sans-serif; font-size: 20px; margin: 0; line-height: 2.6;">
            <u><b>RECEIPT</b></u></p>
        <p style="text-align:center; font-size: 24px; margin: 0;"><u>Securities Appellate Tribunal</u></p>
        <p style="text-align:center; margin: 0;">Earnest House, House 107, NCPA Marg, Nariman Point, Mumbai, Maharashtra 400021</p>

        <div style="overflow: hidden;">
            <div style="float: left; width: 50%;">
                <span>
                    <?php
             
                    $val= substr($row[0]->filing_no,7);
                    $a=substr_replace($val ,"",-4);
                    $b= substr($val, -4);
                    $val= ltrim($a,0).'/'.$b;
                    //echo $row['case_no'];
                    if($row[0]->case_no==''){
						//$sdsdsd =ltrim($val,0);
                      echo "AL No.      :-  $val";
                    }else{
						
                        echo $row[0]->short_name . ' No.- '.ltrim(substr($row[0]->case_no,6,5),'0').'/'.substr($row[0]->case_no,11,4);
                        //echo $row['case_no_no'];
                    }
                    
               //     echo $this->efiling_model->fn_addition_party($db,$filingNo,'1');die;
                   
                    ?></span>
                 
                <p> <strong>Appellant/Petitioner Name    :- </strong> <?php echo $row[0]->pet_name.$this->efiling_model->fn_addition_party('',$filingNo,'1'); ?></p>
                <p><strong>Respondent  Name                 :-</strong><?php echo $row[0]->res_name.$this->efiling_model->fn_addition_party('',$filingNo,'2'); ?></p>
            </div>
                <span style="float: right; width: 50%; text-align: right;">
                    <p>DATE OF APPLICATION  :<?php echo  date("d-m-Y", strtotime($row[0]->created_on)); ?></p>
                </span>
            </div>
           
          
        </div>
		<p align="center"><strong>Apply for Certified Copy<strong></p>
       	<table datatable="ng" border="1" id="

" class="table table-striped table-bordered" cellspacing="0"  width="100%" style="width:100%;border-collapse:collapse">
                        <thead>
                            <tr>
                                <th align="center" style="white-space: nowrap;">Certified Copy Sr. No.</th>
                                <th align="center">Year</th>
                                <th align="center"  width="170">Amount Received</th>
                                <th width="left" align="left">Applied by</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php  $i=1;
                        $total=0;
                          foreach($case_details as $row1) {
                            $val= substr($row[0]->filing_no,5);
                            $a=substr_replace($val ,"",-4);
                            $b= substr($val, -4);
                            $val= $a.'/'.$b;
                            $total+=$row1->amount;
                            $i++ ; } ?>
                            <tr>
                               <td align="center"><?php echo $row[0]->certify_number; ?></td>
                               <td align="center"><?php echo $row[0]->year; ?></td>
                               <td align="center"><?php echo $total; ?>.00</td>
                               <td align="left"><?php echo substr($party_name,0,-1); ?></td>
                           </tr>
                        </tbody>
                    </table>

       				 
    		</div>
    		</div>
    		<img src="<?php echo base_url(); ?>asset/images/bg.jpg" style="position:absolute; left:0; right:0; top:0; bottom:0; margin:auto; z-index:0;">
    		</div>
    </body>
    </html>


