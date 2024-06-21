<table id="actintable" class="table table-hover" width="100%">
    <thead>
    <tr>
        <th scope="col">Sr. No</th>
        <th scope="col">MA No</th>
        <th scope="col">MA Year</th>
        <th scope="col">MA Nature</th>
        <th scope="col">MA Days</th>
        <th scope="col">Status</th>
        <th scope="col" style="width: 13%;">Filing Date</th>
        <th scope="col" style="width: 26%;">Filed by</th>
        <th scope="col" style="width: 26%;">IA Disposel Date</th>
        <th scope="col">Edit</th>
        <th scope="col">Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $query =$this->db->query("select * from sat_case_detail where filing_no='$filing_no'");
    $row1= $query->result();
    foreach($row1 as $row){
        $petName_namme = $row->pet_name;
        $resName_namee = $row->res_name;
    }
    
    $ia_detail_query =$this->db->query("select * from ma_detail where filing_no='$filing_no'");
    $row1= $ia_detail_query->result();
    $fgfgfg = 1;
    foreach($row1 as $row_iad){
        $iadays='';
        if($row_iad->iadays!=0){
            $iadays= htmlspecialchars($row_iad->iadays.'  days');
        }
        $ia_fil_nom = htmlspecialchars($row_iad->filing_no);
        $ia_fil_no_unq = htmlspecialchars($row_iad->ia_filing_no);
        $ia_nom = htmlspecialchars($row_iad->ia_no);
        $additional_partyqq = htmlspecialchars($row_iad->additional_party);
        $ia_yearm = htmlspecialchars($row_iad->dt_of_filing);
        $ia_naturecodem = htmlspecialchars($row_iad->ia_nature);
        $ia_statusm = htmlspecialchars($row_iad->status);
        $ia_fil_datem = htmlspecialchars($row_iad->entry_date);
        $filed_bym = htmlspecialchars($row_iad->filed_by);
        $filed_bym11 = htmlspecialchars($row_iad->filed_by);
        $ia_nature_namem = '';
        if ($ia_naturecodem != '' || $ia_naturecodem != NULL) {
            if ($ia_naturecodem != 'D') {
                    $ia_naturem = $this->db->query("select nature_name from moster_ia_nature where nature_code = '$ia_naturecodem'");
                    $row1= $ia_naturem->result();
                    foreach($row1 AS $roes){
                        $ia_nature_namem = $roes->nature_name;
                    }
            }
        }
        if ($ia_statusm == 'D') {
            $ia_statusnamem = 'Disposal';
            $color='red';
        } else if ($ia_statusm == 'P') {
            $ia_statusnamem = 'Pending';
            $color='green';
        }
        $ia_fil_datem_exp = explode("-", $ia_fil_datem);
        $ia_fil_datem_format = $ia_fil_datem_exp[2] . '-' . $ia_fil_datem_exp[1] . '-' . $ia_fil_datem_exp[0];
        
        
        if ($filed_bym == '1') {
            $filed_bym = 'A';
            if($additional_partyqq!='') {
                $additional_partyqq = explode(',', $additional_partyqq);
                if (is_array($additional_partyqq) && !empty($additional_partyqq)) {
                    $additional_partyqq_name='';
                    $asa = 1;
                    foreach ($additional_partyqq as $vall) {  
                        if ($vall!='') {
                            if ($vall == 1) {
                                $additional_partyqq_name.= substr($petName_namme, 0, 20) . '( A-' . $asa . ')<br>';
                            } else {
                                    $ia_naturem = $this->db->query("SELECT  pet_name,partysrno FROM additional_party where filing_no='$ia_fil_nom' AND party_flag='$filed_bym11' AND  party_id = '$vall' order by CAST(partysrno AS int) ASC,party_id ASC");
                                    $row1= $ia_naturem->result();
                                    foreach($row1 AS $roes){
                                        $pet_name= $roes->pet_name;
                                        $partysrno = $roes->partysrno;
                                    }
                                    $row1 = $ia_naturem->fetch();
                                    if(!empty($row1)){
                                        $additional_partyqq_name .= substr($pet_name, 0, 20) . '(A-' . $partysrno . ')<br>';
                                    }
                            }
                        }
                    }
                }
            } else {
                $additional_partyqq_name= substr($petName_namme, 0, 20) . '( A-1)';
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
                        if ($vall != '') {
                            if ($vall == '1') {
                                $additional_partyqq_name .= substr($resName_namee, 0, 20) . '( R-' . $asa1 . ')<br>';
                            } else {
                                    $ia_naturem = $this->db->query("SELECT  * FROM additional_party where party_id = ? AND  filing_no='$ia_fil_nom' AND party_flag='$filed_bym11' order by CAST(partysrno AS int) ASC,party_id ASC");
                                    $row1= $ia_naturem->result();
                                    foreach($row1 AS $roes){
                                        $pet_name= $roes->pet_name;
                                        $partysrno = $roes->partysrno;
                                    }
                                    $row1 = $ia_naturem->fetch();
                                    if(!empty($row1)){
                                        $additional_partyqq_name .= substr($pet_name, 0, 20) . '(A-' . $partysrno . ')<br>';
                                    }
                        
                            }
                        }
                    }
                }
            } else {
                $additional_partyqq_name = substr($resName_namee, 0, 20) . '( R-1)';
            }
        }

        $disdate='';
        if ($ia_statusm == 'D') {
            if($row_iad['disposal_date']!=''){
              $disdate = date('d/m/Y',strtotime($row_iad->disposal_date));
            }
        }
       $hashlcdmod = $ia_fil_no_unq; ?>
        <tr>
            <th scope="row"><?php echo $fgfgfg; ?></th>
            <td> <?php echo $ia_nom; ?></td>
            <td><?php echo substr($ia_fil_no_unq, 11, 4); ?></td>
            <td><?php echo $ia_nature_namem; ?></td>
            <td><?php echo $iadays; ?></td>
            <td style="color:<?php echo $color; ?>"><?php echo $ia_statusnamem; ?></td>
            <td><?php echo $ia_fil_datem_format; ?></td>
            <td><?php echo $additional_partyqq_name; ?></td>
            <td><?php echo $disdate; ?></td>
            <td align="center"><a href="javascript:modify_ia('<?php echo htmlspecialchars(base64_encode($hashlcdmod)); ?>');"  onclick="return confirm('Are you sure to modify this ia detail?');"><img   src="edit.png" name="imgCalendar" width="20"  height="20" border="0" alt=""> </a>         </td>
            <td> <input type="button" class="btn btn-info" value="Delete"  onclick="delete_party_ia('<?php echo $ia_fil_nom; ?>','<?php echo $ia_fil_no_unq; ?>')"></td>
        </tr>
        <?php
        $fgfgfg++; }
    if ($ia_fil_nom == '' || $ia_fil_nom == NULL) {
        ?>
        <tr> <td>No Record Found...</td> </tr>
        <?php } ?>
    </tbody>
</table>



