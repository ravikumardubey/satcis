<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepsia");
$salt=$this->session->userdata('iasalt');
$token= $this->efiling_model->getToken();

$tab_no='';
$type='';
$totalia='';
$anx='';
$filing_no='';
$ianature='';
$iapartys='';
$partyType='';
$iano=0;
$selected_radio1='';
if($salt!=''){
    $basicia= $this->efiling_model->data_list_where('temp_iadetail','salt',$salt);
    if(!empty($basicia)){
        $anx=isset($basicia[0]->totalanx)?$basicia[0]->totalanx:'';
        $type=isset($basicia[0]->type)?$basicia[0]->type:'';
        $filing_no=isset($basicia[0]->filing_no)?$basicia[0]->filing_no:'';
        $tab_no=isset($basicia[0]->tab_no)?$basicia[0]->tab_no:'';
        $totalia=isset($basicia[0]->totalia)?$basicia[0]->totalia:'';
        $ianature=isset($basicia[0]->ianature)?$basicia[0]->ianature:'';
        $iano=isset($basicia[0]->totalia)?$basicia[0]->totalia:'';
        $iapartys=isset($basicia[0]->iapartys)?$basicia[0]->iapartys:'';
        $selected_radio1=isset($basicia[0]->party_flag)?$basicia[0]->party_flag:'';
        $partyType=isset($basicia[0]->partyType)?$basicia[0]->partyType:'';
    }
}
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script src="<?=base_url('asset/admin_js_final/customs.js')?>"></script> 
<style>
.srchWrap {
    margin-left: 194px;
    position: relative;
    float: right;
    width: 100%;
    margin-right: 10px;
}
.srchWrap input {
    padding-left: 35px;
    font-size: 16px;
}
.srchWrap input:focus {
    border: 1px solid #2196f3 !important;
}
.srchWrap i {
    position: absolute;
    left: 12px;
    top: 14px;
}
</style>
<div id="rightbar"> 
<?php defined('BASEPATH') OR exit('No direct script access allowed'); error_reporting(0); ?>
<div class="content clearfix"></div>
<div class="content" style="padding-top:0px;">
	<div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">    
      <?php 
            echo form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','id'=>'documentUpload','name'=>'documentUpload','autocomplete'=>'off']).
            form_fieldset('Document Upload').
              '<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;top: 38px;"></i>'.
              '<div class="date-div text-success"></div>';
                $st=$this->efiling_model->data_list_where('sat_temp_appellant','salt', $salt);
                ?>
                <input type="hidden" id="saltNo" name="saltNo" value="<?php echo $salt; ?>">
                <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
                <input type="hidden" id="tabno" name="tabno" value="6"> 
                <input type="hidden" id="type" name="type" value="<?php echo $type; ?>"> 
                <input type="hidden" id="valtype" name="valtype" value="<?php echo $type; ?>"> 
                
                <div class="col-md-12">
                  <h3 class="text-info">All Files should be pdf format only <sup class="text-danger">*</sup></h3>
				</div>
                   <div class="col-md-12">
                  <div class="row rdoc">
                    <div class="col-md-2">
                      Document Filed By <sup class="text-danger">*</sup>
                    </div>
                    <div class="col-md-4">
                      <select id="party_type" class="form-control">
                        <option value="">Document filed by</option>
                        <option value="appellants" selected>Appellant</option>
                      </select>
                    </div>
                    <div class="col-md-2">
                      Document Type <sup class="text-danger">*</sup>
                    </div>
                    <?php
                    $st=$this->efiling_model->data_list_where('temp_documents_upload','salt', $salt);
                    $doc=$this->efiling_model->data_list_where('master_document_efile','doctype', 'ia');
                    ?>
                    <div class="col-md-4">
                      <select id="req_dtype" class="form-control" onClick="openmatter(this.value);">
                        <option value="">Select Document Type</option>
                        <?php foreach($doc as $row){
                            $disabl='';
                            if($partyType=='1' && $row->id=='18'){
                                $disabl="disabled";
                            }
                            ?>
                        <option value="<?php echo $row->id; ?>" <?php echo $disabl; ?>><?php echo $row->docname; ?> </option>
                        <?php 
                        } ?>
                         <option value="01">Challan Receipt</option>
                         <option value="0">Any other Documents</option>
                      </select>
                    </div>
                 </div> 
                 
                 <div class="row rdoc" id="matter" style="display:none">
                      	<div class="col-md-2">
                          Documents Name<sup class="text-danger">*</sup>
                        </div>
                        <div class="col-md-4">
                        <?= form_input(['id'=>'matterc','name'=>'matterc','class'=>'form-control','min'=>0,'max'=>400,'title'=>'Enter min 1 & max 400 pages','style'=>'width:360px'])?>
                        </div>
                  </div>  
                 
                 <div class="row rdoc">    
                    <div class="col-md-2">
                      Select File <sup class="text-danger">*</sup>
                    </div>
                    <div class="col-md-4">
                      <?= form_upload(['id'=>'req_docs','title'=>'Choose file should be pdf format only','placeholder'=>'choose profile image','accept'=>'application/pdf'])?>
                    </div>
                  </div>
                  
  			      <div class="row rdoc">    
                    <div class="col-md-2">
                     Undertaking <sup class="text-danger">*</sup>
                    </div>
                    <div class="col-md-4">
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckChecked" <?php echo $checked; ?>>
                    An undertaking shall be given by the counsel to file 
                    the original paper book or IAs complete in all 
                    respects including the requisite documents, 
                    affidavit and duly signed Vakalatnama/ power of 
                    Attorney etc. in original within 3 days from the date of filing.
                     </div>
                  </div>
                  
                </div>
                <div class="offset-8 col-md-4 text-right" style="margin-top:12px">
                  <i class="icon-file-plus" style="position: absolute; margin: 9px 4px; z-index: 9; color: white;"></i>'.
                  <?= form_submit(['value'=>'Save & Next','class'=>'btn btn-success','id'=>'documentSave','style'=>'padding-left:24px;']) ?>
                    '&nbsp;&nbsp;&nbsp;&nbsp;<i class="icon-trash-alt" style="position: absolute; z-index: 9; margin: 10px 4px; color:white;"></i>
                    <?= form_reset(['value'=>'Reset/Clear','class'=>'btn btn-danger','style'=>'padding-left: 24px;'])?>
             </div>
                    <?php  form_fieldset_close();
                form_close();
      ?>
      <FIELDSET>
        <LEGEND><b class="fa fa-upload">&nbsp;&nbsp;Uploaded Documents Details :</b></legend>

        <table style="width: 100%;" border=1>
            <thead>                    
                <tr>
                    <th style="width:5%">SR.No.</th> 
                    <th style="width:15%">Party Type</th>                    
                    <th style="width:55%">Document Type</th> 
                    <th style="width:55%">Documents Name</th>
                    <th style="width:55%">Matter</th>                                                  
                    <th style="width:15%">No of Pages</th>                   
                    <th style="width:5%">Last Update </th>
                    <th style="width:5%">View</th>
                    <th style="width:5%">Delete</th>
                </tr>
            </thead>
            <tbody id='updData'>
            </tbody>
        </table>
      </fieldset>

      <!--- DISPLAY UPLOADED ID PROOF ------>
      <div class="row" style="padding: 12px;min-height:600px;display:none;" id="rDisplay">
        <iframe src="" frameborder="0" style="height:580px;width:100%" id="riframDisplay"></iframe>
      </div>
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
function openmatter(val){
	if(val==0){
		$("#matter").show();
	}else{
	   $("#matter").hide();
	}
}



