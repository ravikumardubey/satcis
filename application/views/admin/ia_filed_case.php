<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed');
if($this->input->post()) {
 $year=$this->input->post('year');
}else   $year=date('Y');
?>

<div class="content" style="padding-top:12px;">
	<div class="row">
		<div class="card" style="width: 100%; padding: 12px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px;border-top-left-radius: 0px;">
            <?php 
                echo form_fieldset('<small class="fa fa-list"></small>&nbsp;&nbsp;MA LIST','ia_list').
                        '<div class="date-div text-success">Date & Time : &nbsp;<small>'.date('D M d, Y H:i:s').'&nbsp;IST</small></div>
                    

                         <div class="table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer" id="ia_list_table" style="width: 100%;" role="grid" aria-describedby="ia_list_table_info">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">AL No.</th>
                                        <th scope="col">MA No.</th>
                                        <th scope="col">MA Year</th>
                                        <th scope="col">MA Nature</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" style="width: 13%;">Filing Date</th>
                                        <th scope="col" style="width: 15%;">Filed by</th>
                                        <th scope="col" style="width: 10%;">MA Print</th>
                                    </tr>
                                </thead>
                                <tbody>';
                $additional_partyqq_name='';
                                   // $ia_data_array=$this->admin_model->ia_data_list('ia_detail');
                                    $ia_data_array=$this->admin_model->ia_data_list();
                            $count=1;
                            foreach($ia_data_array as $row_iad){
                                $ia_fil_nom = htmlspecialchars($row_iad->filing_no);
                                $st_aptel_case_detail = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$ia_fil_nom);
                                foreach ($st_aptel_case_detail as $row) {
                                    $petName_namme = $row->pet_name;
                                    $resName_namee = $row->res_name;
                                }
                                $ia_fil_no_unq = htmlspecialchars($row_iad->ia_filing_no);
                                $ia_nom = htmlspecialchars($row_iad->ia_no);
                                $additional_partyqq = htmlspecialchars($row_iad->additional_party);
                              //  $ia_yearm = $row_iad->dt_of_filing;
                                $ia_naturecodem = $row_iad->ia_nature;
                                $ia_statusm = htmlspecialchars($row_iad->status);
                                $ia_fil_datem = htmlspecialchars($row_iad->entry_date);
                                $filed_bym = htmlspecialchars($row_iad->filed_by);
                               // $ia_nature_namem = htmlspecialchars($row_iad->nature_name);
                                $ia_nature_namem = '';
                                if ($ia_naturecodem != '' || $ia_naturecodem != NULL) {
                                    if ($ia_naturecodem != 'D') {     
                                            $ia_naturem = $this->efiling_model->data_list_where('moster_ma_nature','nature_code',$ia_naturecodem);
                                            $ia_nature_namem = @$ia_naturem[0]->nature_name;
                                    }
                                }
                                

                                if ($ia_statusm == 'D')         $ia_statusnamem = '<span class="text-danger">Disposal</span>';
                                else if ($ia_statusm == 'P')    $ia_statusnamem = '<span class="text-primary">Pending</span>';

                                $ia_fil_datem_exp = explode("-", $ia_fil_datem);
                                $ia_fil_datem_format = $ia_fil_datem_exp[2] . '-' . $ia_fil_datem_exp[1] . '-' . $ia_fil_datem_exp[0];

                                if ($filed_bym == '1') {
                                    $filed_bym = 'A';
                                    if($additional_partyqq!='') {

                                        $additional_partyqq = explode(',', $additional_partyqq);

                                        if (is_array($additional_partyqq) && !empty($additional_partyqq)) {
                                            $additional_partyqq_name = '';
                                            $asa = 1;
                                            foreach ($additional_partyqq as $vall) {
                                                if ((int)$vall>0) {

                                                    if ($vall == '1')
                                                        $additional_partyqq_name .= substr($petName_namme, 0, 20) . '( A-' . $asa . ')<br>';
                                                    else {
                                                        $ia_naturem=$this->db->select('pet_name')
                                                                             ->get_where('additional_party',['party_id'=>$vall])
                                                                             ->row('pet_name');
                                                        $additional_partyqq_name .= substr($ia_naturem, 0, 20).'( A-'.$asa.')<br>';
                                                        $asa++;
                                                    }           
                                                } else $additional_partyqq_name = substr($petName_namme, 0, 20) . '( A-1)';
                                            }
                                        }
                                    }
                                }

                                else if ($filed_bym == '2') {
                                    $filed_bym = 'R';
                                    if($additional_partyqq!='') {
                                        $additional_partyqq = explode(',', $additional_partyqq);
                                        if (is_array($additional_partyqq) && !empty($additional_partyqq)) {
                                            $additional_partyqq_name = '';
                                            $asa1 = 1;
                                            foreach ($additional_partyqq as $vall) {
                                                if ((int)$vall > 0) {
                                                    if ($vall == '1')
                                                        $additional_partyqq_name .= substr($resName_namee, 0, 20) . '( R-' . $asa1 . ')<br>';
                                                    else {                                                                
                                                        $ia_naturem=$this->db->select('pet_name')
                                                                             ->get_where('additional_party',['party_id'=>$vall])
                                                                             ->row('pet_name'); 
                                                        $additional_partyqq_name .= substr($ia_naturem, 0, 20) . '(R-' . $asa1 . ')<br>';
                                                    }
                                                    $asa1++;
                                                }
                                            }
                                        }
                                    } else $additional_partyqq_name = substr($resName_namee, 0, 20) . '( R-1)';
                                }
                                $iayear = substr($ia_fil_no_unq, 11, 4);
                                ?>

                                <tr>
                                       <th scope="row"><?php echo $count++; ?></th>
                                       <td><?php echo ltrim(substr($ia_fil_nom,5,6),0).'/'.substr($ia_fil_nom,11,4); ?></td>
                                       <td><?php echo $ia_nom; ?></td>
                                       <td><?php echo substr($ia_fil_no_unq, 11, 4); ?></td>
                                       <td><?php echo $ia_nature_namem; ?></td>
                                       <td><?php echo $ia_statusnamem; ?></td>
                                       <td><?php echo $ia_fil_datem_format; ?></td>
                                       <td><?php echo $additional_partyqq_name; ?> </td>
                                       <td><a target="_blank" href="<?php echo base_url(); ?>iaprint/<?php echo $ia_fil_nom; ?>/<?php echo base64_encode($ia_nom); ?>/<?php echo $iayear;?>">MA Print</a></td>
                                    </tr>

                            <?php } ?> 
                           </tbody>
                            </table>
                        </div>
          <?php   echo form_fieldset_close();  ?>
        </div>
	</div>
</div>
 <?php $this->load->view("admin/footer"); ?>
<script type="text/javascript">
    $.(document).ready(function(){
        $('#ia_list_table').DataTable();
    });
</script>