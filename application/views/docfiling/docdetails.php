<?php
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$userdata=$this->session->userdata('login_success');
$userid=$userdata[0]->id;
$token= $this->efiling_model->getToken(); error_reporting(0);?>
<div class="content" style="padding-top:0px;">
<div class="card"  id="dvContainer" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px;  border-top-left-radius: 0px;">
    <form action="<?php echo base_url(); ?>/iaaction" target="_blank" class="wizard-form steps-basic wizard clearfix" id="finalsubmit" autocomplete="off" method="post" accept-charset="utf-8">
       <div class="content clearfix" id="mainDiv1">
      <!-- <FIELDSET> 
        	<legend><b>IA Details</b></legend>
        	<table datatable="ng" id="examples"  class="table table-striped table-bordered" cellspacing="0"  width="100%">
                <thead>
                    <tr><th>SR.No.</th>                  
                        <th>IA Number</th>
                        <th>IA Nature</th>
                        <th>AL No.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i=1;
                    foreach($iadetail as $row) {
                      //  print_r($row);die;
                        if(!empty($row)){
                            $ia_fil_nom=$row->ia_filing_no;
                            $filing_no=$row->filing_no;
                            $aDetail= $this->efiling_model->data_list_where('moster_ia_nature','nature_code',$row->ia_nature);
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                         <td><?php echo ltrim(substr($ia_fil_nom,5,6),0).'/'.substr($ia_fil_nom,11,4); ?></td>
                        <td><?php echo htmlspecialchars($aDetail[0]->nature_name);?></td>
                        <td><?php echo ltrim(substr($filing_no,5,6),0).'/'.substr($filing_no,11,4); ?></td>
                    </tr>
                <?php  $i++;} 
                    }?>
                </tbody>
      	    </table>
        </FIELDSET>
 --> 

 		<FIELDSET>
            <LEGEND><b class="fa fa-upload">&nbsp;&nbsp;Uploaded Documents Lists :</b></legend>
            <table id="example" class="display" cellspacing="0" border="1" width="100%">
                <thead>                    
                    <tr style="background-color: #dec7c6">
                        <th style="width:5%">Sr.No.</th>          
                        <th style="width:15%">Party Type</th>                    
                        <th style="width:30%">Document Type</th>                    
                        <th style="width:5%">No of Pages</th> 
                        <th style="width:60%">Document Name</th>                                    
                        <th style="width:10%">View Document </th>
                    </tr>
                </thead>
                <tbody>
                <?php

                if(@$docs) {
                    $sr=1;
                    foreach ($docs as $docs) {
                        $document_filed_by = $docs->document_filed_by;
                        $document_type = $docs->document_type;
                        $no_of_pages = $docs->no_of_pages;
                        $file_id = $docs->id;
                        $update_on = $docs->update_on;
                        $doc_name= $docs->doc_name;
                        echo'<tr>
                                <td>'.$sr.'</td>
                                <td>'.$document_filed_by.'</td>
                                <td>'.$document_type.'</td>
                                <td>'.$no_of_pages.'</td>
                                <td>'.$doc_name.'</td>
                                <td id="updDocId"><a href="javascript:void();" tagId="'.$file_id.'"><i class="fa fa-eye"></i></a></td>
                        </tr>';
                        $sr++;}
                }
                else echo'<tr><td colspan=5 class="text-danger text-center h3">No document uploaded!</td></tr>';
                ?>
                </tbody>
            </table>
        </fieldset>
	<?= form_close();?>    
 </div>

</div>

 <div class="modal fade bd-example-modal-lg" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
        <!-- Modal content-->
            <div class="modal-content">
             <form action="certifiedsave.php" method="post">
                  <div class="modal-header" style="background-color: cadetblue;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div id="viewsss">
                  </div>
              </form>
          </div>
     </div>
</div>  

<!-- Display Uploaded file PDF -->
<div class="modal fade" id="updPdf" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="min-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="frame">                   
                <iframe style="width: 100%; height: 560px" id="frameID" src=""></iframe>
            </div>
        </div>
    </div>
</div>        
<script>
$('#updDocId > a').click(function(e){
    e.preventDefault();
    var updId='', href='';
    updId=$(this).attr('tagId');
    $.ajax({
        type: 'post',
        url: base_url+'uploaded_docs_displayforia',
        data: {docId: updId},
        dataType: 'json',
        success: function(rtn){
            debugger;
            if(rtn.error == '0'){
                href=base_url+rtn.data;   
                $('#frameID').attr("src", href + "#view=VFit" + "&toolbar=0" + "&navpanes=0");   
            }
            else $.alert(rtn.error);
        }
    });
    $('#updPdf').modal('show');
});
</script>
<?php $this->load->view("admin/footer"); ?>