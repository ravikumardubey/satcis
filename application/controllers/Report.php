<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller {
    function __construct() {

error_reporting(0);

        parent::__construct();
        $this->load->model('Admin_model','admin_model');
        $this->load->model('Efiling_model','efiling_model');
		//$this->userData = $this->session->userdata('login_success');
		//$userLoginid = $this->userData[0]->loginid;
		/*if(empty($userLoginid)){
			//redirect(base_url(),'refresh');
		}*/
        
        $uri_request=$_SERVER['REQUEST_URI'];
        $url_array=explode('?',$uri_request);
        $parameters=@$url_array[1];
        $parameters_array=explode('&',$parameters);
        $spcl_char=['<'=>'','>'=>'','/\/'=>'','\\'=>'','('=>'',')'=>'','!'=>'','^'=>'',"'"=>''];
        for($i=0; $i < count($parameters_array); $i++) :;
        $getPara_array=explode("=",$parameters_array[$i]);
        $paraName=@$getPara_array[0];
        $getPvalue=@$getPara_array[1];
        $_REQUEST[$paraName]=htmlspecialchars($getPvalue);
        endfor;
        foreach($_REQUEST as $key=>$val):;
        if(is_array($val)){
            foreach($val as $key1=>$val1):;
            if(is_array($val1)){
                foreach($val1 as $key2=>$val2):;
                if(is_array($val2)) {
                    $innerData[$key1][$key2]=$val2;
                }
                else    $innerData[$key1][$key2]=htmlspecialchars(strtr($val2, $spcl_char));
                endforeach;
            }
            else $innerData[$key1]=htmlspecialchars(strtr($val1, $spcl_char));
            endforeach;
            $_REQUEST[$key]=$innerData;
        }
        else $_REQUEST[$key]=htmlspecialchars(strtr($val, $spcl_char));
        endforeach;
    }
    
    
    
    function casestatus(){
			$data='';
			if($this->input->post('button1')):
			 //echo "hiiiii"; die;
			$token_no = $this->input->post('token_no');
			$token_year = $this->input->post('token_year');
			//echo $token_no; echo $token_year; die;
			$filing_no= '10001'.str_pad($token_no,6,'0',STR_PAD_LEFT).$token_year;
			//echo $filing_no; die;
            $tt=$this->db->query("select * from sat_case_detail where filing_no='$filing_no'")->row_array();
            $caseall=$this->db->query("select * from case_allocation where filing_no='$filing_no'")->row_array();
			//echo $this->db->last_query();
			//print_r($tt); die;
            $ttt=$this->db->query("select * from efile_documents_upload where filing_no='$filing_no'")->result_array();
			$data = [
			'filedcase' => $tt,
			'casealo' => $caseall,
			'docs' => $ttt,
		      ];
			endif;
            $this->load->view("report/casestatus",$data);
        }
    }


