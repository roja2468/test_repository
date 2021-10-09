<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Api extends CI_Controller{
    public function __construct() {
            parent::__construct();
            $sv     =   $this->api_model->checkAuthorizationvalid();
            $dta    =   $this->api_model->jsonencode("0","Authorization key Invalid");
            if($sv != "1"){
                echo $dta;
                exit;
            }
    }
    public function countries(){
        $sv    =   $this->api_model->checkAuthorizationvalid();
        $dta   =   $this->api_model->jsonencode("0","Authorization key Invalid");
        if($sv == "1"){
            $ppunt  =   array(
                "countries"     =>  $this->api_model->countries()
            );
            $dta   =   $this->api_model->jsonencode("1",$ppunt);
        }
        echo ($dta);
    }
    public function register(){
        $sv    =   $this->api_model->checkAuthorizationvalid();
        $dta   =   $this->api_model->jsonencode("0","Authorization key Invalid");
        if($sv == "1"){
            $dta	=	$this->api_model->jsonencode("1","Some fields are required");
            if($this->input->post('mobile_no')!='' && $this->input->post('email')!='' && $this->input->post('password')!=''){
                $vvpl   =   $this->api_model->checkUnique();
                $dta	=	$this->api_model->jsonencode("2","Email or Mobile no already exists");
                if(!$vvpl){
                    $dta	=   $this->api_model->jsonencode("3","Not registered.Please try again");
                    $thi        =   $this->api_model->signup();
                    if($thi){
                        $dta	=   $this->api_model->jsonencode("4","OTP has been sent successfully");
                    }
                }
            }
        }
        echo ($dta);
    }
    public function sendotp(){
        $json       =   $this->api_model->jsonencode("1","Mobile No. is required");
        if($this->input->post("mobile_no") != ""){ 
            $eck  =   $this->api_model->checkregacstatus();
            if($eck){
              $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            }else {
                $jon        =   $this->api_model->sendotp();
                $json       =   $this->api_model->jsonencode('3', "OTP has been not sent.Please try again");
                if($jon){
                    $json   =   $this->api_model->jsonencode('4', "OTP has been sent successfully");
                } 
            }
        }
        echo ($json);
    }
    public function otp_verify(){ 
        $json   =   $this->api_model->jsonencode("1","Mobile No. and OTP are required");
        if($this->input->post("mobile_no") != "" && $this->input->post("otp_no") != ""){
            $eck  =   $this->api_model->checkregacstatus();
            if($eck != 1){
                $json   =   $this->api_model->jsonencode('2', "OTP has been expired or not valid");
                $ins    =   $this->api_model->verifyotp();
                if($ins){					
                    $profile    =   $this->api_model->getProfile();
                    $json       =   $this->api_model->jsonencode('5', "Update Basic details");
                    if($profile["register_name"] != ""){
                        $json       =   $this->api_model->jsonencode('3', $profile);
                    }
                }
            }else {
                $json   =   $this->api_model->jsonencode("4","Mobile No. has been blocked.Please contact administrator");
            }
        }
        echo $json;
    }
    public function login(){
        $dta	=	$this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post('email')!= '' && $this->input->post('password') != ''){
            $dta    =   $this->api_model->jsonencode('2',"Email Id does not exist.");
            $res    =   $this->api_model->login();
            if($res == 2){
                $dta    =   $this->api_model->jsonencode('2',"Please contact Administrator as your Profile blocked");
            }else if($res == 1){
                $profile    =   $this->api_model->getProfile();
                $dta        =   $this->api_model->jsonencode('4', "Update Basic details");
                if($profile["register_otp"] == "0"){
                    $dta       =   $this->api_model->jsonencode('5',"OTP Not verified");
                }
                if($profile["register_name"] != ""){
                    $dsta       =   $this->api_model->dashboard();
                    $dta       =   $this->api_model->jsonencode('3', $dsta);
                }
            }else{
                $dta    =   $this->api_model->jsonencode('2',"Password Incorrect");
            }
        }
        echo ($dta);
    }
    public function update_basic_details(){
        $dta	=	$this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post('full_name')!='' && $this->input->post('email')!='' && $this->input->post('age')!='' && $this->input->post('gender')!=''){
            $dta   =   $this->api_model->jsonencode('2',"Not updated full details please try again");
            $res    =   $this->api_model->add_basic_details();
            if($res){
                $dash   =   $this->api_model->dashboard();
                $dta    =   $this->api_model->jsonencode('3',$dash);
            }
        }
        echo ($dta);
    }
    public function logout(){
        $dta	=	$this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post('email')  !=  ''){
            $dta    =   $this->api_model->jsonencode('2',"Logout failed");
            $res    =   $this->api_model->logout();
            if($res){
                $dta   =   $this->api_model->jsonencode('3',"Logged out Successfully");
            }
        }
        echo ($dta);
    }
    public function splash(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
                $dsta       =   $this->api_model->dashboard();
                $json       =   $this->api_model->jsonencode('3',$dsta);
            }
            if($eck == 2){
                $json    =   $this->api_model->jsonencode('4',"OTP Not verified");
            }
        }
        echo $json;
    }
    public function submodule(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != "" && $this->input->post("module_id") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No Sub modules are available");
                $dsta    =   $this->api_model->submodules(0);
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function submoduleview(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != "" && $this->input->post("module_id") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No Sub modules are available");
                $dsta    =   $this->api_model->submodules(1);
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function blogs(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != "" && $this->input->post("module_id") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No Blog data are available");
                $dsta    =   $this->api_model->blogs();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function blogsview(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != "" && $this->input->post("blog_id") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();

            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No Blog data are available");
                $blog_view_check = $this->api_model->blog_view_check();
                print_r($blog_view_check);exit;
                $dsta    =   $this->api_model->blogsid();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function questions(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != "" && $this->input->post("module_id") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No questions data are available");
          		if($this->input->post("module") != "" && $this->input->post("qa_question") != ""){
              	    $dsta    =   $this->api_model->createqueries();
              	}
                $dsta    =   $this->api_model->questions();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function homepackages(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != "" && $this->input->post("module_id") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No Home Packages data are available");
                $dsta    =   $this->api_model->homepackages();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function hometest(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != "" && $this->input->post("module_id") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No Home Tests data are available");
                $dsta    =   $this->api_model->hometest();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function getPackages(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != "" && $this->input->post("package_id") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No Home Packages data are available");
                $dsta    =   $this->api_model->getPackages();
              	if(is_array($dsta) && count($dsta) > 0){
                    $dstav   =    array(
                        "package"   =>  $dsta,
                        "items"     =>  $this->api_model->subitems()
                    );
                    $json    =   $this->api_model->jsonencode('3',$dstav);
                }
            }
        }
        echo $json;
    }
    public function wellness(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No Wheel of Wellness are available");
                $dsta    =   $this->api_model->wellness();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function consultation(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != "" && $this->input->post("module_id") != "" && $this->input->post("sub_module_id") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No consultations data are available");
                $dsta    =   $this->api_model->consultation();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function consultationview(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != "" && $this->input->post("regvendor_id") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No consultations data are available");
                $dsta    =   $this->api_model->consultationview();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function consultationpackages(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != "" && $this->input->post("regvendor_id") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No consultations packages are available");
                $dsta    =   $this->api_model->consultationpackages();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function chatroom(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
              	$json    =   $this->api_model->jsonencode('4',"No Messages are available");
              	if($this->input->post("message")  != ""){
              	    $dta    =   $this->api_model->chatroom_create();    
              	}
                $dsta    =   $this->api_model->chatroom();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function symptoms_checker(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
            	$json    =   $this->api_model->jsonencode('4',"No list are available");
                $dsta    =   $this->api_model->symptoms_checker();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function consultdoctors(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != ""){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
            	$json    =   $this->api_model->jsonencode('4',"No list are available");
                $dsta    =   $this->api_model->consultdoctors();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function healthsymptoms(){
        $json   =   $this->api_model->jsonencode("1","Some fields are required");
        if($this->input->post("email") != "" && $this->input->post("healthcategory_id") != ''){
            $json   =   $this->api_model->jsonencode("2","Mobile No. has been blocked.Please contact administrator");
            $eck  =   $this->api_model->checkregacstatus();
            if($eck == 3){
            	$json    =   $this->api_model->jsonencode('4',"No Health Symptoms are available");
                $dsta    =   $this->api_model->viewsubCategory();
              	if(is_array($dsta) && count($dsta) > 0){
                	$json    =   $this->api_model->jsonencode('3',$dsta);
                }
            }
        }
        echo $json;
    }
    public function __destruct() {
            $this->db->close();
    }
}