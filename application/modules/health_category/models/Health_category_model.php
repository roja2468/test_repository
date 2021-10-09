<?php

class Health_category_model extends CI_Model{
        public function create_category(){
                $data = array(
                        'healthcategory_name'                    =>  ucfirst($this->input->post('category_name')),
                        'healthcategory_alias_name'              =>  $this->common_config->cleanstr($this->input->post('category_name')),
                        'healthcategory_module_id'               =>  $this->input->post('module'),
                        "healthcategory_created_on"               =>  date("Y-m-d H:i:s"),
                        "healthcategory_created_by"               =>  $this->session->userdata("login_id")
                );
                $this->db->insert("health_issues_category",$data);
                $vsp   =    $this->db->insert_id();
                if($vsp > 0){
                    $dat    =   array("healthcategory_id" => $vsp."VT");	
                    $this->db->update("health_issues_category",$dat,array("healthcategoryid" => $vsp));
                    return true;   
                 }
        }
        public function cntviewCategory($params  = array()){
                $params["columns"]  =   "count(*) as cnt";
                $vsp     =  $this->queryCategory($params)->row_array();
                if($vsp != '' && count($vsp) > 0){
                        return $vsp['cnt'];
                }
                return 0;
        }
        public function viewCategory($params = array()){
            return $this->queryCategory($params)->result_array();
        }
        public function getCategory($params = array()){
            return $this->queryCategory($params)->row_array();
        }
        public function update_category($str){
            $data = array(
                    'healthcategory_name'                    =>  ucfirst($this->input->post('category_name')),
                    'healthcategory_alias_name'              =>  $this->common_config->cleanstr($this->input->post('healthcategory_name')),
                    'healthcategory_module_id'               =>  $this->input->post('module'),
                    "healthcategory_modified_on"             =>  date("Y-m-d H:i:s"),
                    "healthcategory_modified_by"             =>  $this->session->userdata("login_id")
            );
            $this->db->update("health_issues_category",$data,array("healthcategory_id" => $str));
            $vsp   =    $this->db->affected_rows();
            if($vsp > 0){
                return true;
            }
            return FALSE;
        }
        public function delete_category($uro){
                $dta    =   array(
                    "healthcategory_open"        =>  0, 
                    "healthcategory_modified_on" =>    date("Y-m-d H:i:s"),
                    "healthcategory_modified_by" =>    $this->session->userdata("login_id") 
                );
                $this->db->update("health_issues_category",$dta,array("healthcategory_id" => $uro));
                $vsp   =    $this->db->affected_rows();
                if($vsp > 0){
                    return true;
                }
                return FALSE;
        }
        public function activedeactive($uri,$status){
                $ft     =   array(  
                            "healthcategory_acde"        =>    $status,
                            "healthcategory_modified_on" =>    date("Y-m-d H:i:s"),
                            "healthcategory_modified_by" =>    $this->session->userdata("login_id") 
                       );  
                $this->db->update("health_issues_category",$ft,array("healthcategory_id" => $uri));
                if($this->db->affected_rows() > 0){
                    return TRUE;
                }
                // echo $this->db->last_query();exit;
                return FALSE;
        }
        public function queryCategory($params = array()){
            $dt     =   array(
                        "healthcategory_open"         =>  "1",
                        "healthcategory_status"       =>  "1"
                    );
            $sel    =   "*";
            if(array_key_exists("cnt",$params)){
                $sel    =   "count(*) as cnt";
            }
            if(array_key_exists("columns",$params)){
                $sel    =   $params["columns"];
            }
            $this->db->select("$sel")
                    ->from('health_issues_category as c')
                    ->join('modules as m','m.moduleid = c.healthcategory_module_id','LEFT')
                    ->where($dt); 
            if(array_key_exists("keywords",$params)){
            $this->db->where("(healthcategory_name LIKE '%".$params["keywords"]."%' OR module_name LIKE '%".$params["keywords"]."%' OR healthcategory_acde = '".$params["keywords"]."'  )");
            }
            if(array_key_exists("whereCondition",$params)){
                $this->db->where("(".$params["whereCondition"].")");
            }
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            if(array_key_exists("tipoOrderby",$params) && array_key_exists("order_by",$params)){
                $this->db->order_by($params['tipoOrderby'],$params['order_by']);
            } 
            //  $this->db->get();echo $this->db->last_query();exit;
            return $this->db->get();
        }
        public function unique_id_check_category(){
            $str    =       $this->input->post('healthcategory_name');
            $str1   =       $this->input->post('module'); 
            $ms     =   "";
            $healthcategory_id    =   $this->input->post("healthcategory_id");
            if($healthcategory_id != ""){
                $ms     =   " and healthcategory_id not like '".$healthcategory_id."'";
            }
            $pms["whereCondition"]  =   "healthcategory_name = '".$str."' AND healthcategory_module_id = '".$str1."' $ms";
            $vsp    =   $this->getCategory($pms);
            if(is_array($vsp) && count($vsp) > 0){
                return true;
            }
            return false;
        }
        public function querysubCategory($params = array()){
            $dt     =   array(
                            "healthcategory_open"         =>  "1",
                            "healthcategory_status"       =>  "1"
                        );
            $sel    =   "*";
            if(array_key_exists("cnt",$params)){
                    $sel    =   "count(*) as cnt";
            }
            if(array_key_exists("columns",$params)){
                    $sel    =   $params["columns"];
            }
            $this->db->select("$sel")
                        ->from('subcategory_issues as r')
                        ->join('health_issues_category as c','c.healthcategory_id = r.healthsubcategory_health_id','LEFT')
                        ->join('modules as m','m.moduleid = c.healthcategory_module_id','LEFT')
                        ->where($dt); 
            if(array_key_exists("keywords",$params)){
              $this->db->where("(healthcategory_name LIKE '%".$params["keywords"]."%' OR module_name LIKE '%".$params["keywords"]."%' OR healthcategory_acde = '".$params["keywords"]."'  )");
            }
            if(array_key_exists("whereCondition",$params)){
                    $this->db->where("(".$params["whereCondition"].")");
            }
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                    $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                    $this->db->limit($params['limit']);
            }
            if(array_key_exists("tipoOrderby",$params) && array_key_exists("order_by",$params)){
                    $this->db->order_by($params['tipoOrderby'],$params['order_by']);
            } 
          //  $this->db->get();echo $this->db->last_query();exit;
            return $this->db->get();
        }
        public function cntviewsubCategory($params  = array()){
            $params["columns"]  =   "count(*) as cnt";
            $vsp     =  $this->querysubCategory($params)->row_array();
            if($vsp != '' && count($vsp) > 0){
                    return $vsp['cnt'];
            }
            return 0;
        }
        public function viewsubCategory($params = array()){
            return $this->querysubCategory($params)->result_array();
        }
        public function getsubCategory($pms = array()){
            return $this->querysubCategory($pms)->row_array();   
        }
        public function unique_id_check_subcategory(){
            $str    =       $this->input->post('healthcategory_name');
            $str1   =       $this->input->post('module'); 
            $ms     =   "";
            $healthcategory_id    =   $this->input->post("healthcategory_id");
            if($healthcategory_id != ""){
                $ms     =   " and healthcategory_id not like '".$healthcategory_id."'";
            }
            $pms["whereCondition"]  =   "healthcategory_name = '".$str."' AND healthcategory_module_id = '".$str1."' $ms";
            $vsp    =   $this->getsubCategory($pms);
            if(is_array($vsp) && count($vsp) > 0){
                return true;
            }
            return false;
        }
        public function update_subcategory($ur){
            $data = array(
                    "healthsubcategory_health_id"       =>  $this->input->post("healthcategory"),
                    'healthsubcategory_name'            =>  ucfirst($this->input->post('subcategory_name')),
                    'healthsubcategory_alias_name'      =>  $this->common_config->cleanstr($this->input->post('subcategory_name')),
                    "healthsubcategory_modified_on"     =>  date("Y-m-d H:i:s"),
                    "healthsubcategory_modified_by"     =>  $this->session->userdata("login_id")
            );
            $target_dir =   $this->config->item("upload_dest")."modules/";
            if(count($_FILES) > 0){
                $fname      =   $_FILES["module_image"]["name"]; 
                if($fname != ''){
                    $uploadfile =   $target_dir . ($fname);
                    if (move_uploaded_file($_FILES['module_image']['tmp_name'], $uploadfile)) {
                        $pic =  $fname;
                        $data['healthsubcategory_image']  =   $fname;
                    }
                }
            }
            $this->db->update("subcategory_issues",$data,array("healthsubcategory_id" => $ur));
            // echo $this->db->last_query();exit;
            // echo "<pre>";print_R($data);exit;
            $vsp   =    $this->db->affected_rows();
            if($vsp > 0){	
                return true;   
            }
            return false;
        }
        public function activedeactivesubcate($uri,$status){
                $ft     =   array(  
                            "healthsubcategory_acde"        =>    $status,
                            "healthsubcategory_modified_on" =>    date("Y-m-d H:i:s"),
                            "healthsubcategory_modified_by" =>    $this->session->userdata("login_id") 
                       );  
                $this->db->update("subcategory_issues",$data,array("healthsubcategory_id" => $ur));
                if($this->db->affected_rows() > 0){
                    return TRUE;
                }
                echo $this->db->last_query();exit;
                return FALSE;
        }
        public function delete_subcategory($ur){
            $data = array(
                    "healthsubcategory_open"      =>  0,
                    "healthsubcategory_modified_on"     =>  date("Y-m-d H:i:s"),
                    "healthsubcategory_modified_by"     =>  $this->session->userdata("login_id")
            );
            $this->db->update("subcategory_issues",$data,array("healthsubcategory_id" => $ur));
            $vsp   =    $this->db->affected_rows();
            if($vsp > 0){	
                return true;   
            }
            return false;
        }
        public function createsubcategory(){
            $data = array(
                    "healthsubcategory_health_id"       =>  $this->input->post("healthcategory"),
                    'healthsubcategory_name'            =>  ucfirst($this->input->post('subcategory_name')),
                    'healthsubcategory_alias_name'      =>  $this->common_config->cleanstr($this->input->post('subcategory_name')),
                    "healthsubcategory_created_on"      =>  date("Y-m-d H:i:s"),
                    "healthsubcategory_created_by"      =>  $this->session->userdata("login_id")
            );
            $target_dir =   $this->config->item("upload_dest")."modules/";
            if(count($_FILES) > 0){
                $fname      =   $_FILES["module_image"]["name"]; 
                if($fname != ''){
                    $uploadfile =   $target_dir . ($fname);
                    if (move_uploaded_file($_FILES['module_image']['tmp_name'], $uploadfile)) {
                        $pic =  $fname;
                        $data['healthsubcategory_image']  =   $fname;
                    }
                }
            }
            $this->db->insert("subcategory_issues",$data);
            $vsp   =    $this->db->insert_id();
            if($vsp > 0){
                $dat    =   array("healthsubcategory_id" => $vsp."SUT");	
                $this->db->update("subcategory_issues",$dat,array("healthsubcategoryid" => $vsp));
                return true;   
            }
            return false;
        }
}