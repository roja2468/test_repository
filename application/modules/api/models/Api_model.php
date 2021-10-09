<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Api_model extends CI_Model{
        public function checkAuthorizationvalid(){
            $default_status =   "0";
            $auth           =   sitedata('authorization');
            $getallheaders  =   getallheaders();	
            $authorization_key = '';
            if(isset($getallheaders) && is_array($getallheaders) && count($getallheaders) >0){
                if(isset($getallheaders['Authorization']) && $getallheaders['Authorization'] !='') { $authorization_key = $getallheaders['Authorization']; }
                if(isset($authorization_key) && $authorization_key !=''){
                    $authorization_key = str_replace("key=","",$authorization_key);
                    $authorization_key = str_replace('"',"",$authorization_key);
                    $authorization_key = str_replace("'","",$authorization_key);
                    $authorization_key = trim($authorization_key);
                    if($authorization_key == trim($auth) ) { $default_status = 1; }
                }
            }
            return $default_status;
        }
        public function jsonencode($status,$status_message,$check = '1'){ 
            $json   =   array(
                "status"            =>  $status,
                "status_messsage"   =>  $status_message,
            );
            if($check == '0'){
                return $json;
            }
            return json_encode($json);
        }
        public function checkUnique(){
                $email     =   $this->input->post('email');
                $mobile    =   $this->input->post('mobile_no');
                $pms["whereCondition"]  =   "(register_email = '".$email."' or register_mobile = '".$mobile."')";
                $sleper     =   $this->registration_model->getRegistration($pms);
                if(is_array($sleper) && count($sleper) > 0){
                    return true;
                }
                return false;
        }
        public function checkregacstatus(){
                $mobile    =   $this->input->post('mobile_no');
                $email     =   $this->input->post('email');
                $pms["whereCondition"]  =   "(register_email = '".$email."' or register_mobile = '".$mobile."')";
                $sleper     =   $this->registration_model->getRegistration($pms);
                if(is_array($sleper) && count($sleper) > 0){
                    $clps   =   $sleper['register_acde'];
                    if($clps == "Deactive"){
                        return 1;
                    }else{
                        if($sleper['register_otp'] == 0){
                            return 2;   
                        }
                    }
                    return 3;
                }
                return 0;
        }
        public function signup(){
            $data = array(
                'register_email'           => $this->input->post('email'),
                'register_mobile'          => $this->input->post('mobile_no'),
                'register_password'        => base64_encode($this->input->post('password')),
                'register_created_on'      => date("Y-m-d H:i:s")
            );
            $this->db->insert("registration",$data);
            $vsp   =    $this->db->insert_id();
            if($vsp > 0){
                $uniq   =   "PAT". str_pad($vsp, 6, "0", STR_PAD_LEFT);  
                $dat    =   array(
                                "registration_id"       =>  $vsp."USR",
                                "register_unique"       =>  $uniq,
                                'register_created_by'   =>  $vsp."USR"
                            );		
                $this->db->update("registration",$dat,array("registrationid" => $vsp));	
                $vpsl   =   $this->api_model->sendotp();
                return true;
            }
            return false;
            
        }
        public function sendotp($otp_type = 0){
            $mobile     =   $this->input->post("mobile_no");
            $otp_key    =   1234;//rand(0000,99999);
            $str        =   "Dear Customer, The OTP to authenticate your identity is $otp_key. We thank you for your interest in SuJeevan. Please key in to confirm purchase - SuJeevan Health and Wellness Services.";
            $messge     =   urlencode($str);
            $vsp        =   1;//$this->common_config->sendmessagemobile($mobile,$messge);
            if($vsp){
                $dta    =   array(
                    "otp_type"          =>  $otp_type,
                    "otp_key"           =>  $otp_key,
                    "otp_mobile_no"     =>  $mobile,
                    "otp_sent_time"     =>  date("Y-m-d H:i:s")
                );
                $this->db->insert("otp_log",$dta);
                return TRUE;
            }
            return FALSE;
        } 
        public function verifyotp(){
            $this->db->select('*')
                    ->from('otp_log')
                    ->where('otp_key',$this->input->post('otp_no'))
                    ->where('otp_mobile_no',$this->input->post("mobile_no"))
                    ->where("TIMEDIFF(TIME(otp_sent_time), '".date("H:i:s")."') <= '10'")
                    ->where('otp_status','0'); 
            $response 	= 	$this->db->get();  
//            echo $this->db->last_query();exit;
            $result 	= 	$response->row_array();  
            if(is_array($result) && count($result)>0){
                $this->db->where('otpid', $result['otpid']);
                $this->db->update('otp_log',array('otp_status'=>'1'));
                if($result["otp_type"] == 0){
                    $data = array(
                        'register_mobile'     	=> $this->input->post("mobile_no")
                    );
                    $dta    =   array(
                        'register_otp'          => 1,
                        "register_modified_on"  => date("Y-m-d H:i:s")
                    );
                    $this->db->update("registration",$dta,$data); 
                }else{
                    $data = array(
                        'regvendor_mobile'     	=> $this->input->post("mobile_no")
                    );
                    $dta    =   array(
                        'regvendor_otp'          => 1,
                        "regvendor_modified_on"  => date("Y-m-d H:i:s")
                    );
                    $this->db->update("register_vendors",$dta,$data); 
                }
                return TRUE;   
            }
            return FALSE;
        }
        public function add_basic_details(){
            $data = array(
                'register_email'     	=> $this->input->post('email'),
            );
            $dta    =   array(
                'register_name'         => $this->input->post('full_name') ,
                'register_age'          => $this->input->post('age') ,
                'register_gender'       => $this->input->post('gender'),
                "register_modified_on"  => date("Y-m-d H:i:s")
            );
            $this->db->update("registration",$dta,$data);
            $vsp   =    $this->db->affected_rows();
            if($vsp > 0){
                return true;
            }
            return FALSE;
        }
        public function login(){  
            $pd     =   base64_encode($this->input->post('password'));
            $condition['whereCondition']    =   "register_email  = '".$this->input->post('email')."'";
            $res =  $this->registration_model->getRegistration($condition);
            if(!empty($res) && count($res) > 0){
                if($res["register_password"] != $pd){
                    return 3;
                }
                else if($res["register_acde"] == "Active"){
                    $dta    =   array(
                        'register_login_status' => "1",
                        'register_login_time'   =>  date("Y-m-d H:i:s")
                    );
                    $this->db->update("registration",$dta,array("registration_id" => $res["registration_id"]));
                    $vsp   =    $this->db->affected_rows();
                    if($vsp > 0){
                        return 1;
                    }
                }
                return  2;
            }
            return 0;
        }
        public function logout(){
            $data   =   array(
                'register_email'     	=> $this->input->post('email'),
            );
            $dta    =   array(
                'register_login_status' =>  0,
                "register_modified_on"  =>  date("Y-m-d H:i:s")
            );
            $this->db->update("registration",$dta,$data);
            $vsp   =    $this->db->affected_rows();
            if($vsp > 0){
                return true;
            }
            return FALSE;
        }
        public function splash(){     
            $condition['whereCondition']    =   "register_email  LIKE '".$this->input->post('email')."'";
            $res =  $this->registration_model->getRegistration($condition);
            if(is_array($res) && count($res) > 0){
                return $res;
            }
            return array();
        }
        public function getProfile(){     
            $condition['columns']           =   "registration_id,register_unique,register_email,register_mobile,register_name,register_age,register_gender,register_otp";
            $condition['whereCondition']    =   "register_mobile  LIKE '".$this->input->post('mobile_no')."'";
            if($this->input->post('email') != ""){
                $condition['whereCondition']    =   "register_email  = '".$this->input->post('email')."'";
            }
            $res =  $this->registration_model->getRegistration($condition);
            if(is_array($res) && count($res) > 0){
                return $res;
            }
            return array();
        }
        public function submodules($mdol){  
            $target_dir             =   base_url().$this->config->item("upload_dest")."modules/";
            $conditions["columns"]          =   "sub_module_id,sub_module_name,concat('".$target_dir."',sub_module_image) as  sub_module_image,moduleid,(case when submodule_isblog = 1 then 'api-blogs' else (case when submodule_isquestions = 1 then 'api-questions' else (case when submodule_isconsult = 1 then 'api-consultation' else submodule_api end) end) end) as submodule_api";
            $conditions['whereCondition']   =   "submodule_ismodule = '".$mdol."' and sub_module_acde = 'Active' and sub_module_module_id  = '".$this->input->post('module_id')."'";
            $res =  $this->sub_module_model->viewSub_module($conditions);
            if(is_array($res) && count($res) > 0){
                return $res;
            }
            return array();
        }
        public function blogs(){  
            $target_dir                     =   base_url().$this->config->item("upload_dest")."modules/";
            $conditions["columns"]          =    "blog_id,blog_title,blog_alias_name,module_name,blog_description,blogid as blog_images,blog_created_on,blog_created_by";
            $conditions['whereCondition']   =   "moduleid  = '".$this->input->post('module_id')."' and blog_acde = 'Active'";
            $res =  $this->blog_model->viewBlogs($conditions);
            if(is_array($res) && count($res) > 0){
              	foreach($res as $ld  => $ve){
              	    $vol            =   $ve["blog_created_by"];
              	    $vplcr          =   $this->api2_model->getProfile($vol);
              	    $vtimerc        =   (is_array($vplcr) && count($vplcr) > 0)?$vplcr["regvendor_name"]:"";
              	    $timer          =   $ve["blog_created_on"];
              	    $vtimer         =   $this->common_config->get_timeago(strtotime($timer));
              	    
                  	$blog_image	=	base_url().$this->config->item("upload_dest")."blog/";
                  	//$lsp	=	$this->db->select("concat('".$blog_image."',blog_image_path) as  blog_image_path")->get_where("blog_image",array("blog_image_blog_id" => $ve['blog_id']))->result_array();
                  	$lsp	=	$this->db->select("concat('".$blog_image."',blog_image_path) as  blog_image_path")->get_where("blog_image",array("blog_image_blog_id" => $ve['blog_id']))->row_array();
                //  echo "<pre>";print_R($lsp);exit;
                //   	$blog_viedo	=	base_url().$this->config->item("upload_dest")."blog/";
                //   	$lssp	=	$this->db->select("blog_video_type,(case when blog_video_type = 'Youtube' then blog_video_path else concat('".$blog_image."',blog_video_path) end) as  blog_video_path")->get_where("blog_video",array("blog_video_blog_id" => $ve['blog_id']))->result_array();
                //   	$res[$ld]["blog_videos"]	=	$lssp;
                  	$res[$ld]["blog_images"]	    =	is_array($lsp)?$lsp["blog_image_path"]:"";
                  	$res[$ld]["blog_created_on"]	=	$vtimer;
                  	$res[$ld]["blog_created_by"]	=	ucwords($vtimerc);
                }
                return $res;
            }
            return array();
        }
        public function blogsid(){  
            $target_dir                     =   base_url().$this->config->item("upload_dest")."modules/";
            $conditions["columns"]          =   "blog_id,blog_title,blog_alias_name,module_name,blog_description,blogid as blog_images,blog_created_on,blog_created_by";
            $conditions['whereCondition']   =   "blog_id  = '".$this->input->post('blog_id')."' and blog_acde = 'Active'";
            $res =  $this->blog_model->getBlogs($conditions);
            if(is_array($res) && count($res) > 0){
                    $ve    =   $res;
            //   	foreach($res as $ld  => $ve){
              	    $vol            =   $ve["blog_created_by"];
              	    $vplcr          =   $this->api2_model->getProfile($vol);
              	    $vtimerc        =   (is_array($vplcr) && count($vplcr) > 0)?$vplcr["regvendor_name"]:"";
              	    $timer          =   $ve["blog_created_on"];
              	    $vtimer         =   $this->common_config->get_timeago(strtotime($timer));
              	    /** Images **/
                  	$blog_image	=	base_url().$this->config->item("upload_dest")."blog/";
                  	$lsp	=	$this->db->select("concat('".$blog_image."',blog_image_path) as  blog_image_path")->get_where("blog_image",array("blog_image_blog_id" => $ve['blog_id']))->result_array();
                  	$vlsp	=	$this->db->select("concat('".$blog_image."',blog_image_path) as  blog_image_path")->get_where("blog_image",array("blog_image_blog_id" => $ve['blog_id']))->row_array();
                    /** Videos **/
                  	$blog_viedo	    =	base_url().$this->config->item("upload_dest")."blog/";
                  	$lssp	        =	$this->db->select("blog_video_type,(case when blog_video_type = 'Youtube' then blog_video_path else concat('".$blog_image."',blog_video_path) end) as  blog_video_path")->get_where("blog_video",array("blog_video_blog_id" => $ve['blog_id']))->result_array();
                  	$res["blog_videos"]	        =	$lssp;
                  	$res["blog_images"]	        =	is_array($vlsp)?$vlsp["blog_image_path"]:"";
                  	$res["blog_images_all"]	    =	$lsp;
                  	$res["blog_created_on"]	    =	$vtimer;
                  	$res["blog_created_by"]	    =	ucwords($vtimerc);
                // }
                return $res;
            }
            return array();
        }
        public function questions(){  
            $blog_image						=	base_url().$this->config->item("upload_dest")."blog/";
            $conditions["columns"]  		=   "qa_id,qa_question,qa_answer,concat('".$blog_image."',qa_image_path) as qa_image_path";
            $conditions['whereCondition']   =   "moduleid  = '".$this->input->post('module_id')."'";
            $res =  $this->questions_model->viewQa($conditions);
            if(is_array($res) && count($res) > 0){
                return $res;
            }
            return array();
        }
        public function hometest(){
            $target_dir             =   base_url().$this->config->item("upload_dest")."homecare/";
            $conditions["columns"]  =    "homecaretest_id,homecaretest_name,concat('".$target_dir."',homecaretest_image) as  homecaretest_image,homecaretest_actual_price,homecaretest_offer_price";
            $conditions['whereCondition']    =   "moduleid  = '".$this->input->post('module_id')."'";
            $res =  $this->hometest_model->viewTest($conditions);
          	if(is_array($res) && count($res) > 0){
                return $res;
            }
            return array();
        }
        public function blog_view_check()
        {
            $email = $this->input->post('email');
            $pms["whereCondition"]  =   "(register_email = '".$email."')";
            $get_user =   $this->registration_model->getRegistration($pms);
            return $get_user;
        }
        public function homepackages(){
            $target_dir             =   base_url().$this->config->item("upload_dest")."homecare/";
            $conditions["columns"]  =    "package_id,package_name,concat('".$target_dir."',package_image) as package_image";
            $conditions['whereCondition']    =   "package_module_id  = '".$this->input->post('module_id')."'";
            $res =  $this->homecare_model->viewpackage($conditions);
          	if(is_array($res) && count($res) > 0){
                return $res;
            }
            return array();
        }
        public function dashboard(){
            $target_dir             =   base_url().$this->config->item("upload_dest")."modules/";
            $conons["whereCondition"]       =   "module_program = 1";
            $conons["columns"]              =   "moduleid,module_name,concat('".$target_dir."',module_image) as  module_image";
            $conditions["whereCondition"]   =   "module_program = 0";
            $conditions["columns"]  =   "moduleid,module_name,concat('".$target_dir."',module_image) as  module_image";
            $dad    =   array(
                "profile"           =>  $this->api_model->getProfile(),
                "tophealth"         =>  $this->common_model->viewModules($conons),
                "book_appointment"  =>  $this->common_model->viewModules($conditions),
                "wow"               =>  $this->api_model->wellness(),
                "health_score"      =>  $this->api_model->allscores(),
                "track_activity"    =>  array(
                                            array("track_name"    =>  "Steps","track_image"   =>  $target_dir."image_not_available.png"),
                                            array("track_name"    =>  "Sleep","track_image"   =>  $target_dir."sleep.png"),
                                            array("track_name"    =>  "Weight","track_image"   =>  $target_dir),
                                            array("track_name"    =>  "Calories","track_image"   =>  $target_dir),
                                        ),
                "other_services"    =>  array(
                                            array("service_id" => "13","service_name"    =>  "Insurance","service_image"   =>  $target_dir."image_not_available.png"),
                                            array("service_id" => "13","service_name"    =>  "Sleep","service_image"   =>  $target_dir."sleep.png"),
                                            array("service_id" => "13","service_name"    =>  "Weight","service_image"   =>  $target_dir),
                                            array("service_id" => "13","service_name"    =>  "Calories","service_image"   =>  $target_dir),
                                        ),
                "packages"          =>  array(
                                            array("package_id" => "13","package_name"    =>  "Steps","package_image"   =>  $target_dir."image_not_available.png"),
                                            array("package_id" => "13","package_name"    =>  "Sleep","package_image"   =>  $target_dir."sleep.png"),
                                            array("package_id" => "13","package_name"    =>  "Weight","package_image"   =>  $target_dir),
                                            array("package_id" => "13","package_name"    =>  "Calories","package_image"   =>  $target_dir),
                                        )
            );
            return $dad;
        }
        public function allscores(){
            $target_dir             =   base_url().$this->config->item("upload_dest")."scores/";
            $this->db->select("score_full_name,score_name as score_api_name,concat('".$target_dir."',score_full_icon) as  score_full_icon");
            return $this->db->get_where("score_formulas",array("score_open" => 1))->result_array();
        }
        public function vendors(){
            $target_dir             =   base_url().$this->config->item("upload_dest")."modules/";
            $conditions["columns"]  =   "vendor_id,vendor_name,concat('".$target_dir."',vendor_icon) as  vendor_icon";
            return $this->vendor_model->viewVendors($conditions);
        }
        public function wellness(){
            $target_dir             =   base_url().$this->config->item("upload_dest")."wellness/";
            $conditions["columns"]  =   "wellness_id,wellness_name,wellness_description,concat('".$target_dir."',wellness_image) as  wellness_image";
            return $this->wellness_model->viewWellness($conditions);
        }
        public function getPackages(){
            $target_dir             =   base_url().$this->config->item("upload_dest")."homecare/";
            $conditions["columns"]  =   "package_id,package_name,concat('".$target_dir."',package_image) as package_image,quotation_by,quotation,concat('".$target_dir."',quotation_image) as how_itworks";
            $conditions['whereCondition']    =   "package_id  = '".$this->input->post('package_id')."'";
            $res =  $this->homecare_model->getpackage($conditions);
            if(is_array($res) && count($res) > 0){
                return $res;
            }
            return array();
        }
        public function subitems(){
            $conditions["columns"]          =   "item_package_item,item_id,'' as subitems";
            $conditions['whereCondition']   =   "package_id  = '".$this->input->post('package_id')."'";
            $res        =   $this->works_model->viewworks($conditions);
            $vslp       =   array();
            if(is_array($res) && count($res) > 0){
                foreach($res as $kl =>  $ver){
                    $item_id                        =   $ver['item_id'];
                    $res[$kl]["item_id"]            =   $item_id;
                    $res[$kl]["item_package_item"]  =   $ver["item_package_item"];
                    $dmpp["columns"]                =   "subitem_name,subitem_quantity";
                    $dmpp["whereCondition"]         =   "subitem_item_id = '".$item_id."'";
                    $res[$kl]["subitems"]           =   $this->works_model->viewsubworks($dmpp);
                }
                return $res;
            }
            return array();
        }
        public function createqueries(){
                $data = array(
                        'qa_question'                 =>  $this->input->post('qa_question'),
                        'qa_module_id'                =>  $this->input->post('module'),
                        "qa_created_on"               =>  date("Y-m-d h:i:s"),
                        "qa_created_by"               =>  $this->session->userdata("login_id")
                );
                $this->db->insert("questions",$data);
                $vsp   =    $this->db->insert_id();
                if($vsp > 0){
                    	$dat    =   array("qa_id" => $vsp."QA");	
                        $this->db->update("questions",$dat,"qaid='".$vsp."'");
                        return true;   
                }
                return false;
        }
        public function consultation(){
            $condition['columns']           =   "r.*,vendor_name,specialization_name,state_name,district_name,sub_module_name";
            $condition["whereCondition"]    =   "sub_module_id = '".$this->input->post("sub_module_id")."'";
            $vpo    =   $this->vendor_registration_model->viewRegistration($condition);
            if(is_array($vpo) && count($vpo) > 0){
                return $vpo;
            }
            return array();
        }
        public function consultationview(){
            $condition['columns']           =   "r.*,vendor_name,specialization_name,state_name,district_name,sub_module_name,'' as educations";
            $condition["whereCondition"]    =   "regvendor_id = '".$this->input->post("regvendor_id")."'";
            $vpo    =   $this->vendor_registration_model->getRegistration($condition);
            if(is_array($vpo) && count($vpo) > 0){
                $vpo['educations']  =   $this->api2_model->qualifications();
                return $vpo;
            }
            return array();
        }
        public function consultationpackages(){
            $condition['columns']           =   "r.*,vendor_name,specialization_name,state_name,district_name,sub_module_name,'' as educations";
            $condition["whereCondition"]    =   "regvendor_id = '".$this->input->post("regvendor_id")."'";
            $vpo    =   $this->vendor_registration_model->getRegistration($condition);
            if(is_array($vpo) && count($vpo) > 0){
                $vpo['educations']  =   $this->api2_model->qualifications();
                return $vpo;
            }
            return array();
        }
        public function scorepoint($scorename){
            $serum      =   $this->input->post("serum");
            $age        =   $this->input->post("age");
            $if_black   =   $this->input->post("if_black");
            $if_female  =   $this->input->post("if_female");
            
            /** Heart Score **/    
            $history    =   $this->input->post("history");
            $agevalue   =   $this->input->post("age");
            $risk       =   $this->input->post("risk");
            $ekg        =   $this->input->post("ekg"); 
            $troponin   =   $this->input->post("troponin"); 
            
            $height    =   $this->input->post("height");
            $weight    =   $this->input->post("weight");
            $fasting_insulin    =   $this->input->post("fasting_insulin");
            $fasting_glucose    =   $this->input->post("fasting_glucose");
            $daa    =   array(
                            "score_name"    =>  $scorename
                        );
                            
            $target_dir             =   base_url().$this->config->item("upload_dest")."scores/";
            $this->db->select("*,concat('".$target_dir."',score_png) as  score_png");
            $vpo    =   $this->db->get_where("score_formulas",$daa)->row_array();
            if(is_array($vpo) && count($vpo) > 0){
                $vlpa   =   $vpo["score_formula"];
                if($if_female == '1'){
                    $vlpa   =   $vlpa."*0.742";
                }
                if($if_black == '1'){
                    $vlpa   =   $vlpa."*1.212";
                }
                $cslp   =   eval("return ".$vlpa.";");
                $cml    =   round($cslp,2);
                $spl    =   "";
                if($history != ""){
                    if($cml >= 0 && $cml <= 3){
                        $spl    =   "Low Score (0-3 points) <br/> Risk of MACE of 0.9-1.7%.";
                    }
                    if($cml >= 4 && $cml <= 6){
                        $spl    =   "Moderate Score (0-3 points) <br/> Risk of MACE of 12-16.6%.";
                    }
                    if($cml >= 7){
                        $spl    =   "High Score (>= 7 points) <br/> Risk of MACE of 50-65%.";
                    }
                }
                return array(
                        "score_result"  =>  $cml,
                        "score_desc"    =>  $vpo['score_desc'],
                        "score_png"         =>  $vpo['score_png'],
                        "score_result_text" =>  $spl
                    );
            }
            return array();
        }
        public function chatroom(){     
            $condition['columns']           =   "chat_message as from_message,chat_to as to_message";
            $condition['whereCondition']    =   "register_mobile  LIKE '".$this->input->post('mobile_no')."' and chat_message is not null";
            if($this->input->post('email') != ""){
                $condition['whereCondition']    =   "register_email  = '".$this->input->post('email')."'  and chat_message is not null";
            }
            $res =  $this->registration_model->viewChatroom($condition);
            if(is_array($res) && count($res) > 0){
                return $res;
            }
            return array();
        }
        public function consultchatroom($registration_id){     
            $condition['columns']           =   "symptomchat_from,symptomchat_message,symptomchat_options,symptomchat_to";
            $condition['whereCondition']    =   "(symptomchat_to like '".$registration_id."' or symptomchat_from like '".$registration_id."')";
            if($this->input->post('email') != ""){
                $condition['whereCondition']    =   "(symptomchat_to like '".$registration_id."' or symptomchat_from like '".$registration_id."')";
            }
            $res =  $this->registration_model->viewconsultChatroom($condition);
            if(is_array($res) && count($res) > 0){
                return $res;
            }
            return array();
        }
        public function viewHalthcategory(){
            $condition['columns']   =   'healthcategory_id,healthcategory_name';
            $res =  $this->health_category_model->viewCategory($condition);
            return $res;
        }
        public function consultdoctors(){     
            $vslp   =   $this->api_model->getProfile();
            $registration_id        =   $vslp['registration_id'];
            $vslp   =   $this->api_model->consultchatroom($registration_id);
            if(is_array($vslp) && count($vslp) == 0){
                $dns['tipoOrderby']     =   "symptoms_order";
                $dns['order_by']        =   "ASC";
                $dns['whereCondition']  =   "symptoms_auto_start = 1";
                $ll     =   $this->symptoms_model->viewSymptomsbot($dns);
                if(is_array($ll) && count($ll) > 0){
                    foreach($ll as $le){
                        $data[] = array(
                            'symptomchat_from'         => "bot",
                            'symptomchat_message'      => $le['symptoms_question'],
                            'symptomchat_options'      => $le['symptoms_options'],
                            'symptomchat_to'           => $registration_id,
                            'symptomchat_created_on'   => date("Y-m-d H:i:s")
                        );
                    }
                    $this->db->insert_batch("symptom_chat_room",$data);
                }
            }
            $thims  =   $this->input->post("message");
            if($thims != ""){
                $da= array(
                    'symptomchat_from'         => $registration_id,
                    'symptomchat_message'      => $thims,
                    'symptomchat_options'      => "",
                    'symptomchat_to'           => "bot",
                    'symptomchat_created_on'   => date("Y-m-d H:i:s")
                );
                $this->db->insert("symptom_chat_room",$da);
            }
            $symptom_health     =   $this->api_model->viewHalthcategory();
            $cslp   =   $this->api_model->consultchatroom($registration_id);
            if(is_array($cslp) && count($cslp) > 0){
                foreach($cslp as $ki => $vd){
                    $symptomchat_options    =   explode(",",$vd['symptomchat_options']);
                    foreach($symptomchat_options as $vrf){
                        $slcp[]['option_key']     =   $vrf;
                    }
                    $cslp[$ki]['symptomchat_options']   =   $slcp;
                }
            }
            $csxlp['symptom_health']    =   $symptom_health;
            $csxlp['symptom_message']   =   $cslp;
            return $csxlp;
        }
        public function symptoms_checker(){
            $condition['whereCondition']   =   "healthcategory_acde = 'Active'";
            $condition['columns']   =   'healthcategory_name,healthcategory_id,"" as health_subcats';
            $res =  $this->health_category_model->viewCategory($condition);
            if(is_array($res) && count($res) > 0){
                $target_dir             =   base_url().$this->config->item("upload_dest")."modules/";
                foreach($res as $kl =>  $fr){
                    $lcp                            =   $fr["healthcategory_id"];
                    $condition['whereCondition']    =   "healthsubcategory_health_id = '".$lcp."'";
                    $condition['columns']   =   'healthsubcategory_id,healthsubcategory_name,concat("'.$target_dir.'",healthsubcategory_image) as  healthsubcategory_image';
                    $lvpdd =  $this->health_category_model->viewsubCategory($condition);
                    $res[$kl]['health_subcats']    =   $lvpdd;
                }
                return $res;
            }
            return array();
        }
        /*** Chat room ***/
        public function chatroom_create(){
            $vslp   =   $this->api_model->getProfile();
            $msk    =   $this->input->post('message');
            $pms['whereCondition']  =   "(find_in_set('$msk',botauto_tags) > 0) and botauto_acde = 'Active'";
            $vdlp                   =   $this->botconfiguation_model->getBotsreply($pms);
            $registration_id        =   $vslp['registration_id'];
            $data = array(
                'chat_from'         => $registration_id,
                'chat_message'      => $msk,
                'chat_to'           => $vdlp,
                'chat_created_on'   => date("Y-m-d H:i:s")
            );
            $this->db->insert("chat_room",$data);
            $vsp   =    $this->db->insert_id();
            if($vsp > 0){
                return true;
            }
            return false;
        }
        /*** Health Sub Categorry ****/
        public function viewsubCategory(){
            $helathid   =   $this->input->post("healthcategory_id");
            $mpsm['columns']        =   'healthsubcategory_id,healthsubcategory_name';
            $mpsm['whereCondition'] =   'healthsubcategory_health_id = "'.$helathid.'"';
            return $this->health_category_model->viewsubCategory($mpsm);
        }
}