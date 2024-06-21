<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Efiling_model extends CI_Model {
    
    function user() {
        parent::Model();
    }
    
    function get_single_table($table){
        $this->db->where('status', '1');
        $query = $this->db->get($table);
        $data = $query->result();
        return $data;
    }
    

    function select_inparty($table,$arr){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($arr);
        $this->db->order_by('partysrno','ASC');
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }
    
   
    function getCaseDetailsDfrdoc($fno,$year){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $detail = "Case No Details";
        $diaryYear1 = $year;
        $bench = 100;
        $subBench = 1;
        $subBenchCode = htmlspecialchars(str_pad($subBench, 2, '0', STR_PAD_LEFT));
        $len = strlen($fno);
        $length = 6 - $len;
        for ($i = 0; $i < $length; $i++) {
            $diaryNo = "0" . $fno;
        }
        $filing_no_old = $bench . $subBenchCode . $diaryNo . $diaryYear1;
        $filing_no_old = $diaryNo . $diaryYear1;  // change ravi kumar dubey
        $query_str="SELECT * FROM sat_case_detail  where filing_no like '%$filing_no_old' ";
        $query=$this->db->query($query_str);
        return $data = $query->result();
    }
    
    
    function  ia_data_list($table,$array,$col,$order){
        $this->db->where_not_in($col,$array) ;
        $this->db->from($table);
        $this->db->order_by($order,'ASC');
        $query = $this->db->get();
        return $query->result();
    }
    

    
    function  ia_dataIN_list($table,$array,$col,$order){
        $this->db->where_in($col,$array) ;
        $this->db->from($table);
        $this->db->order_by($order,'ASC');
        $query = $this->db->get();
        return $query->result();
    }
    
    

    function data_list($table){
        $this->db->from($table);
        $query = $this->db->get();
        // echo $str = $this->db->last_query();
        return $query->result();
    }
    
    
    
    function getColumn($table,$requestcolumn,$col,$id){
        $this->db->select($requestcolumn);
        $this->db->from($table);
        $this->db->where($col,$id);
        return $this->db->get()->row()->$requestcolumn;
    }
    
    
    
    function data_list_mulwhere($table,$array){
        $this->db->where($array);
        $this->db->from($table);
        $query = $this->db->get();
        // echo $str = $this->db->last_query();
        return $query->result();
    }

    function data_list_rpepcp($table,$col,$id){
        $this->db->from($table);
        $ages=array(5,6,7,6);
        $this->db->where_in('case_type',$ages);
        $this->db->where($col,$id);
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }
    
    
    
    
    function data_list_where($table,$col,$id){
        $data=$this->db->select('*')
        ->get_where($table,[$col=>$id])
        ->result();
        return $data;
    }
    
    function data_list_commission($table,$where_cond){        
        $data=$this->db->select('*')->get_where($table,$where_cond);
        if($data->num_rows()>0) return $data->result();
        else return false;
    }


    
    
    function getCaseType(){
        $this->db->where('flag', '1');
        $array=array('7','2');
        $this->db->where_not_in('case_type_code',$array) ;
        $this->db->from('master_case_type');
        $this->db->order_by('case_type_code','ASC');
        $query = $this->db->get();
        return $query->result();
      //  echo $str = $this->db->last_query();
    }
    
    
    function getCaseTypeia(){
        $array=array('1','2','3');
        $this->db->where_in('case_type_code',$array) ;
        $this->db->from('master_case_type');
        $this->db->order_by('case_type_code','ASC');
        $query = $this->db->get();
     //   echo $str = $this->db->last_query();die;
        return $query->result();
    }
    
    
    function feedetailia(){
        $array=array('11','9');
        $this->db->where_in('doc_code',$array) ;
        $this->db->from('master_fee_detail');
        $this->db->order_by('doc_code','ASC');
        $query = $this->db->get();
        return $query->result();
        //  echo $str = $this->db->last_query();
    }
    
    function getia($table,$col,$ids){
        $this->db->where_in($col,$ids) ;
        $this->db->from($table);
        $query = $this->db->get();
        return $query->result();
        //  echo $str = $this->db->last_query();
    }
    
    
    
    
    
    function getCaseTyperpcpep(){
        $this->db->where('display', '1');
        $array=array('4','1');
        $this->db->where_in('case_type_code',$array) ;
        $this->db->from('master_case_type');
        $this->db->order_by('case_type_code','ASC');
        $query = $this->db->get();
        return $query->result();
        //  echo $str = $this->db->last_query();
    }
    
    
    function getDistrictlist($stateCode,$distcode){
        $this->db->from('master_psdist');
        $this->db->where(array('state_code'=>$stateCode,'district_code'=>$distcode));
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }
    
    
    function select_in($table,$arr){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($arr);
        $query = $this->db->get();
        $data = $query->result();
      //  echo $str = $this->db->last_query();die;
        return $data;
    }
    
    function update_data_where($table, $where, $data){
        $this->db->where($where);
        $query = $this->db->update($table, $data);
      //  echo $str = $this->db->last_query();die;
        return $query;
    }
    

    
    
    function geRecorappeal($table){
        $this->db->from($table);
        $ages=array(1,2,3,4);
        $this->db->where_in('case_type',$ages);
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }
    

    function gerpepcp($table,$userid){
        $this->db->from($table);
        $ages=array(5,6,7,6);
        $this->db->where_in('case_type',$ages);
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }

    function getdistric($table,$col){
        $this->db->from($table);
        $this->db->where('state_id', $col);
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }
    
    
    function insert_query($table, $data){
        $query = $this->db->insert($table, $data);
        return $query;
    }
    
    function edit_data($table,$col, $id){
        $query = $this->db->get_where($table, array($col=>$id));
        $data = $query->row();
        return $data;
    }
    
    function update_data($table, $data, $idname, $id){
        $this->db->where($idname, $id);
        $query = $this->db->update($table, $data);
        return $query;
    }
    
    function delete_event($table, $col, $id){
        $this->db->where($col, $id);
        $delqu = $this->db->delete($table);
        return $delqu;
        
    }
    
    function geIA($table,$ia,$year){
        $query=$this->db->query("select * from $table where ia_no='$ia' and SUBSTR(ia_filing_no,12,4)='$year'");
        return $query->result();
    }
    
    
    function getCasedetail($filing_no){
        $query=$this->db->query("select * from sat_case_detail where filing_no='{$filing_no}'");
        $rowval= $query->result();
        if(!empty($rowval)){
            return $query->result();
        }
    }
    
    
    
    function createSlug($slug) {
        $lettersNumbersSpacesHyphens = '/[^\-\s\pN\pL]+/u';
        $spacesDuplicateHypens = '/[\-\s]+/';
        $slug = preg_replace($lettersNumbersSpacesHyphens, '', $slug);
        $slug = preg_replace($spacesDuplicateHypens, '-', $slug);
        $slug = trim($slug, '-');
        return mb_strtolower($slug, 'UTF-8');
    }
    
    
    function undersection(){
        $html='';
        $state=$_REQUEST['state_id'];
        $case_typed = $_REQUEST['case_typed'];
        if($state==''){
            echo "<option>Select Under Section</option>";
        }else{
            $this->db->where('act_code',$state);
            $this->db->from('master_under_section');
            $query = $this->db->get();
            $val= $query->result();
            foreach($val as $row){
                $html.='<option value="'.$row->section_code.'">'.$row->section_name.'</option>';
            }
            echo $html;die;
          }
     }
  
     
     
     
     
     
     


    function findrecord($vl){
         $vals='';
         $case_type='1';
         if($vl['type']=='1'){
             $fno=$vl['filing_no'];
             $year=$vl['dfr_year'];
             $vals=$this->recordfing('sat_case_detail',$fno,$year,$case_type);
         }
         if($vl['type']=='2'){
             $cno=$vl['case_no'];
             $year=$vl['year'];
             $case_type=$vl['case_type'];
             $vals=$this->caserecordfing('sat_case_detail',$cno,$year,$case_type);
         }
         return $vals;
     }
    
      function getpartyname($table,$col, $id,$pid){
         $query_str="select * from additional_party where filing_no='$id' and  party_id IN($pid)";
         $query=$this->db->query($query_str);
         return $query->result();
     }
     
     function getadditionalPartydetail($table,$filing_no,$party_flag,$isd){
         $query_str="select * from additional_party where filing_no='$filing_no' and party_flag='$party_flag'";
         $query=$this->db->query($query_str);
         return $query->result();
     }

    function fn_addition_party($filing_no,$flag_type) {
         $pet_others = '';
         $sqlpet ="select party_flag from additional_party where filing_no='$filing_no' and party_flag='$flag_type'";
         $query=$this->db->query($sqlpet);
         $data = $query->result();
         $totalpet =count($data)+1;
         if ($totalpet == 1) {
             $pet_others =  " ";
         }
         if ($totalpet == 2) {
             $pet_others =  " & Anr.";
         }
         if ($totalpet > 2) {
             $pet_others =  " & Ors.";
         }
         return $pet_others;
     }
     
     
     
     function fn_addition_partyr($filing_no,$flag_type) {
         $pet_others = '';
         $sqlpet ="select party_flag from additional_party where filing_no='$filing_no' and party_flag='$flag_type'";
         $query=$this->db->query($sqlpet);
         $data = $query->result();
         $totalpet =count($data)+1;
         if ($totalpet == 1) {
             $pet_others =  " ";
         }
         if ($totalpet == 2) {
             $pet_others =  " & Anr.";
         }
         if ($totalpet > 2) {
             $pet_others =  " & Ors.";
         }
         return $pet_others;
     }
     
     
     function  scrutiny(){
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id; 
         if($user_id){
             $query_str="select ap.filing_no from sat_case_detail as ap left join scrutiny as s on s.filing_no=ap.filing_no where  s.defects ='Y'";
             $query=$this->db->query($query_str);
             return $query->result();
         }
     }
     
     
     function  scrutiny_list(){
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         if($user_id){
             $query_str="select * from sat_case_detail as ap left join scrutiny as s on s.filing_no=ap.filing_no where   s.defects IS NULL";
             $query=$this->db->query($query_str);
             return $query->result();
         }
     }
     
     function defective_list(){
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         if($user_id){
             $query_str="select * from sat_case_detail as ap left join scrutiny as s on s.filing_no=ap.filing_no 
                where  s.defects='Y'";
             
             $query=$this->db->query($query_str);
             return $query->result();
         }
     }
     
     
     function registerdcases_list(){
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         if($user_id){
             $query_str="select * from sat_case_detail where (case_no!='' OR case_no is NOT null)";
             $query=$this->db->query($query_str);
             return $query->result();
         }
     }
     
     
     

     
     
     
     
     function createdfr($dfr){
         $vals= substr($dfr,-8);
         $a=substr_replace($vals ,"",-4);
         $b= substr($vals, -4);
         $valdfr= $a.'/'.$b;
         return $valdfr;
     }
     
     
     function createcaseno($dfr){
         $query_str="select acd.res_name,acd.case_no,mct.short_name from sat_case_detail as acd 
        LEFT JOIN master_case_type mct ON acd.case_type=mct.case_type_code  where acd.filing_no= '$dfr'";
         $query=$this->db->query($query_str);
         $valsc=$query->result();
         $res_name= $valsc[0]->res_name;
         $case_no= $valsc[0]->case_no;
         $case_tye= $valsc[0]->short_name;
         //Case No
         $valc='';
         if($case_no!=''){
             $valc= substr($case_no,-8);
             $ac=substr_replace($valc ,"",-4);
             $bc= substr($valc, -4);
             $valc=$case_tye . -$ac.'/'.$bc;
         }
         return $valc;
     }


     public function getData($post_data=NULL,$col=NULL,$id=NULL){
        $table=$post_data['db_table'];
        $ctype=$post_data['ctype'];
        if(strtolower($ctype)=='all') {
            $data=$this->db->select('to_char(update_on, \'dd-mm-YYYY HH24:MI:SS\') as update_on,salt,pet_name,bench,sub_bench,tab_no,(select name from master_benches where bench_code=bench) as name,(select state_name from master_psstatus where state_code=CAST(sub_bench AS INTEGER)) as state_name')
            ->order_by('update_on','desc')
            ->get_where($table,[$col=>$id]);
        }
        else if(strtolower($ctype) == 'ia'){
            //SELECT * FROM "rpepcp_reffrence_table" WHERE "case_type" = 'IA' AND "user_id" = '59'
            $data=$this->db->select('to_char(update_on, \'dd-mm-YYYY HH24:MI:SS\') as update_on,salt,pet_name,bench,sub_bench,tab_no,(select name from master_benches where bench_code=bench) as name,(select state_name from master_psstatus where state_code=CAST(sub_bench AS INTEGER)) as state_name')
            ->where($col,$id)
            ->where_in('l_case_type',explode(",",$ctype))
            ->order_by('update_on','desc')
            ->get($table);
        }
        else{
            $data=$this->db->select('\'\' as update_on,salt,pet_name,bench,sub_bench,tab_no,(select name from master_benches where bench_code=bench) as name,(select state_name from master_psstatus where state_code=CAST(sub_bench AS INTEGER)) as state_name')
            ->where($col,$id)
            ->where_in('l_case_type',explode(",",$ctype))
            ->get($table);
        }
        //exit($this->db->last_query());
        if($data->num_rows()>0) {
            foreach($data->result() as $data_val) :;
                $pet_name=$data_val->pet_name;
                $salt=$data_val->salt;
                $name=$data_val->name;
                $state_name=$data_val->state_name;
                $tab_no=$data_val->tab_no;
                $update_on=$data_val->update_on;
                if(is_numeric($pet_name)){
                    $org_name=$this->db->select('org_name')->get_where('master_org',['org_id'=>(int)$pet_name])->row()->org_name;
                    $pet_name=$org_name;
                }
                $final_data[]=['pet_name'=>$pet_name,'salt'=>$salt,'name'=>$name,'state_name'=>$state_name,'tab_no'=>$tab_no,'update_on'=>$update_on];
            endforeach;
            return @$final_data;
        }
        else return false;
     }

     function list_uploaded_docs($table, $wcond){         
        $rs=$this->db->select('document_filed_by,filing_no,docid, document_type, no_of_pages,display, file_url,doc_name, id,doc_type, update_on,matter,valumeno')
                     ->where($wcond)
                     ->order_by('document_filed_by ASC, update_on ASC')
                     ->get($table);

        if($rs->num_rows() > 0) return $rs->result();
        else false;
     }
     
     function getToken(){ 
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         $token=rand(1000,9999);
         $md_dbc = hash('sha256',$token.'@'.$user_id);
         $this->session->set_userdata('submittoken',$md_dbc);;
         return $md_dbc;
     }
     
     function getCaseDetailsCaseNo($cno,$year,$case_type){
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         if($user_id){
             $cnoss=$cno;
             $cnolength= strlen($cno);
             $cno='';
             if($cnolength==1){
                 $cno='000';
             }
             if($cnolength==2){
                 $cno='00';
             }
             if($cnolength==3){
                 $cno='0';
             }
             $case1='400';
             $ct='';
             if($case_type==1){
                 $ct='1000';
             }
             if($case_type==2){
                 $ct='2000';
             }
             if($case_type==3){
                 $ct='3000';
             }
             $cno=$case1.$ct.$cno.$cnoss.$year;
             $query_str="SELECT * FROM sat_case_detail  where case_no like '%$cno%' AND case_type='$case_type' limit 1";
             $query=$this->db->query($query_str);
             return  $query->result();
         }
     }
     
     
     function getCaseDetailsDfr($fno,$year){
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         $detail = "Case No Details";
         $diaryYear1 = $year;
         $bench = 100;
         $subBench = 1;
         $subBenchCode = htmlspecialchars(str_pad($subBench, 2, '0', STR_PAD_LEFT));
         $len = strlen($fno);
         $length = 6 - $len;
         for ($i = 0; $i < $length; $i++) {
             $diaryNo = "0" . $fno;
         }
         $filing_no_old = $bench . $subBenchCode . $diaryNo . $diaryYear1;
         $filing_no_old = $diaryNo . $diaryYear1;  // change ravi kumar dubey
         $query_str="SELECT * FROM sat_case_detail  where filing_no like '%$filing_no_old'";
         $query=$this->db->query($query_str);
         return $data = $query->result();
     }
     
     
     function  getDFRformate($val){
         $filing_No = substr($val, 5, 6);
         $filing_No = ltrim($filing_No, 0);
         $filingYear = substr($val, 11, 4);
         return  "DFR/$filing_No/$filingYear";
     }
     
     function  getCASEformate($val){
         if($val!=''){
             $filing_No = substr($val, 5, 6);
             $filing_No = ltrim($filing_No, 0);
             $filingYear = substr($val, 11, 4);
             if($filingYear!=''){
                return  "APP/$filing_No/$filingYear";
             }
         }
         return '';
     }

     function getPartydetail($filing_no,$party_flag){
         $this->db->from('additional_party');
         $this->db->where(array('filing_no'=>$filing_no,'party_flag'=>$party_flag));
         $this->db->order_by('partysrno','ASC');
         $query = $this->db->get();
         $data = $query->result();
         return $data;
     }
     
     
     function calculateapp($count){
         if($count!=0)
         return $count*5000;
         else 
         return 0;    
     }
     
     
     function user_menu_data($user_id,$username) {
         $query=$this->db->query("select menu_access from users where id='$user_id' AND username='$username'");
         $partno= $query->result();
         $menu_access = json_decode($partno[0]->menu_access,true);
         return $menu_access;
     }
     
     
     
   
     
     function menu_list($user_id,$username) {
         $menu_access =   $this->user_menu_data($user_id,$username);
         $status = '1';
         $arr_data = array();
         if(!empty($menu_access) && is_array($menu_access)) {
             foreach($menu_access as $key=>$value) {
                 if(!empty($key)) {
                     $query=$this->db->query("select name from menu where status='$status' and id = '$key'  order by priority ASC");
                     $menu =  $query->result();
                     if(!empty($value) && is_array($value))  {
                         foreach($value as $val) {
                             $query=$this->db->query("select name,folder_name,page_name from menu_sub where status ='$status' and id = '$val' and menu_id = '$key'  order by priority ASC");
                             $menu_data =  $query->result();
                             $temp_arra = array();
                             if(@$menu_data[0]->name){
                                 $temp_arra['name'] = $menu_data[0]->name;
                                 $temp_arra['folder_name'] = $menu_data[0]->folder_name;
                                 $temp_arra['page_name'] = $menu_data[0]->page_name;
                                 $arr_data[$menu[0]->name][] = $temp_arra;
                             }
                         }
                     }
                 }
             }

             return $arr_data;
         }
        
     }
     
     

     
     
     function user_restiction($user_id,$username) {
         $status = '1';
         $http = $_SERVER['PHP_SELF'];
         $pageName = explode("/", $http);
         $lenPage = sizeOf($pageName) - 1;
         $folder_name = sizeOf($pageName) - 2;
         $folder =  $pageName[$folder_name];
         $page =  explode('.',$pageName[$lenPage]);
         $page = $page['0'];
         $flags = 0;
         $query=$this->db->query("select id,menu_id  from menu_sub  where status = '$status' and page_name = '$page' and folder_name = '$folder'");
         $check_page= $query->result();
         $menu_access =   $this->user_menu_data($db,$user_id,$username);
         if(!empty($check_page) && is_array($check_page)) {
             $pag_id =  $check_page[0]->id;
             $menu_sub =  $menu_access[$check_page[0]->menu_id];
             if(in_array($pag_id,$menu_sub)) {
                 $flags = true;
             }
         }
         if($flags=='0')  {
             unset($_SESSION['user']);
             session_unset();
             session_destroy();
             header("Location:../index.php");
             die();
         }
     } 
     
     function getCaseDetailsCaseNodoc($cno,$year,$case_type){
         
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         if($user_id){
         $caseYear =  $year;
         $caseNo =  $cno;
         $case_type1 = $case_type;
         $detail = "DFR No Detail";
         $diaryYear1 = $caseYear;
         $clen = strlen($case_type1);
         $clength = 3 - $clen;
         for ($c = 0; $c < $clength; $c++){
         
             $case_type1 = "0" . $case_type1;
             $clen = strlen($caseNo);
             $clength = 7 - $clen;
             for ($c = 0; $c < $clength; $c++){
              
                 $caseNo = "0" . $caseNo;
                 if ($caseNo){
                     $caseNo = '';
                     $chr = 4;// this for first hard code digit of filing no
                     $c_no = $chr . $case_type1 . $caseNo . $caseYear;
                     $query_str="SELECT * FROM sat_case_detail  where case_no ='$c_no'";
                     $query=$this->db->query($query_str);
                     return $data = $query->result();
                 }
             }
         }
         }
     }
     

}
?>