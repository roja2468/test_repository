<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Api2_model extends CI_Model{
        public function vendorsview(){
            $target_dir             =   base_url().$this->config->item("upload_dest")."modules/";
            $conditions["columns"]  =   "vendor_id,vendor_name,concat('".$target_dir."',vendor_background) as  vendor_background,concat('".$target_dir."',vendor_profile_icon) as  vendor_profile_icon,concat('".$target_dir."',vendor_icon) as  vendor_icon,vendor_profile_create,vendor_stage_1,vendor_stage_2";
            $conditions["whereCondition"]  =   "vendor_acde = 'Active' and vendor_id = '".$this->input->post("vendor_id")."'";
            $vspl   =   $this->vendor_model->getVendors($conditions);
            if(is_array($vspl) && count($vspl) > 0){
                $vs     =   $sv     =   array();
                $moduleapi      =   $this->config->item("moduleapi");
                $s1     =  ($vspl["vendor_stage_1"] != "")?array_filter(explode(",",$vspl["vendor_stage_1"])):array();
                if(is_array($s1) && count($s1) > 0){
                    foreach($s1 as $slo =>  $sr){
                        $vs[$slo]["key"]        =   $sr;
                        $vs[$slo]["value"]      =   $moduleapi[$sr];
                    }
                }
                $s2     =  ($vspl["vendor_stage_2"] != "")?array_filter(explode(",",$vspl["vendor_stage_2"])):array();
                if(is_array($s2) && count($s2) > 0){
                    foreach($s2 as $so  =>  $sr){
                        $sv[$so]["key"]         =   $sr;
                        $sv[$so]["value"]       =   $moduleapi[$sr];
                    }
                }
                $vspl["vendor_stage_1"]     =   $vs;
                $vspl["vendor_stage_2"]     =   $sv;
                return $vspl;
            }
            return array();
        }
        public function submodules(){
            $vlsp   =   $this->input->post("vendor_id");
            if($this->input->post("vendor_id") == ""){
                $vpl    =   $this->api2_model->getProfile();
                $vlsp   =   $vpl["regvendor_vendor_id"];
            }
            $target_dir             =   base_url().$this->config->item("upload_dest")."modules/";
            $conditions["columns"]  =   "vendorsubmodule_id,vendorsubmodule_name,concat('".$target_dir."',vendorsubmodule_icon) as  vendorsubmodule_icon,vendorsubmodule_api";
            $conditions["whereCondition"]  =   "vendorsubmodule_acde = 'Active' and vendorsubmodule_vendor_id = '".$vlsp."'";
            return $this->sub_vendors_model->viewSub_vendor($conditions);
        }
        public function viewSpecialization(){
            $conditions["whereCondition"]  =   "specialization_acde = 'Active'";
            $conditions["columns"]  =   "specialization_id,specialization_name";
            return $this->specialization_model->viewSpecialization($conditions);
        }
        public function viewStates(){
            $conditions["tipoOrderby"]  =   "state_name";
            $conditions["order_by"]     =   "asc";
            $conditions["columns"]      =   "state_id,state_name";
            return $this->common_model->viewStates($conditions);
        }
        public function viewDistricts(){
            $conditions["tipoOrderby"]      =   "district_name";
            $conditions["order_by"]         =   "asc";
            $conditions["whereCondition"]   =   "state_id = '".$this->input->post("state_id")."'";
            $conditions["columns"]          =   "district_id,district_name";
            return $this->common_model->viewDistricts($conditions);
        }
        public function checkUnique(){
            $email     =   $this->input->post('email');
            $mobile    =   $this->input->post('mobile_no');
            $pms["whereCondition"]  =   "(regvendor_email_id = '".$email."' or regvendor_mobile = '".$mobile."')";
            $sleper     =   $this->vendor_registration_model->getRegistration($pms);
            if(is_array($sleper) && count($sleper) > 0){
                return true;
            }
            return false;
        }
        public function checkUniquevendor(){
            $email     =   $this->input->post('regvendor_id'); 
            $pms["whereCondition"]  =   "(regvendor_id = '".$email."')";
            $sleper    =   $this->vendor_registration_model->getRegistration($pms);
            if(is_array($sleper) && count($sleper) > 0){
                return true;
            }
            return false;
        }
        public function checkregacstatus(){
            $mobile    =   $this->input->post('mobile_no');
            $email     =   $this->input->post('email');
            $regvendor_id     =   $this->input->post('regvendor_id');
            
            if($regvendor_id != ""){
                $pms["whereCondition"]  =   "(regvendor_id = '".$regvendor_id."')";
            }
            if($email != ""){
                $pms["whereCondition"]  =   "(regvendor_email_id = '".$email."')";
            }
            if($mobile != ''){
                $pms["whereCondition"]  =   "(regvendor_mobile = '".$mobile."')";
            }
            $sleper     =   $this->vendor_registration_model->getRegistration($pms);
            if(is_array($sleper) && count($sleper) > 0){
                $clps   =   $sleper['regvendor_acde'];
                if($clps == "Deactive"){
                    return 1;
                }else{
                    if($sleper['regvendor_otp'] == 0){
                        return 2;   
                    }
                }
                return 3;
            }
            return 0;
        }
        public function login(){  
            $condition['whereCondition']    =   "regvendor_email_id  = '".$this->input->post('email')."' AND regvendor_password  = '".base64_encode($this->input->post('password'))."'";
            $res    =   $this->vendor_registration_model->getRegistration($condition);
            if(!empty($res) && count($res) > 0){
                $dta    =   array(
                    'regvendor_login_status' => "1",
                    'regvendor_login_time'   =>  date("Y-m-d H:i:s")
                );
                $this->db->update("register_vendors",$dta,array("regvendor_id" => $res["regvendor_id"]));
                $vsp   =    $this->db->affected_rows();
                if($vsp > 0){
                    return true;
                }
            }
            return FALSE;
        }
        public function logout(){
            $data   =   array(
                'regvendor_email_id'     	=> $this->input->post('email'),
            );
            $dta    =   array(
                'regvendor_login_status' =>  0,
                "regvendor_modified_on"  =>  date("Y-m-d H:i:s")
            );
            $this->db->update("register_vendors",$dta,$data);
            $vsp   =    $this->db->affected_rows();
            if($vsp > 0){
                return true;
            }
            return FALSE;
        }
        public function changepassword(){
            $vpl    =   $this->api2_model->getProfile();
            $vspl   =   $this->input->post('new_password');
            $spl    =   base64_decode($vpl["regvendor_password"]);
            if($spl   ==  $vspl){
                return 1;
            }
            $dta    =   array(
                'regvendor_password'    =>  base64_encode($vspl),
                "regvendor_modified_on" =>  date("Y-m-d H:i:s")
            );
            $this->db->update("register_vendors",$dta,array("regvendor_id" => $this->input->post('regvendor_id')));
            $vsp   =    $this->db->affected_rows();
            if($vsp > 0){
                return 2;
            }
            return 0;
        }
        public function getProfile($regvid  =   ""){     
            $condition['columns']           =   "*,state_name as regvendor_state,district_name as regvendor_city,specialization_name as regvendor_specialization";
            $condition['whereCondition']    =   "regvendor_mobile  LIKE '".$this->input->post('mobile_no')."'";
            if($this->input->post('email') != ""){
                $condition['whereCondition']    =   "regvendor_email_id  = '".$this->input->post('email')."'";
            }
            if($this->input->post('regvendor_id') != ""){
                $condition['whereCondition']    =   "regvendor_id  = '".$this->input->post('regvendor_id')."'";
            }
            if($regvid != ""){
                $condition['whereCondition']    =   "regvendor_id  = '".$regvid."'";
            }
            $res =  $this->vendor_registration_model->getRegistration($condition);
            if(is_array($res) && count($res) > 0){
                return $res;
            }
            return array();
        }
        public function signup(){
            $data = array(
                'regvendor_email_id'        => $this->input->post('email'),
                'regvendor_mobile'          => $this->input->post('mobile_no'),
                'regvendor_password'        => base64_encode($this->input->post('password')),
                'regvendor_created_on'      => date("Y-m-d H:i:s")
            );
            $this->db->insert("register_vendors",$data);
            $vsp   =    $this->db->insert_id();
            if($vsp > 0){
                $uniq   =   "VRN". str_pad($vsp, 6, "0", STR_PAD_LEFT);  
                $dat    =   array(
                                "regvendor_id"           =>  $vsp."USR",
                                "regvendor_unique"       =>  $uniq,
                                'regvendor_created_by'   =>  $vsp."USR"
                            );		
                $this->db->update("register_vendors",$dat,array("regvendorid" => $vsp));	
                $vpsl   =   $this->api_model->sendotp("1");
                return true;
            }
            return false;
        } 
        public function dashboard(){
            $profile    =   $this->api2_model->getProfile();
            return array(
                "regvendor_id"  =>  $profile["regvendor_id"],
                "vendors"       =>   $this->api_model->vendors(),
                "sub_modules"   =>  $this->api2_model->submodules(),
                "profile"       =>  $profile
            );
        }
        public function updateprofile(){
            $data = array(
                "regvendor_name"        => $this->input->post('full_name'),
                "regvendor_state"       => $this->input->post('state'),
                "regvendor_city"        => $this->input->post('city'),
                "regvendor_dob"         => ($this->input->post('regvendor_dob') != "")?date("Y-m-d",strtotime($this->input->post('regvendor_dob'))):"",
                "regvendor_age"     => ($this->input->post('regvendor_age') != "")?$this->input->post('regvendor_age'):"",
                "regvendor_gender"  => ($this->input->post('regvendor_gender') != "")?$this->input->post('regvendor_gender'):"",
                "regvendor_address" => ($this->input->post('regvendor_address') != '')?$this->input->post('regvendor_address'):"",
                "regvendor_phone"   => ($this->input->post('regvendor_phone') != '')?$this->input->post('regvendor_phone'):"",
                "regvendor_specialization"  => ($this->input->post('regvendor_specialization') != "")?$this->input->post('regvendor_specialization'):"",
                "regvendor_landline"        => ($this->input->post('regvendor_landline') != '')?$this->input->post('regvendor_landline'):"",
                "regvendor_registration_no" => ($this->input->post('regvendor_registration_no') != '')?$this->input->post('regvendor_registration_no'):"",
                "regvendor_registration_council" => ($this->input->post('regvendor_registration_council') != '')?$this->input->post('regvendor_registration_council'):"",
                "regvendor_year"            => ($this->input->post('regvendor_year') != '')?$this->input->post('regvendor_year'):"",
                "regvendor_experience_yrs"  => ($this->input->post('regvendor_experience_yrs') != '')?$this->input->post('regvendor_experience_yrs'):"",
                "regvendor_current_working" => ($this->input->post('regvendor_current_working') != '')?$this->input->post('regvendor_current_working'):"",
                "regvendor_gst_no"          => ($this->input->post('regvendor_gst_no') != '')?$this->input->post('regvendor_gst_no'):"",
                "regvendor_other_name"          => ($this->input->post('regvendor_other_name') != '')?$this->input->post('regvendor_other_name'):"",
                "regvendor_certification_name"      => ($this->input->post('regvendor_certification_name') != '')?$this->input->post('regvendor_certification_name'):"",
                "regvendor_verify_certificate_no"   => ($this->input->post('regvendor_verify_certificate_no') != '')?$this->input->post('regvendor_verify_certificate_no'):"",
                "regvendor_gym_no"                  => ($this->input->post('regvendor_gym_no') != "")?$this->input->post('regvendor_gym_no'):"",
                "regvendor_gym_certificate" => ($this->input->post('regvendor_gym_certificate') != "")?$this->input->post('regvendor_gym_certificate'):"",
                "regvendor_gym_description" => ($this->input->post('regvendor_gym_description') != "")?$this->input->post('regvendor_gym_description'):"",
                "regvendor_modified_on"     =>  date("Y-m-d H:i:s"),
                "regvendor_modified_by"     =>  $this->input->post("regvendor_id")
            );
            $vslvendo   =   $this->input->post('vendor_id');
            if($vslvendo != ""){
                $data["regvendor_vendor_id"]   =  $vslvendo;
            }
            $target_dir     =   $this->config->item("upload_dest");
            $direct         =   $target_dir."/vendors";
            if (file_exists($direct)){
            }else{mkdir($target_dir."/vendors");}
            $target_dir =   $this->config->item("upload_dest")."vendors/";
            if(count($_FILES) > 0){
                if(array_key_exists("regvendor_upload_registration",$_FILES)){
                    $regvendor_upload_registration      =   $_FILES["regvendor_upload_registration"]["name"]; 
                    if($regvendor_upload_registration != ''){
                        $filename   =   $_SERVER['DOCUMENT_ROOT'].'/'.$target_dir.'/'.$regvendor_upload_registration;
                        $vsp        =   explode(".",$regvendor_upload_registration);
                        $ect        =   end($vsp);  
                        if (file_exists($filename)) {
                            $regvendor_upload_registration      =   basename($regvendor_upload_registration,".".$ect)."_".date("YmdHis").".".$ect;
                        }
                        $uploadfile =   $target_dir . ($regvendor_upload_registration);
                        if (move_uploaded_file($_FILES['regvendor_upload_registration']['tmp_name'], $uploadfile)) {
                            $data['regvendor_upload_registration']  =   $regvendor_upload_registration;
                        }
                    }
                }
                
                if(array_key_exists("regvendor_upload_picture",$_FILES)){
                    $fname      =   $_FILES["regvendor_upload_picture"]["name"]; 
                    if($fname != ''){
                        $filename   =   $_SERVER['DOCUMENT_ROOT'].'/'.$target_dir.'/'.$fname;
                        $vsp        =   explode(".",$fname);
                        $ect        =   end($vsp);  
                        if (file_exists($filename)) {
                            $fname      =   basename($fname,".".$ect)."_".date("YmdHis").".".$ect;
                        }
                        $uploadfile =   $target_dir . ($fname);
                        if (move_uploaded_file($_FILES['regvendor_upload_picture']['tmp_name'], $uploadfile)) {
                            $data['regvendor_upload_picture']  =   $fname;
                        }
                    }
                }
                // echo "<pre>";print_r($_FILES);exit;
                if(array_key_exists("multiple_uploads", $_FILES)){
                    $surgical_videos      = count($_FILES['multiple_uploads']['name']); 
                    for($d=0 ; $d < $surgical_videos ; $d++ ) {
                        $tmpFilePath = $_FILES['multiple_uploads']['tmp_name'][$d];
                        if ($tmpFilePath != ""){
                            $dfname         =   $_FILES['multiple_uploads']['name'][$d]; 
                            $filename   =   $_SERVER['DOCUMENT_ROOT'].'/'.$target_dir.'/'.$dfname;
                            $vsp        =   explode(".",$dfname);
                            $ect        =   end($vsp);  
                            if (file_exists($filename)) {
                                $dfname      =   basename($dfname,".".$ect)."_".date("YmdHis").".".$ect;
                            }
                            $uploadfile =   $target_dir . ($dfname);
                            if(move_uploaded_file($tmpFilePath, $uploadfile)) {
                                $dta    =   array(
                                    "vendorpicture_name"            =>  $dfname,
                                    "vendorpicture_regvendor_id"    =>  $this->input->post("regvendor_id")
                                );
                                $this->db->insert("register_vendor_pictures",$dta); 
                            }
                        }
                    } 
                }
            }
            $this->db->update("register_vendors",$data,array("regvendor_id" => $this->input->post("regvendor_id")));	
            if($this->db->affected_rows() > 0){
                $dere       =   $this->input->post("degree");
                $year       =   $this->input->post("year");
                $university       =   $this->input->post("university");
                if($dere != ""){
                    $dtrrep     =   $this->common_config->strreplace($dere);
                    $utrrep     =   $this->common_config->strreplace($university);
                    $ytrrep     =   $this->common_config->strreplace($year);
                    $der        =   array_filter(explode(",",$dtrrep));
                    $uer        =   array_filter(explode(",",$utrrep));
                    $yer        =   array_filter(explode(",",$ytrrep));
                    if(is_array($der) && count($der) > 0){
                        foreach($der as $ve => $dg){
                            $cs[]   =   array(
                                "degreevendor_regvendor_id" =>  $this->input->post("regvendor_id"),
                                "degreevendor_degree"       =>  $dg,
                                "degreevendor_university"         =>  $uer[$ve],
                                "degreevendor_year"   =>  $yer[$ve],
                            );
                        }
                        $this->db->insert_batch("register_vendor_degree",$cs);
                    }
                }
                return true;
            }
            return false;
        } 
        public function stage_profile(){
            $data = array(
                "regvendor_gst_no"          => ($this->input->post('regvendor_gst_no') != '')?$this->input->post('regvendor_gst_no'):"",
                "regvendor_other_name"          => ($this->input->post('regvendor_other_name') != '')?$this->input->post('regvendor_other_name'):"",
                "regvendor_certification_name"      => ($this->input->post('regvendor_certification_name') != '')?$this->input->post('regvendor_certification_name'):"",
                "regvendor_verify_certificate_no"   => ($this->input->post('regvendor_verify_certificate_no') != '')?$this->input->post('regvendor_verify_certificate_no'):"",
                "regvendor_gym_no"                  => ($this->input->post('regvendor_gym_no') != "")?$this->input->post('regvendor_gym_no'):"",
                "regvendor_gym_certificate" => ($this->input->post('regvendor_gym_certificate') != "")?$this->input->post('regvendor_gym_certificate'):"",
                "regvendor_gym_description" => ($this->input->post('regvendor_gym_description') != "")?$this->input->post('regvendor_gym_description'):"",
                "regvendor_modified_on"     =>  date("Y-m-d H:i:s"),
                "regvendor_modified_by"     =>  $this->input->post("regvendor_id")
            );
            $target_dir     =   $this->config->item("upload_dest");
            $direct         =   $target_dir."/vendors";
            if (file_exists($direct)){
            }else{mkdir($target_dir."/vendors");}
            $target_dir =   $this->config->item("upload_dest")."vendors/";
            if(count($_FILES) > 0){
                if(array_key_exists("regvendor_upload_registration",$_FILES)){
                    $regvendor_upload_registration      =   $_FILES["regvendor_upload_registration"]["name"]; 
                    if($regvendor_upload_registration != ''){
                        $filename   =   $_SERVER['DOCUMENT_ROOT'].'/'.$target_dir.'/'.$regvendor_upload_registration;
                        $vsp        =   explode(".",$regvendor_upload_registration);
                        $ect        =   end($vsp);  
                        if (file_exists($filename)) {
                            $regvendor_upload_registration      =   basename($regvendor_upload_registration,".".$ect)."_".date("YmdHis").".".$ect;
                        }
                        $uploadfile =   $target_dir . ($regvendor_upload_registration);
                        if (move_uploaded_file($_FILES['regvendor_upload_registration']['tmp_name'], $uploadfile)) {
                            $data['regvendor_upload_registration']  =   $regvendor_upload_registration;
                        }
                    }
                }
                if(array_key_exists("regvendor_upload_picture",$_FILES)){
                    $fname      =   $_FILES["regvendor_upload_picture"]["name"]; 
                    if($fname != ''){
                        $filename   =   $_SERVER['DOCUMENT_ROOT'].'/'.$target_dir.'/'.$fname;
                        $vsp        =   explode(".",$fname);
                        $ect        =   end($vsp);  
                        if (file_exists($filename)) {
                            $fname      =   basename($fname,".".$ect)."_".date("YmdHis").".".$ect;
                        }
                        $uploadfile =   $target_dir . ($fname);
                        if (move_uploaded_file($_FILES['regvendor_upload_picture']['tmp_name'], $uploadfile)) {
                            $data['regvendor_upload_picture']  =   $fname;
                        }
                    }
                }
                if(array_key_exists("multiple_uploads", $_FILES)){
                    $surgical_videos      = count($_FILES['multiple_uploads']['name']); 
                    for( $d=0 ; $d < $surgical_videos ; $d++ ) {
                        $tmpFilePath = $_FILES['multiple_uploads']['tmp_name'][$d];
                        if ($tmpFilePath != ""){
                            $dfname         =   $_FILES['multiple_uploads']['name'][$d]; 
                            $filename   =   $_SERVER['DOCUMENT_ROOT'].'/'.$target_dir.'/'.$dfname;
                            $vsp        =   explode(".",$dfname);
                            $ect        =   end($vsp);  
                            if (file_exists($filename)) {
                                $dfname      =   basename($fname,".".$ect)."_".date("YmdHis").".".$ect;
                            }
                            $uploadfile =   $target_dir . ($dfname);
                            if(move_uploaded_file($tmpFilePath, $uploadfile)) {
                                $dta    =   array(
                                    "vendorpicture_name"            =>  $dfname,
                                    "vendorpicture_regvendor_id"    =>  $this->input->post("regvendor_id")
                                );
                                $this->db->insert("register_vendor_pictures",$dta); 
                            }
                        }
                    } 
                }
            }
            $this->db->update("register_vendors",$data,array("regvendor_id" => $this->input->post("regvendor_id")));	
            if($this->db->affected_rows() > 0){
                return true;
            }
            return false;
        } 
        public function ratingpanel(){
            $vpl    =   array(
                "vendorrating_regvendor_id"     =>  $this->input->post('regvendor_id'),
                "vendorrating_rating"           =>  $this->input->post('rating'),
                "vendorrating_rating_on"        =>  date("Y-m-d H:i:s")
            );
            $this->db->insert("vendor_rating",$vpl);
            if($this->db->insert_id() > 0){
                return true;
            }
            return false;
        }
        public function servicepackages(){
            $vspld  =   $this->input->post('regvendor_id');
            $vpl    =   array(
                "regpackage_service_name"           =>  $this->input->post('regpackage_service_name'),
                "regpackage_service_description"    =>  ($this->input->post('regpackage_service_description') != "")?$this->input->post('regpackage_service_description'):"",
                "regpackage_service_price"          =>  $this->input->post('regpackage_service_price'),
            );
            $target_dir     =   $this->config->item("upload_dest");
            $direct         =   $target_dir."/packages";
            if (file_exists($direct)){
            }else{mkdir($target_dir."/packages");}
            $target_dir =   $this->config->item("upload_dest")."packages/";
            if(count($_FILES) > 0){
                $fname      =   $_FILES["regpackage_service_picture"]["name"]; 
                if($fname != ''){
                    $filename   =   $_SERVER['DOCUMENT_ROOT'].'/'.$target_dir.'/'.$fname;
                    $vsp        =   explode(".",$fname);
                    $ect        =   end($vsp);  
                    if (file_exists($filename)) {
                        $fname      =   basename($fname,".".$ect)."_".date("YmdHis").".".$ect;
                    }
                    $uploadfile =   $target_dir . ($fname);
                    if (move_uploaded_file($_FILES['regpackage_service_picture']['tmp_name'], $uploadfile)) {
                        $vpl['regpackage_service_picture']  =   $fname;
                    }
                }
            }
            $regpackage_id  =   $this->input->post("regpackage_id");
            if($regpackage_id == ""){
                $vpl["regpackage_regvendor_id"] =   $vspld;
                $vpl["regpackage_created_on"]   =   date("Y-m-d H:i:s");
                $vpl["regpackage_created_by"]   =   $vspld;
                $this->db->insert("register_vendor_packages",$vpl);
                $regpackageid       =   $this->db->insert_id();
                if($regpackageid    > 0){
                    $daarata    =   array("regpackage_id" =>  $regpackageid.'VPG');
                    $this->db->update("register_vendor_packages",$daarata,array("regpackageid" => $regpackageid));
                    return true;
                }
            }
            else {
                $vpl["regpackage_modified_on"]   =   date("Y-m-d H:i:s");
                $vpl["regpackage_modified_by"]   =   $vspld;
                $this->db->update("register_vendor_packages",$vpl,array("regpackage_id" => $regpackage_id));
                return true;
            }
            return false;
        }
        public function deleteservicepackages(){
            $vspld  =   $this->input->post('regvendor_id');
            $regpackage_id                   =   $this->input->post('regpackage_id');
            $vpl["regpackage_open"]          =   0;
            $vpl["regpackage_modified_on"]   =   date("Y-m-d H:i:s");
            $vpl["regpackage_modified_by"]   =   $vspld;
            $this->db->update("register_vendor_packages",$vpl,array("regpackage_id" => $regpackage_id));
            if($this->db->affected_rows() > 0){
                return true;
            }
            return false;
        }
        public function packages(){
            $targee_dir             =   base_url()."uploads/image_not_available.png";
            $target_dir             =   base_url().$this->config->item("upload_dest")."packages/";
            $conditions["tipoOrderby"]  =   "regpackageid";
            $conditions["order_by"]     =   "desc";
            $conditions["columns"]      =   "regpackage_id,regpackage_service_name,(CASE WHEN regpackage_service_picture = '' THEN  '".$targee_dir."' ELSE concat('".$target_dir."',regpackage_service_picture) END) as  regpackage_service_picture,regpackage_service_description,regpackage_service_price";
            if($this->input->post('regvendor_id') != ""){
                $conditions["whereCondition"]  =   "regpackage_regvendor_id = '".$this->input->post('regvendor_id')."'";
            }
            if($this->input->post("regpackage_get") == "1"){
                $conditions["whereCondition"]  =   "regpackage_id = '".$this->input->post('regpackage_id')."'";
            }
            return $this->vendor_registration_model->viewPackages($conditions);
        }
        public function bankaccountslist(){
            $conditions["tipoOrderby"]  =   "regbankid";
            $conditions["order_by"]     =   "desc";
            $conditions["columns"]      =   "regbank_id,regbank_name,regbank_bank_name,regbank_ifsc,regbank_account_no,regbank_primary,regbank_branch";
            if($this->input->post('regvendor_id') != ""){
                $conditions["whereCondition"]  =   "regbank_regvendor_id = '".$this->input->post('regvendor_id')."'";
            }
            if($this->input->post('regbank_get') == 1){
                $conditions["whereCondition"]  =   "regbank_id = '".$this->input->post('regbank_id')."'";
            }
            return $this->vendor_registration_model->viewBanks($conditions);
        }
        public function bankaccount(){
            $vspld  =   $this->input->post('regvendor_id');
            $vpl    =   array(
                "regbank_name"          =>  $this->input->post('regbank_name'),
                "regbank_bank_name"     =>  $this->input->post('regbank_bank_name'),
                "regbank_ifsc"          =>  $this->input->post('regbank_ifsc'),
                "regbank_branch"          =>  $this->input->post('regbank_branch'),
                "regbank_account_no"    =>  $this->input->post('regbank_account_no'),
                "regbank_primary"       =>  ($this->input->post('regbank_primary') != "")?$this->input->post('regbank_primary'):"0",
            );
            $regpackage_id  =   $this->input->post("regbank_id");
            if($regpackage_id == ""){
                $vpl["regbank_regvendor_id"]    =   $vspld;
                $vpl["regbank_created_on"]   =   date("Y-m-d H:i:s");
                $vpl["regbank_created_by"]   =   $vspld;
                $this->db->insert("register_vendor_banks",$vpl);
                $regpackageid       =   $this->db->insert_id();
                if($regpackageid    > 0){
                    $daarata    =   array("regbank_id" =>  $regpackageid.'VBK');
                    $this->db->update("register_vendor_banks",$daarata,array("regbankid" => $regpackageid));
                    return true;
                }
            }
            else {
                $vpl["regbank_modified_on"]   =   date("Y-m-d H:i:s");
                $vpl["regbank_modified_by"]   =   $vspld;
                $this->db->update("register_vendor_banks",$vpl,array("regbank_id" => $regpackage_id));
                return true;
            }
            return false;
        }
        public function deleteBanks(){
            $vspld  =   $this->input->post('regvendor_id');
            $regpackage_id  =   $this->input->post("regbank_id");
            $vpl["regbank_open"]          =   0;
            $vpl["regbank_modified_on"]   =   date("Y-m-d H:i:s");
            $vpl["regbank_modified_by"]   =   $vspld;
            $this->db->update("register_vendor_banks",$vpl,array("regbank_id" => $regpackage_id));
            if($this->db->affected_rows() > 0){
                return true;
            }
            return false;
        }
        public function update_visible(){
            $vspld  =   $this->input->post('regvendor_id');
            $pro        =   $this->api2_model->getProfile();
            $ste        =   $pro["regbank_state_visible"];
            $cte        =   $pro["regbank_city_visible"];
            $dlo        =   array_unique(array_filter(explode(",",$pro["regbank_state_visible"])));
            $tsea       =   implode(",",$dlo);
            
            $clo        =   array_unique(array_filter(explode(",",$pro["regbank_city_visible"])));
            $csea       =   implode(",",$clo);
            $ct         =   $this->input->post('regbank_city_visible');
            $st         =   $this->input->post('regbank_state_visible');
            if(!in_array($st,$dlo)){
                $vpl["regbank_state_visible"]         =   $tsea.",".$st;
            }
            if(!in_array($ct,$clo)){
                $vpl["regbank_city_visible"]          =   $csea.",".$ct;
            }
            $vpl["regvendor_modified_on"]   =   date("Y-m-d H:i:s");
            $vpl["regvendor_modified_by"]   =   $vspld;
            $this->db->update("register_vendors",$vpl,array("regvendor_id" => $vspld));
            if($this->db->affected_rows() > 0){
                return true;
            }
            return false;
        }
        public function visibility(){
            $vspld  =   $this->input->post('regvendor_id');
            $pro        =   $this->api2_model->getProfile();
            $tsea       =   array_filter(explode(",",$pro["regbank_state_visible"]));
            $csea       =   array_filter(explode(",",$pro["regbank_city_visible"]));
            $vspsl   =   $vspl   =   array();
            if(is_array($tsea) && count($tsea) > 0){
                foreach($tsea as $te){
                    $vspl[]["state_name"]   =   $this->common_model->getStatenames($te);
                }
            }
            if(is_array($csea) && count($csea) > 0){
                foreach($csea as $ter){
                    $vspsl[]["city_name"]   =   $this->common_model->getDistrictsname($ter);
                }
            }
            return array(
                    "states"    =>  $vspl,
                    "cities"    =>  $vspsl
                );
        }
        public function create_bllog(){
            $vspld  =   $this->input->post('regvendor_id');
            $cpor   =   $this->getProfile();
            $pr     =   $cpor["vendor_module"];
            $vblo   =   $this->input->post("blog_id");
            if($vblo == ""){
                $data = array(
                        'blog_description'              =>  ($this->input->post('blog_description'))??'',
                        'blog_alias_name'               =>  $this->common_config->cleanstr($this->input->post('blog_title')),
                        'blog_title'                    =>  ucfirst($this->input->post('blog_title')),
                        'blog_seo_keywords'             =>  ($this->input->post('keywords'))??'',
                        'blog_seo_description'          =>  ($this->input->post('seo_description'))??'',
                        'blog_module_id'                =>  $pr,//$this->input->post('module'),
                        "blog_created_on"               =>  date("Y-m-d H:i:s"),
                        "blog_created_by"               =>  $vspld
                );
                $this->db->insert("blogs",$data);
                $vsp   =    $this->db->insert_id();
                if($vsp > 0){
                    $vblo   =   $vsp."BLG";
                    $dat    =   array("blog_id    "=> $vblo);	
                    $id     =   $vsp;	
                    $this->db->update("blogs",$dat,array("blogid" => $vsp));
                }
            }else{
                $data = array(
                        'blog_description'              =>  ($this->input->post('blog_description'))??'',
                        'blog_alias_name'               =>  $this->common_config->cleanstr($this->input->post('blog_title')),
                        'blog_title'                    =>  ucfirst($this->input->post('blog_title')),
                        'blog_seo_keywords'             =>  ($this->input->post('keywords'))??'',
                        'blog_seo_description'          =>  ($this->input->post('seo_description'))??'',
                        'blog_module_id'                =>  $pr,//$this->input->post('module'),
                        "blog_modified_on"               =>  date("Y-m-d H:i:s"),
                        "blog_modified_by"               =>  $vspld
                );
                $this->db->update("blogs",$data,array("blog_id" => $vblo));
            }
            $target_dir     =   $this->config->item("upload_dest");
            $direct         =   $target_dir."/blog";
            if (file_exists($direct)){
            }else{mkdir($target_dir."/blog");}
            if(count($_FILES) > 0){
                $total = count($_FILES['blog_image']['name']);
                for( $i=0 ; $i < $total ; $i++ ) {
                    $tmpFilePath = $_FILES['blog_image']['tmp_name'][$i];
                    if ($tmpFilePath != ""){    
                        $newFilePath = $direct."/".$_FILES['blog_image']['name'][$i];
                        if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                            $data = array(
                                "blog_image_blog_id" 			  => $vblo,
                                'blog_image_path'                 => $_FILES['blog_image']['name'][$i],
                                "blog_image_created_on"           => date("Y-m-d H:i:s"),
                                "blog_image_created_by"           => $vspld
                    
                            );
                            $this->db->insert("blog_image",$data);
                            $vsp   =    $this->db->insert_id();
                            if($vsp > 0){
                                $dat    =   array(
                                                "blog_image_id" 				=> $vsp."BLGI"
                                            );		
                                $this->db->update("blog_image",$dat,array("blog_imageid" => $vsp));
                            }
                        }
                     }
                } 
            }
            return true;     
        }
        public function delete_blogs(){
            $vspld  =   $this->input->post('regvendor_id');
            $blog_id    =   $this->input->post("blog_id");
            return $this->blog_model->delete_blog($blog_id,$vspld);
        }
        public function blogs(){
            $vspld  =   $this->input->post('regvendor_id');
            $oms["whereCondition"]  =   "blog_created_by = '".$vspld."'";// and blog_acde = 'Active'";
            if($this->input->post("blog_id") != "") {
                $oms["whereCondition"]  =   "blog_id = '".$this->input->post("blog_id")."'";
            }
            $oms["columns"]          =    "blog_id,blog_title,blog_alias_name,module_name,blog_description,blogid as blog_images,blog_created_on,blog_created_by";
            $res =  $this->blog_model->viewBlogs($oms);
            if(is_array($res) && count($res) > 0){
              	foreach($res as $ld  => $ve){
              	    $vol            =   $ve["blog_created_by"];
              	    $vplcr          =   $this->api2_model->getProfile($vol);
              	    $vtimerc        =   $vplcr["regvendor_name"];
              	    $timer          =   $ve["blog_created_on"];
              	    $vtimer         =   $this->common_config->get_timeago(strtotime($timer));
              	    
                  	$blog_image	=	base_url().$this->config->item("upload_dest")."blog/";
                  	$lsp	=	$this->db->select("concat('".$blog_image."',blog_image_path) as  blog_image_path")->get_where("blog_image",array("blog_image_blog_id" => $ve['blog_id']))->row_array();
                  
                	$res[$ld]["blog_images"]	    =	is_array($lsp)?$lsp["blog_image_path"]:"";
                  	$res[$ld]["blog_created_on"]	=	$vtimer;
                  	$res[$ld]["blog_created_by"]	=	ucwords($vtimerc);
                }
                return $res;
            }
            return array();
        }
        public function queries(){
            // $vspld  =   $this->input->post('regvendor_id');
            $oms["whereCondition"]  =   "qa_answer = '' and qa_acde = 'Active'";
            $oms["columns"]         =   "qa_id,qa_answer,qa_question";
            return $this->questions_model->viewQa($oms);
        }
        public function update_queries(){
            $str    =   $this->input->post("qa_id");
            $data = array(
                'qa_answer'             =>  $this->input->post('qa_answer'),
                "qa_modified_on"        =>  date("Y-m-d H:i:s"),
                "qa_modified_by"        =>  $this->session->userdata("login_id")
            );
            $this->db->update("questions",$data,array("qa_id" => $str));
            $vsp   =    $this->db->affected_rows();
            if($vsp > 0){
                return true;
            }
            return FALSE;
        }
        public function qualifications(){
            $regvendor_id                   =   $this->input->post("regvendor_id");
            $conditions["whereCondition"]   =   "regvendor_id = '".$regvendor_id."'";
            $conditions["columns"]          =   "degreevendor_degree,degreevendor_university,degreevendor_year";
            $cpsl   =   $this->vendor_registration_model->viewDegrees($conditions);
            if(is_array($cpsl) && count($cpsl) > 0){
                $k  =   0;
                foreach($cpsl as $ck){
                    $k  =   $k+1;
                    $ckl["degreevendor_university".$k]  =   $ck['degreevendor_university'];
                    $ckl["degreevendor_year".$k]    =   $ck['degreevendor_year'];
                    $ckl["degreevendor_degree".$k]  =   $ck['degreevendor_degree'];
                }
            }
            return $ckl;
        }
        public function register_availability(){
            $regvendor_id                   =   $this->input->post("regvendor_id");
            $conditions["whereCondition"]   =   "regvendor_id = '".$regvendor_id."'";
            $conditions["columns"]          =   "availability_type,(case when availability_from_time <> '00:00:00' then date_format(availability_from_time,'%h:%i %p') else '' end) as availability_from_time,(case when availability_date <> '0000-00-00' then date_format(availability_date,'%d-%m-%Y') else '' end) as availability_date,availability_day,(case when availability_to_time <> '00:00:00' then date_format(availability_to_time,'%h:%i %p') else availability_to_time End) as availability_to_time,availability_available";
            return $this->vendor_registration_model->viewAvailability($conditions);
        }
        public function insert_availability(){
            $dat        =   array(
                            	"availability_regvendor_id" =>  $this->input->post("regvendor_id"),
                            	"availability_type"         =>  $this->input->post("availability_type"),
                            	"availability_from_time"    =>  ($this->input->post("availability_from_time") != "")?date("H:i:s",strtotime($this->input->post("availability_from_time"))):"",
                            	"availability_date"         =>  ($this->input->post("availability_date") != "")?date("Y-m-d",strtotime($this->input->post("availability_date"))):"",
                            	"availability_day"          =>  ($this->input->post("availability_day") != "")?$this->input->post("availability_day"):"",
                            	"availability_to_time"      =>  ($this->input->post("availability_to_time") != "")?date("H:i:s",strtotime($this->input->post("availability_to_time"))):"",
                    	        "availability_available"    =>  $this->input->post("availability_available")
                	        );
	        $this->db->insert("register_availability",$dat);
	        if($this->db->insert_id() > 0){
	            return true;
	        }
	        return false;
        }
        public function vital_nursemedications(){
            $vspld      =   $this->input->post("regvendor_id");
            $vpl        =   array(
                                "vital_registration_id"           =>  $this->input->post("registration_id"),
                            	"vital_bp"           =>  ($this->input->post("vital_bp") != "")?$this->input->post("vital_bp"):"", 
                            	"vital_weight"          =>  ($this->input->post("vital_weight") != "")?$this->input->post("vital_weight"):"", 
                            	"vital_pulse"           =>  ($this->input->post("vital_pulse") != "")?$this->input->post("vital_pulse"):"", 
                            	"vital_temperature"     =>  ($this->input->post("vital_temperature") != "")?$this->input->post("vital_temperature"):"", 
                            	"vital_sugar_level"     =>  ($this->input->post("vital_sugar_level") != "")?$this->input->post("vital_sugar_level"):""
                	        );
	        $regpackage_id  =   $this->input->post("vital_id");
            if($regpackage_id == ""){
                $vpl["vital_created_on"]   =   date("Y-m-d H:i:s");
                $vpl["vital_created_by"]   =   $vspld;
                $this->db->insert("vital_medications",$vpl);
                $regpackageid       =   $this->db->insert_id();
                if($regpackageid    > 0){
                    $regpackage_id        =   $regpackageid.'VBK';
                    $uniq       =   "VRN". str_pad($regpackageid, 6, "0", STR_PAD_LEFT);
                    $daarata    =   array("vital_id" =>  $regpackage_id,"vital_unique" => $uniq);
                    $this->db->update("vital_medications",$daarata,array("vitalid" => $regpackageid));
                }
            }
            else {
                $vpl["vital_modified_on"]   =   date("Y-m-d H:i:s");
                $vpl["vital_modified_by"]   =   $vspld;
                $this->db->update("vital_medications",$vpl,array("vital_id" => $regpackage_id));
            }
	        if($this->db->affected_rows() > 0){
	            $ooms["columns"]        =   'vital_id,vital_unique,vital_registration_id,vital_bp,vital_weight,vital_pulse,vital_temperature,vital_sugar_level';
	            $ooms['whereCondition'] =   "vital_id = '".$regpackage_id."'";
	            return $this->vendor_vital_model->getVital($ooms);
	        }
	        return array();
        }
        public function vital_medications(){
            $vspld      =   $this->input->post("regvendor_id");
            $vpl        =   array(
                                "vital_registration_id"           =>  $this->input->post("registration_id"),
                            	"vital_diagnosis"           =>  ($this->input->post("vital_diagnosis") != "")?$this->input->post("vital_diagnosis"):"", 
                            	"vital_cheif_complaints"    =>  ($this->input->post("vital_cheif_complaints") != "")?$this->input->post("vital_cheif_complaints"):"", 
                            	"vital_illness"             =>  ($this->input->post("vital_illness") != "")?$this->input->post("vital_illness"):"", 
                            	"vital_past_history"        =>  ($this->input->post("vital_past_history") != "")?$this->input->post("vital_past_history"):"", 
                            	"vital_drug_history"        =>  ($this->input->post("vital_drug_history") != "")?$this->input->post("vital_drug_history"):"", 
                            	"vital_immunisations"       =>  ($this->input->post("vital_immunisations") != "")?$this->input->post("vital_immunisations"):"", 
                            	"vital_family_history"      =>  ($this->input->post("vital_family_history") != "")?$this->input->post("vital_family_history"):"", 
                            	"vital_personal_history"        =>  ($this->input->post("vital_personal_history") != "")?$this->input->post("vital_personal_history"):"", 
                            	"vital_general_examination"     =>  ($this->input->post("vital_general_examination") != "")?$this->input->post("vital_general_examination"):"", 
                            	"vital_systematic_examination"  =>  ($this->input->post("vital_systematic_examination") != "")?$this->input->post("vital_systematic_examination"):"", 
                            	"vital_provisional_diagonsis"   =>  ($this->input->post("vital_provisional_diagonsis") != "")?$this->input->post("vital_provisional_diagonsis"):"", 
                            	"vital_investigations"  =>  ($this->input->post("vital_investigations") != "")?$this->input->post("vital_investigations"):"", 
                            	"vital_remarks"         =>  ($this->input->post("vital_remarks") != "")?$this->input->post("vital_remarks"):""
                	        );
	        $regpackage_id  =   $this->input->post("vital_id");
            if($regpackage_id == ""){
                $vpl["vital_created_on"]   =   date("Y-m-d H:i:s");
                $vpl["vital_created_by"]   =   $vspld;
                $this->db->insert("vital_medications",$vpl);
                $regpackageid       =   $this->db->insert_id();
                if($regpackageid    > 0){
                    $regpackage_id        =   $regpackageid.'VBK';
                    $uniq       =   "VRN". str_pad($regpackageid, 6, "0", STR_PAD_LEFT);
                    $daarata    =   array("vital_id" =>  $regpackage_id,"vital_unique" => $uniq);
                    $this->db->update("vital_medications",$daarata,array("vitalid" => $regpackageid));
                }
            }
            else {
                $vpl["vital_modified_on"]   =   date("Y-m-d H:i:s");
                $vpl["vital_modified_by"]   =   $vspld;
                $this->db->update("vital_medications",$vpl,array("vital_id" => $regpackage_id));
            }
	        if($this->db->affected_rows() > 0){
	            $ooms["columns"]        =   'vital_id,vital_unique,vital_registration_id,vital_diagnosis,vital_cheif_complaints,vital_illness,vital_past_history,vital_drug_history,vital_immunisations,vital_family_history,vital_personal_history,vital_general_examination,vital_systematic_examination,vital_provisional_diagonsis,vital_investigations,vital_remarks';
	            $ooms['whereCondition'] =   "vital_id = '".$regpackage_id."'";
	            return $this->vendor_vital_model->getVital($ooms);
	        }
	        return array();
        }
        public function vital_bp(){
	        $regpackage_id  =   $this->input->post("vital_id");
            $vspld      =   $this->input->post("regvendor_id");
            $vpl        =   array(
                                "vital_bp"           =>  $this->input->post("vital_bp"),
                            	"vital_weight"          =>  ($this->input->post("vital_weight") != "")?$this->input->post("vital_weight"):"", 
                            	"vital_pulse"           =>  ($this->input->post("vital_pulse") != "")?$this->input->post("vital_pulse"):"", 
                            	"vital_temperature"     =>  ($this->input->post("vital_temperature") != "")?$this->input->post("vital_temperature"):"", 
                            	"vital_sugar_level"     =>  ($this->input->post("vital_sugar_level") != "")?$this->input->post("vital_sugar_level"):"", 
                            	"vital_spo2"            =>  ($this->input->post("vital_spo2") != "")?$this->input->post("vital_spo2"):"", 
                            	"vital_respiratory"     =>  ($this->input->post("vital_respiratory") != "")?$this->input->post("vital_respiratory"):""
                	        );
	        
            $vpl["vital_modified_on"]   =   date("Y-m-d H:i:s");
            $vpl["vital_modified_by"]   =   $vspld;
            $this->db->update("vital_medications",$vpl,array("vital_id" => $regpackage_id));
	        if($this->db->affected_rows() > 0){
	            $ooms["columns"]        =   'vital_id,vital_unique,vital_bp,vital_weight,vital_pulse,vital_temperature,vital_sugar_level,vital_spo2,vital_respiratory';
	            $ooms['whereCondition'] =   "vital_id = '".$regpackage_id."'";
	            return $this->vendor_vital_model->getVital($ooms);
	        }
	        return array();
        }
        public function vitalmedications(){
	        $regpackage_id  =   $this->input->post("vital_id");
            $vspld      =   $this->input->post("regvendor_id");
            $vpl        =   array(
                                "vital_medications"     =>  ($this->input->post("vital_medications") != "")?$this->input->post("vital_medications"):""
                	        );
	        
            $vpl["vital_modified_on"]   =   date("Y-m-d H:i:s");
            $vpl["vital_modified_by"]   =   $vspld;
            $this->db->update("vital_medications",$vpl,array("vital_id" => $regpackage_id));
	        if($this->db->affected_rows() > 0){
	            $ooms["columns"]        =   'vital_id,vital_unique,vital_medications';
	            $ooms['whereCondition'] =   "vital_id = '".$regpackage_id."'";
	            return $this->vendor_vital_model->getVital($ooms);
	        }
	        return array();
        }
        public function earnings(){
            return array(
                    "total_consultations"       =>  rand(1,99),
                    "total_amount_earned"       =>  rand(1111,9999),
                    "total_amount_transferred"  =>  rand(111,9999)
            );
        }
        public function transaction(){
            return array();
        }
        public function appointments(){
            return array();
        }
        public function products(){
            $produd     =   $this->input->post("product_id");
            $parms["order_by"]          =   "desc";
            $parms["tipoOrderby"]       =   "productid";
            $parms["whereCondition"]    =   "product_created_by = '".$this->input->post("regvendor_id")."'";
            if($produd != ""){
                $parms["whereCondition"]    =   "product_id = '".$produd."'";
            }
            $parms["columns"]           =   "product_id,product_unique,product_name,product_actual_price,product_offer_price,product_description,product_quantity,product_stock_availibility,'' as product_images";
            $vspl   =   $this->product_model->viewProduct($parms);
            if(is_array($vspl) && count($vspl) > 0){
                $target_dir     =   base_url().$this->config->item("upload_dest");
                $direct         =   $target_dir."products";
                foreach($vspl as $ky => $ve){
                    $pms["columns"]          =   "concat('".$direct."','/',product_image_path) as product_image_path";
                    $pms["whereCondition"]   =   "product_image_productid = '".$ve["product_id"]."'";
                    $sth        =   $this->product_model->viewProductImage($pms);
                    $vspl[$ky]['product_images']    =   $sth;
                }
            }
            return $vspl;
        }
        public function insert_products(){
            $vspld      =   $this->input->post("regvendor_id");
            $produd     =   $this->input->post("product_id");
            $dat        =   array(
                            	"product_name"          =>  $this->input->post("product_name"),
                            	"product_actual_price"          =>  ($this->input->post("actual_price") != "")?$this->input->post("actual_price"):"",
                            	"product_offer_price"           =>  ($this->input->post("offer_price") != "")?$this->input->post("offer_price"):"",
                            	"product_description"           =>  ($this->input->post("product_description") != "")?$this->input->post("product_description"):"",
                            	"product_quantity"              =>  ($this->input->post("quantity") != "")?$this->input->post("quantity"):"",
                            	"product_stock_availibility"    =>  ($this->input->post("stock_availibility") != "")?$this->input->post("stock_availibility"):"",
                	        );
            if($produd != ""){
                $dat["product_modified_by"]  =   date("Y-m-d H:i:s");
                $dat["product_modified_by"]  =   $vspld;
                $vblo   =  $produd;
                $this->db->update("products",$dat,array("product_id" => $produd));
                $vsp    =   $this->db->affected_rows();
                
            }else{
                $dat["product_created_on"]  =   date("Y-m-d H:i:s");
                $dat["product_created_by"]  =   $vspld;
                
    	        $this->db->insert("products",$dat);
    	        $vsp    =   $this->db->insert_id();
    	        
	            $uniq   =   "VRN". str_pad($vsp, 6, "0", STR_PAD_LEFT);  
	            $vblo   =   $vsp."PRD";
                $dat    =   array(
                                "product_id"           =>  $vblo,
                                "product_unique"       =>  $uniq
                            );		
                $this->db->update("products",$dat,array("productid" => $vsp)); 
            }
	        if($vsp > 0){
	            $target_dir     =   $this->config->item("upload_dest");
                $direct         =   $target_dir."/products";
                if (file_exists($direct)){
                }else{mkdir($direct);}
                if(count($_FILES) > 0){
                    $total = count($_FILES['product_images']['name']);
                    for( $i=0 ; $i < $total ; $i++ ) {
                        $tmpFilePath = $_FILES['product_images']['tmp_name'][$i];
                        if ($tmpFilePath != ""){    
                            $newFilePath = $direct."/".$_FILES['product_images']['name'][$i];
                            if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                                $data = array(
                                    "product_image_productid" 			  => $vblo,
                                    'product_image_path'                 => $_FILES['product_images']['name'][$i],
                                    "product_image_created_on"           => date("Y-m-d H:i:s"),
                                    "product_image_created_by"           => $vspld
                        
                                );
                                $this->db->insert("product_image",$data);
                                $vsp   =    $this->db->insert_id();
                                if($vsp > 0){
                                    $dat    =   array(
                                                    "product_image_id" 				=> $vsp."BLGI"
                                                );		
                                    $this->db->update("product_image",$dat,array("product_imageid" => $vsp));
                                }
                            }
                         }
                    } 
                }
	            return true;
	        }
	        return false;
        }
        public function deleteproducts(){
            $suri   =   $this->input->post("product_id");
            return $this->product_model->delete_Product($suri);
        }
        public function facilites(){
            $produd     =   $this->input->post("facilites_id");
            $parms["order_by"]          =   "desc";
            $parms["tipoOrderby"]       =   "facilitesid";
            $parms["whereCondition"]    =   "facilites_created_by = '".$this->input->post("regvendor_id")."'";
            if($produd != ""){
                $parms["whereCondition"]    =   "facilites_id = '".$produd."'";
            }
            
	        $target_dir     =   $this->config->item("upload_dest");
            $direct         =   $target_dir."products/";
            $csp    =   base_url().$direct;
            $parms["columns"]           =   "facilites_id,facilites_name,facilites_description,(case when facilites_images <> '' then concat('".$csp."',facilites_images) else '' end ) as  facilites_images";
            $vspl   =   $this->product_model->viewfacilites($parms);
            return $vspl;
        }
        public function insert_facilites(){
            $vspld      =   $this->input->post("regvendor_id");
            $produd     =   $this->input->post("facilites_id");
            $dat        =   array(
                            	"facilites_name"          =>  $this->input->post("facilites_name"),
                            	"facilites_description"           =>  ($this->input->post("facilites_description") != "")?$this->input->post("facilites_description"):"",
                	        );
	        $target_dir     =   $this->config->item("upload_dest");
            $direct         =   $target_dir."/products";
            if (file_exists($direct)){
            }else{mkdir($direct);}
            if(count($_FILES) > 0){
                $tmpFilePath    = $_FILES['facilities_images']['tmp_name'];
                $total          = $_FILES['facilities_images']['name'];
                if($total != "") {  
                    $newFilePath = $direct."/".$total;
                    if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $dat["facilites_images"]  =   $total;
                    }
                } 
            }
            if($produd != ""){
                $dat["facilites_modified_by"]  =   date("Y-m-d H:i:s");
                $dat["facilites_modified_by"]  =   $vspld;
                $vblo   =  $produd;
                $this->db->update("facilites",$dat,array("facilites_id" => $produd));
                $vsp    =   $this->db->affected_rows();
                
            }else{
                $dat["facilites_created_on"]  =   date("Y-m-d H:i:s");
                $dat["facilites_created_by"]  =   $vspld;
                
    	        $this->db->insert("facilites",$dat);
    	        $vsp    =   $this->db->insert_id();
    	          
	            $vblo   =   $vsp."PRD";
                $dat    =   array(
                                "facilites_id"           =>  $vblo
                            );		
                $this->db->update("facilites",$dat,array("facilitesid" => $vsp)); 
            }
	        if($vsp > 0){
	            return true;
	        }
	        return false;
        }
        public function deletefacilites(){
            $suri   =   $this->input->post("facilites_id");
            return $this->product_model->delete_facilites($suri);
        }
        /** specialities **/
        public function specialities(){
            $produd     =   $this->input->post("specialities_id");
            $parms["order_by"]          =   "desc";
            $parms["tipoOrderby"]       =   "specialitiesid";
            $parms["whereCondition"]    =   "specialities_created_by = '".$this->input->post("regvendor_id")."'";
            if($produd != ""){
                $parms["whereCondition"]    =   "specialities_id = '".$produd."'";
            }
            
	        $target_dir     =   $this->config->item("upload_dest");
            $direct         =   $target_dir."specialities/";
            $csp    =   base_url().$direct;
            $parms["columns"]           =   "specialities_id,specialities_name,specialities_doctors,specialities_description,(case when specialities_images <> '' then concat('".$csp."',specialities_images) else '' end ) as  specialities_images";
            $vspl   =   $this->product_model->viewspecialities($parms);
            return $vspl;
        }
        public function insert_specialities(){
            $vspld      =   $this->input->post("regvendor_id");
            $produd     =   $this->input->post("specialities_id");
            $dat        =   array(
                            	"specialities_name"          =>  $this->input->post("specialities_name"),
                            	"specialities_description"           =>  ($this->input->post("specialities_description") != "")?$this->input->post("specialities_description"):"",
                            	"specialities_doctors"           =>  ($this->input->post("specialities_doctors") != "")?$this->input->post("specialities_doctors"):"",
                	        );
	        $target_dir     =   $this->config->item("upload_dest");
            $direct         =   $target_dir."/specialities";
            if (file_exists($direct)){
            }else{mkdir($direct);}
            if(count($_FILES) > 0){
                $tmpFilePath    = $_FILES['specialities_images']['tmp_name'];
                $total          = $_FILES['specialities_images']['name'];
                if($total != "") {  
                    $newFilePath = $direct."/".$total;
                    if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $dat["specialities_images"]  =   $total;
                    }
                } 
            }
            if($produd != ""){
                $dat["specialities_modified_by"]  =   date("Y-m-d H:i:s");
                $dat["specialities_modified_by"]  =   $vspld;
                $vblo   =  $produd;
                $this->db->update("specialities",$dat,array("specialities_id" => $produd));
                $vsp    =   $this->db->affected_rows();
                
            }else{
                $dat["specialities_created_on"]  =   date("Y-m-d H:i:s");
                $dat["specialities_created_by"]  =   $vspld;
                
    	        $this->db->insert("specialities",$dat);
    	        $vsp    =   $this->db->insert_id();
    	          
	            $vblo   =   $vsp."PRD";
                $dat    =   array(
                                "specialities_id"           =>  $vblo
                            );		
                $this->db->update("specialities",$dat,array("specialitiesid" => $vsp)); 
            }
	        if($vsp > 0){
	            return true;
	        }
	        return false;
        }
        public function deletespecialities(){
            $suri   =   $this->input->post("specialities_id");
            return $this->product_model->delete_specialities($suri);
        }
        /** doctors **/
        public function doctors(){
            $produd     =   $this->input->post("doctors_id");
            $parms["order_by"]          =   "desc";
            $parms["tipoOrderby"]       =   "doctorsid";
            $parms["whereCondition"]    =   "doctors_created_by = '".$this->input->post("regvendor_id")."'";
            if($produd != ""){
                $parms["whereCondition"]    =   "doctors_id = '".$produd."'";
            }
            
	        $target_dir     =   $this->config->item("upload_dest");
            $direct         =   $target_dir."doctors/";
            $csp    =   base_url().$direct;
            $parms["columns"]           =   "doctors_id,doctors_name,doctors_availbility,doctors_description,(case when doctors_images <> '' then concat('".$csp."',doctors_images) else '' end ) as  doctors_images";
            $vspl   =   $this->product_model->viewdoctors($parms);
            return $vspl;
        }
        public function insert_doctors(){
            $vspld      =   $this->input->post("regvendor_id");
            $produd     =   $this->input->post("doctors_id");
            $dat        =   array(
                            	"doctors_name"          =>  $this->input->post("doctors_name"),
                            	"doctors_description"           =>  ($this->input->post("doctors_description") != "")?$this->input->post("doctors_description"):"",
                            	"doctors_availbility"           =>  ($this->input->post("doctors_availbility") != "")?$this->input->post("doctors_availbility"):"",
                	        );
	        $target_dir     =   $this->config->item("upload_dest");
            $direct         =   $target_dir."/doctors";
            if (file_exists($direct)){
            }else{mkdir($direct);}
            if(count($_FILES) > 0){
                $tmpFilePath    = $_FILES['doctors_images']['tmp_name'];
                $total          = $_FILES['doctors_images']['name'];
                if($total != "") {  
                    $newFilePath = $direct."/".$total;
                    if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $dat["doctors_images"]  =   $total;
                    }
                } 
            }
            if($produd != ""){
                $dat["doctors_modified_by"]  =   date("Y-m-d H:i:s");
                $dat["doctors_modified_by"]  =   $vspld;
                $vblo   =  $produd;
                $this->db->update("doctors",$dat,array("doctors_id" => $produd));
                $vsp    =   $this->db->affected_rows();
                
            }else{
                $dat["doctors_created_on"]  =   date("Y-m-d H:i:s");
                $dat["doctors_created_by"]  =   $vspld;
                
    	        $this->db->insert("doctors",$dat);
    	        $vsp    =   $this->db->insert_id();
    	          
	            $vblo   =   $vsp."PRD";
                $dat    =   array(
                                "doctors_id"           =>  $vblo
                            );		
                $this->db->update("doctors",$dat,array("doctorsid" => $vsp)); 
            }
	        if($vsp > 0){
	            return true;
	        }
	        return false;
        }
        public function deletedoctors(){
            $suri   =   $this->input->post("doctors_id");
            return $this->product_model->delete_doctors($suri);
        }
}