$(document).ready(function(){
  listUpdFiles();
});


$('#req_docs').change(function(e){
    e.preventDefault();
    var party_type=$('#party_type').find("option:selected").val(), 
      req_dtype=$('#req_dtype').find("option:selected").text(),
      req_dtype_val=$('#req_dtype').find("option:selected").val(), 
      token=Math.random().toString(36).slice(2), 
      docvalid=$('#req_dtype').val(),  
      token_hash=HASH(token+'upddoc');

      var salt= $("#saltNo").val();
      var type =$('#type').val();
      var submittype =$('#valtype').val();
      if(req_dtype=='Any other Document'){
      	 var matter=$("#matterc").val();
      }
     if(party_type != '' && req_dtype_val !='') {
        formdata = new FormData();
        if($(this).prop('files').length > 0) {
		  $('#progreess').show();	
            file =$(this).prop('files')[0], name = $.trim(file.name), name=name.toLowerCase(), size = file.size, type = $.trim(file.type), type=type.toLowerCase();
            var dots = name.match(/\./g).length, extarray=name.split('.'), ext=extarray[1].toLowerCase(), validImageTypes = ["image/gif","image/jpeg","image/png"]; 
          // $('#loading_modal').modal();
            if(file != undefined && type == "application/pdf") { 
                if(dots > 1){  
                    $.alert('More than one dot (.) not allowed in uploding file!');
                    $('#req_doc').val(''); return false;
                }
                else if (size > 1999990) {  
                    $.alert('Please select file size less than 2000 KB.');
                    $('#req_doc').val(''); return false;
                }
                else {
                	$(this).attr('disabled',true); 
                    formdata.append("userfile", file), 
                    formdata.append("party_type", party_type),
                    formdata.append("req_dtype",req_dtype),
                    formdata.append("token", token),
                    formdata.append("salt", salt);
                    formdata.append("matter", matter);
                    formdata.append("type", type);
                    formdata.append("submittype", submittype);
                    formdata.append("docvalid", docvalid); 
                    $.ajax({
                        type:'post',
                        url: base_url+'required_ia/'+token_hash,
                        data: formdata,
                        processData: false,
                        contentType: false,
                        dataType: 'JSON',
                        success: function(response){
                            if(response.data=='success') {
                            var flName='';

                            $.alert({
                              title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success</b>',
                              content: '<p class="text-success">Choose document uploaded successfully.</p>',
                              animationSpeed: 2000
                            });
                          
                            $('#req_docs').removeAttr('disabled',false);

                            $('#rDisplay').show();
                            
                            $('#req_dtype').find("option:selected").css('color','red').attr('disabled',true);
                            $('#pages').val("");
                            $('#documentSave').removeAttr('disabled',false);
                            $('#loading_modal').hide();   
                            }else if(response.error !='0') {
                                $.alert(response.error);
                            }
                        },
                        error: function(xhr,status){
                            $.alert('Server busy, try later');
                        },
                      complete: function(){
                        listUpdFiles();
                        /*   setTimeout(function(){ 
                                   window.location.reload(true);
        		                   $('#rightbar').empty().load(base_url+'/loadpage/document_upload'); 
                         }, 500); */
                      }
                    });
                }
            }else {
                $.alert("Please Choose Valid Document");
                $('#idproof').val(''); return false;
            }
        }
    }else {
        $.alert("Please select all mandatory fields!");
        $('#req_docs').val(''); return false;
    }
});
	
