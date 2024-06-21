<?php 
//use application\models\Caseallocation; 
defined('BASEPATH') OR exit('No direct script access allowed');
class Bench extends CI_Controller {
    function __construct() {
	error_reporting(0);
        parent::__construct();
        $this->load->model('Admin_model','admin_model');
        $this->load->model('Efiling_model','efiling_model');
		$this->userData = $this->session->userdata('login_success');
		$userLoginid = $this->userData[0]->username;
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

    
    
        function composition(){
			$data=[
                'benchNatures'=>$this->db->get_where('bench_nature',['display'=>true])->result_array(),
                'judges'=>$this->db->get_where('master_judge',['display'=>true])->result_array(),
                'no_of_judges'=>($this->input->Post('bench_nature'))?$this->db->get_where('bench_nature',['bench_code'=>$this->input->Post('bench_nature')])->result_array()[0]['no_of_judges']:'',
            ];
		
            $this->load->view("bench/bench_composition",$data);
        }
        
        
		 public function saveBench(){
		    $userdata=$this->session->userdata('login_success');
		    $user_id=$userdata[0]->id;
			if($this->input->post()):
			$this->form_validation->set_rules('from_list_date','Please enter from date','trim|required|min_length[1]|date|max_length[10]');
			if($this->form_validation->run() == FALSE){
			    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
			}
			
			$this->form_validation->set_rules('from_time','Please enter from time','trim|required|min_length[1]|max_length[8]|regex_match[/^[0-9 A-Z:]+$/]');
			if($this->form_validation->run() == FALSE){
			    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
			}
			
			$this->form_validation->set_rules('court_no','Please enter court number','trim|required|min_length[1]|max_length[4]|numeric');
			if($this->form_validation->run() == FALSE){
			    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
			}
			
			$this->form_validation->set_rules('bench_nature','Please enter bench nature','trim|required|min_length[1]|max_length[4]|numeric');
			if($this->form_validation->run() == FALSE){
			    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
			}

             $this->form_validation->set_rules('judge[]','Please select judge ','trim|required|min_length[1]|max_length[4]|numeric');
			if($this->form_validation->run() == FALSE){
			    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
			}      



			if($user_id){
    			$data=$this->input->Post();
			$tt=$data['judge'];
		/*	$spcl_char=['a-z'=>'','A-Z'=>'','<'=>'','>'=>'','/\/'=>'','\\'=>'','('=>'',')'=>'','!'=>'','^'=>'',"'"=>''];
 			$tt=htmlspecialchars(strtr($tt, $spcl_char));*/
    			
    			$data['presiding']=($this->input->Post('presiding'))?$data['judge'][$data['presiding']]:$data['judge'][1];
    			$data['from_list_date']=date('Y-m-d',strtotime($data['from_list_date']));
    			unset($data['judge']);
				
    			$this->db->insert('bench',$data);
    			$dd=$this->db->select("last_value")->get_where("bench_id_seq")->row_array();
    			 $lastid=$dd['last_value'];
    			foreach($tt as $key=>$j):
    				$jddata[$key]=[
        				'from_list_date'=>date('Y-m-d',strtotime($data['from_list_date'])),
        				'from_time'=>$data['from_time'],
        				'presiding'=>$data['presiding'],
        				'bench_nature'=>$data['bench_nature'],
        				'judge_code'=>$j,
        				'bench_id'=>$lastid,
    		        ];
    				endforeach;
					//print_r($jddata); die;
    			$this->db->insert_batch('bench_judge',$jddata);
				//echo $this->benchjudge->getLastQuery(); die;
    			$this->session->set_userdata('Success','Bench Created Successfully');	
    			redirect('composition');
			}
			endif;
			redirect('composition');
		}
		
		public function viewBench(){
            $data = '';
            if ($this->input->post('submit1')) :
                $this->form_validation->set_rules('from_date', 'from_date', 'required|date|max_length[10]');
                $this->form_validation->set_rules('to_date', ' to_date', 'required|date|max_length[10]');
                if ($this->form_validation->run() === false) :
                    $this->session->set_userdata('error', strip_tags(validation_errors()));
                    redirect('viewbench');
    			endif;
    
                $data = [
                    'benchNatures' => $this->db->get_where('bench_nature', [
                        'display' => true
                    ])->result_array(),
                    'judges' => $this->db->get_where('master_judge', [
                        'display' => true
                    ])->result_array()
                ];
                $params = [
                    'from_list_date >=' => date('Y-m-d', strtotime($this->input->Post('from_date'))),
                    'from_list_date <=' => date('Y-m-d', strtotime($this->input->Post('to_date')))
                ];
                $data['benches'] = $this->db->get_where('bench', $params)->result_array();
    		endif;
    
            $this->load->view('bench/viewBench', $data);
        }
    
    
    
		public function removeBench(){
		    $userdata=$this->session->userdata('login_success');
		    $user_id=$userdata[0]->id;
		    if($user_id){
    			$benchID=$this->input->post('id');
    			$this->form_validation->set_rules('id','Please valid id','trim|required|min_length[1]|max_length[4]|numeric');
    			if($this->form_validation->run() == FALSE){
    			    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
    			}
    			$this -> db -> where('id', $benchID);
    			$this -> db -> delete('bench');
    			$this -> db -> where('bench_id', $benchID);
    			$this -> db -> delete('bench_judge');
		    }
		}
		
		
		
		public function createListing($id){
			$caseDetails=$this->db->get_where('sat_case_detail',['filing_no'=>$id])->row_array();
				if(!empty($caseDetails)):
				$fil_no=$caseDetails['filing_no'];
						$caseAllocations=$this->db->get_where('case_allocation',['filing_no'=>$fil_no])->row_array();
				(isset($caseDetails['case_type']))? $benNT['case_type']=$caseDetails['case_type']:'';
			endif;
			$data=[
			   'filing_no'=>(isset($caseDetails['filing_no']))?$caseDetails['filing_no']:'',
			   'pet_name'=>(isset($caseDetails['pet_name']))?$caseDetails['pet_name']:'',
			   'res_name'=>(isset($caseDetails['res_name']))?$caseDetails['res_name']:'',
			   'next_list_date'=>(isset($caseAllocations['listing_date']))?date('d-m-Y',strtotime($caseAllocations['listing_date'])):'',
			   'label'=>(isset($caseAllocations['listing_date'])!=null)?'Next Hearing Date':'First Hearing Date',
			   'purposeArray'=>$this->db->get_where('master_purpose',['display'=>true])->result_array(),
			   'benchNatureArray'=>$this->db->get_where('bench_nature',['display'=>true])->result_array(),
			   'getBenchNatureListArray'=>[''=>'SELECT'],
			   'getRemarksArray'=>$this->db->get_where('remarks',['display'=>true])->result_array(),
			];
			if(!empty($caseDetails)):
				$data['benches']= $this->db->get_where('bench')->result_array();
			endif; 
			$this->load->view('bench/caselisting',$data);
		}
		
		public function listingaction(){
			if($this->input->post()):
			$this->form_validation->set_rules('listing_date', 'from_list_date', 'required|date|max_length[10]'); 
			$this->form_validation->set_rules('remarks', 'remarks', 'required|max_length[1500]'); 
			$this->form_validation->set_rules('purpose', 'purpose', 'required|max_length[2]|numeric'); 
			$this->form_validation->set_rules('bench_nature', 'bench_nature', 'required|max_length[2]|numeric'); 
			$this->form_validation->set_rules('filing_no', 'filing_no', 'required|max_length[15]|numeric'); 
			if($this->form_validation->run()===false):
				$this->session->set_userdata('error',strip_tags(validation_errors())); 
				redirect('createListing');
			endif;
			$data=$this->input->Post();
			$dat=$data['filing_no'];
			$data['listing_date']= date('Y-m-d',strtotime($data['listing_date']));
			$caseAllocations=$this->db->get_where('case_allocation',['filing_no'=>$dat])->row_array();
			if(!empty($caseAllocations)){
			$this->db->insert('case_allocation_his',$caseAllocations);
				$this->db->where('filing_no', $dat);
				$this->db->update('case_allocation', $data);
			} else {				
					$this->db->insert('case_allocation', $data);
					}
			$this->session->set_userdata('Success','Case Listed Successfully');	
			endif;
			redirect('freshlisting');
		}
		
		public function create_causelist()
		{
				//print_r($_POST); die;
		   	 	$data=[];
		   //if($this->input->post('submit1')):
		   //echo  "firstsummit"; //die;
				//$this->form_validation->set_rules('listingdatre', 'from_date', 'required|date|max_length[10]'); 
			/*if($this->form_validation->run()===false):
				$this->session->set_userdata('error',strip_tags(validation_errors())); 
				redirect('create_causelist', 'refresh');
			endif;*/
			$listingdate=date('Y-m-d'); 
			if ($this->input->post('listingdatre')!='') { 
			$listingdate=date('Y-m-d',strtotime($this->input->post('listingdatre')));		}
			$data['filedcase']= $this->db->get_where('case_allocation',['listing_date'=>$listingdate])->result_array();
			//print_r($data['filedcase']); //die;
			//endif;
			if($this->input->post('submit')):
			//print_r($_POST); die;
			$listingdate=date('Y-m-d',strtotime($this->input->post('listingdate')));
			$data['filedcase']= $this->db->get_where('case_allocation',['listing_date'=>$listingdate])->result_array();
			$this->db->delete('cause_list',['listing_date'=>$listingdate]);
			$this->db->insert_batch('cause_list',$data['filedcase']);
			endif;
			$this->load->view("bench/create_cause_list",$data);
				
		}
		
		public function causelist(){
			$data='';
			if($this->input->post()):
				$this->form_validation->set_rules('listingdatre', 'from_date', 'required|date|max_length[10]'); 
			if($this->form_validation->run()===false):
				$this->session->set_userdata('error',strip_tags(validation_errors())); 
				redirect('cause_list', 'refresh');
			endif;
			endif;
            $this->load->view("bench/cause_list",$data);
		}
		
		
		
		public function draftcauselist($date){
			$userdata=$this->session->userdata('login_success');
            $user_id=$userdata[0]->id;
            $strtoime=strtotime($date);
            if($strtoime){
                if($user_id){
                    $vals=explode('-', $date);
                    $listingdate=$vals['2'].'-'.$vals['1'].'-'.$vals['0'];
                    		   $data=['listing_date'=>  $listingdate, ];
                    	 $data['benches']= $this->db->get_where('bench',['from_list_date'=>$listingdate])->result_array();
						// print_r($data); die;
                    $this->load->view("bench/draftcl",$data);
        		}
            }else{
                $this->load->view('admin/notfound'); 
            }
		}
    }
