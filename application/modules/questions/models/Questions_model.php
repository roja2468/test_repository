<?php

class Questions_model extends CI_Model{
        public function create_qa(){
                $data = array(
                        'qa_question'                 =>  $this->input->post('qa_question'),
                        'qa_answer'                   =>  $this->input->post('qa_answer'),
                        'qa_module_id'                =>  $this->input->post('module'),
                        "qa_created_on"               =>  date("Y-m-d h:i:s"),
                        "qa_created_by"               =>  $this->session->userdata("login_id")
                );
          		if(is_array($_FILES) && count($_FILES) > 0){
                    $target_dir     =   $this->config->item("upload_dest");
                    $direct         =   $target_dir."/blog";
                    if (file_exists($direct)){
                    }else{mkdir($target_dir."/blog");}
                    $tmpFilePath = $_FILES['image']['tmp_name'];
                    if ($tmpFilePath != ""){    
                        $newFilePath = $direct."/".$_FILES['image']['name'];
                        if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                            $picture       =  $_FILES['image']['name']  ;   
                        }
                      	$data["qa_image_path"]	=	$picture;
                    }
                }
                $this->db->insert("questions",$data);
                $vsp   =    $this->db->insert_id();
                if($vsp > 0){
                    	$dat    =   array("qa_id" => $vsp."QA");	
                        $this->db->update("questions",$dat,"qaid='".$vsp."'");
                        return true;   
                }
        }
        public function cntviewQa($params  = array()){
                $params["columns"]  =   "count(*) as cnt";
                $vsp     =  $this->queryQa($params)->row_array();
                if($vsp != '' && count($vsp) > 0){
                        return $vsp['cnt'];
                }
                return 0;
        }
        public function viewQa($params = array()){
            return $this->queryQa($params)->result_array();
        }
        public function getQa($params = array()){
            return $this->queryQa($params)->row_array();
        }
        public function update_qa($str){
            $data = array(
                'qa_question'           =>  $this->input->post('qa_question'),
                'qa_answer'             =>  $this->input->post('qa_answer'),
                'qa_module_id'             =>  $this->input->post('module'),
                "qa_modified_on"        =>  date("Y-m-d h:i:s"),
                "qa_modified_by"        =>  $this->session->userdata("login_id")
            );
            if(is_array($_FILES) && count($_FILES) > 0){
                $target_dir     =   $this->config->item("upload_dest");
                $direct         =   $target_dir."/blog";
                if (file_exists($direct)){
                }else{mkdir($target_dir."/blog");}
                $tmpFilePath = $_FILES['image']['tmp_name'];
                if ($tmpFilePath != ""){    
                    $newFilePath = $direct."/".$_FILES['image']['name'];
                    if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                      $picture       =  $_FILES['image']['name']  ;   
                    }
                    $data["qa_image_path"]	=	$picture;
                }
            }
            $this->db->update("questions",$data,array("qa_id" => $str));
            $vsp   =    $this->db->affected_rows();
            if($vsp > 0){
                return true;
            }
            return FALSE;
    	}
        public function delete_qa($uro){
                $dta    =   array(
                    "qa_open"            =>  0, 
                    "qa_modified_on" =>    date("Y-m-d h:i:s"),
                    "qa_modified_by" =>    $this->session->userdata("login_id") 
                );
                $this->db->update("questions",$dta,array("qa_id" => $uro));
                $vsp   =    $this->db->affected_rows();
                if($vsp > 0){
                    return true;
                }
                return FALSE;
            }
        public function activedeactive($uri,$status){
                $ft     =   array(  
                            "qa_acde"       =>    $status,
                            "qa_modified_on" =>    date("Y-m-d h:i:s"),
                            "qa_modified_by" =>    $this->session->userdata("login_id") 
                       );  
                $this->db->update("questions",$ft,array("qa_id" => $uri));
                if($this->db->affected_rows() > 0){
                    return TRUE;
                }
                return FALSE;
        }
        public function queryQa($params = array()){
                 $dt     =   array(
                                "qa_open"         =>  "1",
                                "qa_status"       =>  "1"
                            );
                $sel    =   "*";
                if(array_key_exists("cnt",$params)){
                        $sel    =   "count(*) as cnt";
                }
                if(array_key_exists("columns",$params)){
                        $sel    =   $params["columns"];
                }
                $this->db->select("$sel")
                            ->from('questions as q')                  
                            ->join('sub_module as c',"c.sub_module_id  = q.qa_module_id and sub_module_open = 1 and sub_module_status = 1","innner")
                            ->join('modules as m','m.moduleid = c.sub_module_module_id and m.module_open = 1 and module_status = 1','inner')
                            ->where($dt); 
                if(array_key_exists("keywords",$params)){
                  $this->db->where("(qa_question LIKE '%".$params["keywords"]."%' or sub_module_name like '%".$params["keywords"]."%' or qa_acde like '%".$params["keywords"]."%' OR module_name LIKE '%".$params["keywords"]."%' OR qa_acde = '".$params["keywords"]."'  )");
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
}