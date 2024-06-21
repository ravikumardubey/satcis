<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepsrpepcp");
$token= $this->efiling_model->getToken();
$partytype='';
$filingno='';
$tab_no='';
$type='';

$vals=$this->session->userdata('rpepcpdetail');
if(!empty($vals) && is_array($vals)){
    $newfiling_no=$vals['filing_no'];
    $printIAno=$vals['ia_no'];
    $iaFilingNossssss=$vals['iaFilingNossssss'];
    $curYear=$vals['curYear'];
    $valfilingno='';
    if($newfiling_no!=''){
        $val= substr($newfiling_no,-8);
        $a=substr_replace($val ,"",-4);
        $b= substr($val, -4);
        $valfilingno='AL No.- '. $a.'/'.$b;
    }
}
?>
<div id="rightbar"> 
	<form action="#" id="frmCounsel" autocomplete="off">    
        <div class="content" style="padding-top:0px;">
    		<fieldset id="iaNature" style="display:block">
                   <div class="table-responsive">
                        <div><a href="void:javascript(0);" style="color: #3F33FF" data-toggle="modal" onclick="return popitup('<?php echo $newfiling_no; ?>','<?php echo $iaFilingNossssss; ?> ','<?php echo $curYear; ?>')"><b><?php echo "Reciept"; ?></b></a></div>            
                        <fieldset>
                            <legend style="color: red;font-size:15"><?php echo strtoupper($type); ?> Appeal Lodging Number :</legend>
                            <div class="col-md-12"><font color="#0000FF" size="5">
                                    Case is successfully registered With <?php echo strtoupper($type); ?> Appeal Lodging :
                                    <?php echo $valfilingno . "</br>";
                                    echo $printIAno;
                                    ?>
                                </font></div>
                        </fieldset>
                   </div>
             </fieldset>
        </div>
     </form>
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
            

<?php $this->load->view("admin/footer"); ?>
            
<script>
    function popitup(filingno,ianumbt,year) {
    	 var dataa={};
         dataa['filing_no']=filingno,
         dataa['iano']=ianumbt,
         dataa['year']=year,
          $.ajax({
              type: "POST",
              url: base_url+"/filingaction/iaprint_rp_cp_ep",
              data: dataa,
              cache: false,
              success: function (resp) {
            	  $("#getCodeModal").modal("show");
             	  document.getElementById("viewsss").innerHTML = resp; 
              },
              error: function (request, error) {
  				$('#loading_modal').fadeOut(200);
              }
          }); 
    	  
    }  
</script>
