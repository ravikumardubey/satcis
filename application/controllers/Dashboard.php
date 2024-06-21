<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Dashboard extends CI_Controller {
	    function __construct() {
	        parent::__construct();
	        $this->load->model('Admin_model','admin_model');
	        $this->load->model('Efiling_model','efiling_model');
	        error_reporting(0);
	        date_default_timezone_set('Asia/Calcutta');
	        $this->log_file_name='./logfile/log.txt';
	        
	        $config = array(
	            'img_path'      => 'asset/captcha_images/',
	            'img_url'       => base_url().'asset/captcha_images/',
	            'font_path'     => FCPATH.'asset/fonts/texb.ttf',
	            'img_width'     => 150,
	            'img_height'    => 37,
	            'word_length'   => 6,
	            'font_size'     => 18,
	            'pool'          => '0123456789',
	            'captcha_case_sensitive' => TRUE,
	            'colors' => array(
	                'background' => array(79, 255, 255),
	                'border' => array(79, 255, 255),
	                'text' => array(0, 0, 0),
	                'grid' => array(79, 255, 255)
	            )
	        );
	        $captcha = create_captcha($config);
	        $this->captcha_data=$captcha; 
			$this->userData = $this->session->userdata('login_success');
			$userLoginid = $this->userData[0]->loginid;
			if(empty($userLoginid)){
				redirect(base_url(),'refresh');
			}
			
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
	    
	    function case_filing_steps(){
	        $this->load->view('admin/case_filing_steps',$data);
	    }
	    
	    
}