$('#party_type').change(function(){
  $('#req_dtype').find('option').removeAttr('style',false).removeAttr('disabled',false);
});

/****** Form Submit ****** */
$('#documentUpload').submit(function(e){
  e.preventDefault();
  //$('#loading_modal').fadeIn(200);
  var salt= $("#saltNo").val();
  var token= $("#token").val();
  var tabno= $("#tabno").val();
  var ut= document.getElementById("flexCheckChecked").checked;
  var untak='';
  if(ut==false){
  	var untak ='0';
  }	
  if(ut==true){
  	var untak ='1';
  } 
  $.ajax({
    type: "POST",
    url: base_url+"doc_save_nextIA",
    data: {salt:salt, token:token, tabno:tabno, untak:untak},
    dataType: 'json',
    success: function (resp) {
      if(resp.data=='success') {
        setTimeout(function(){
            window.location.href = base_url+'ia_checklist';
         }, 250);
      }
      else if(resp.error !='0') { 
        $.alert({
          title: '<i class="fa fa-check-circle text-warning"></i>&nbsp;</b>Error!</b>',
          content: '<p class="text-danger">'+resp.data+'</p>',
          animationSpeed: 2000
        });
      }
    },
    error: function (request, error) {
      $('#loading_modal').fadeOut(200);
    }
  });
});

function listUpdFiles(){
    var saltId=$('#saltNo').val();
    var type=$('#valtype').val();
    $.ajax({
            type: 'post',
            url: base_url+'viewUpdList',
            data: {'saltId': saltId,'type':type},
            dataType: 'json',
            success: function(rtn){
              if(rtn.error=='0'){
                var itemData='', count=0;
                $.each(rtn.data, function(i,item) {
                  count++;
                  var document_filed_by = item.document_filed_by;
                  var document_type = item.document_type;
                  var no_of_pages = item.no_of_pages;
                  var doc_name= item.doc_name;
                  var file_id = item.id;
                  var matter = '';
                  if(item.matter!='undefined'){
                  	var matter = item.matter;
                  }
                  var valumeno = item.valumeno;
                  valumenovc='';
                  if(valumeno!=''){
                     var valumenovc = '<span style="color:red">Vol-'+item.valumeno+'</span>';
                  }
                  
                  var update_on = item.update_on;
                      update_on=moment(update_on, 'YYYY-MM-DD HH:mm:ss').format("DD-MM-YYYY HH:mm:ss");
                  	  itemData += '<tr id="val'+file_id+'"><td>'+count+'</td><td>'+document_filed_by+'</td><td>'+document_type+' '+valumenovc+'</td><td>'+doc_name+'</td><td>'+matter+'</td><td>'+no_of_pages+'</td><td>'+update_on+'</td><td id="updDocId"><a href="javascript:void();" onclick=viewFile("'+file_id+'");><i class="fa fa-eye"></i></a></td><td id="updDocId"><a href="javascript:void();" onclick=docDelete("'+file_id+'");><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>';
                });
                $('#updData').html(itemData);

              }
              else {
                //$.alert(rtn.data);
              }
            },
            error: function(){
              $.alert("Server busy, try later");
            }
    });
}


function dicript(textval){
    var DataEncrypt = textval;
    var DataKey = CryptoJS.enc.Utf8.parse("01234567890123456789012345678901");
    var DataVector = CryptoJS.enc.Utf8.parse("1234567890123412");
    var decrypted = CryptoJS.AES.decrypt(DataEncrypt, DataKey, { iv: DataVector });        
    return  CryptoJS.enc.Utf8.stringify(decrypted);
}


function viewFile(docId){
    event.preventDefault();
    var updId='', href='';
    updId=docId;
    $.ajax({
        type: 'post',
        url: base_url+'uploaded_docs_display',
        data: {docId: updId},
        dataType: 'json',
        success: function(rtn){
            if(rtn.error == '0'){
                var valurl= dicript(rtn.data)
                href=base_url+'order_view/'+btoa(valurl);    
                $('#frameID').attr("src", href + "#view=VFit" + "&toolbar=0" + "&navpanes=0");
            }
            else $.alert(rtn.error);
        },
        error:function(){
          $.alert('Server busy, Try later');
        }
    });
    $('#updPdf').modal('show');
}




function docDelete(docId){
    event.preventDefault();
    var updId='', href='';
    updId=docId;
    $.ajax({
        type: 'post',
        url: base_url+'uploaded_docs_delete',
        data: {docId: updId},
        dataType: 'json',
        success: function(rtn){
           if(rtn.error == '0'){
        	   $.alert({
					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Done</b>',
					content: '<p class="text-success">'+rtn.msg+'</p>',
					animationSpeed: 2000
				});
				setTimeout(function(){
                   window.location.reload(1);
                }, 1000);
			}	
            if(rtn.error == '0'){
 				$("#val"+docId).hide();
 				$('#rDisplay').hide();
            }
            else $.alert(rtn.error);
        },
        error:function(){
          $.alert('Server busy, Try later');
        }
    });
}



</script>	
<style>
  .row.rdoc div {
    padding: 12px 8px;
    border: 1px solid #ababab;
    border-radius: 4px;
  }
</style>
<?php $this->load->view("admin/footer"); ?>