<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Blog_model extends CI_Model{
        public function create_blog(){
                $data = array(
                        'blog_description'              =>  ($this->input->post('blog_description'))??'',
                        'blog_alias_name'               =>  $this->common_config->cleanstr($this->input->post('blog_title')),
                        'blog_title'                    =>  ucfirst($this->input->post('blog_title')),
                        'blog_seo_keywords'             =>  ($this->input->post('keywords'))??'',
                        'blog_seo_description'          =>  ($this->input->post('seo_description'))??'',
                        'blog_module_id'                =>  $this->input->post('module'),
                        "blog_created_on"               =>  date("Y-m-d h:i:s"),
                        "blog_created_by"               =>  $this->session->userdata("login_id")
                );
                $this->db->insert("blogs",$data);
                $vsp   =    $this->db->insert_id();
                if($vsp > 0){
                        $dat    =   array("blog_id"=> $vsp."BLG");	
                        $id     =   $vsp;	
                        $this->db->update("blogs",$dat,array("blogid" => $vsp));
                        $target_dir     =   $this->config->item("upload_dest");
                        $direct         =   $target_dir."/blog";
                        if (file_exists($direct)){
                        }else{mkdir($target_dir."/blog");}
                        $total = count($_FILES['blog_image']['name']);
                        for( $i=0 ; $i < $total ; $i++ ) {
                            $tmpFilePath = $_FILES['blog_image']['tmp_name'][$i];
                            if ($tmpFilePath != ""){    
                                $newFilePath = $direct."/".$_FILES['blog_image']['name'][$i];
                                if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                                    $data = array(
                                        'blog_image_path'                 => $_FILES['blog_image']['name'][$i],
                                        "blog_image_created_on"           => date("Y-m-d h:i:s"),
                                        "blog_image_created_by"           => $this->session->userdata("login_id")
                            
                                    );
                                    $this->db->insert("blog_image",$data);
                                    $vsp   =    $this->db->insert_id();
                                    if($vsp > 0){
                                        $dat=array(
                                                    "blog_image_id" 				=> $vsp."BLGI",
                                                    "blog_image_blog_id" 			=> $id."BLG"
                                            );		
                                        $this->db->update("blog_image",$dat,array("blog_imageid" => $vsp));

                                    }
                                }
                             }
                        } 
                  		$videourl	=	$this->input->post("videourl");
                  		$videotypes	=	$this->input->post("videotypes");
                  		if(is_array($videourl) && count($videourl) > 0){
                            foreach($videourl as $key	=>	$v) {   
                                $data = array(
                                    'blog_video_path'         => $v,
                                    'blog_video_type'         => $videotypes[$key],
                                    "blog_video_created_on"           => date("Y-m-d H:i:s"),
                                    "blog_video_created_by"           => $this->session->userdata("login_id")
                                 );
                                 $this->db->insert("blog_video",$data);
                                 $vsp   =    $this->db->insert_id();
                                 if($vsp > 0){
                                    $dat=array(
                                         "blog_video_id" 				=> $vsp."BLGI",
                                         "blog_video_blog_id" 			=> $id."BLG"
                                    );		
                                    $this->db->update("blog_video",$dat,"blog_videoid ='".$vsp."'");

                                 }
                            }
                        }
                        return true;     
                }
                return false;
        }
        public function update_blog($uri){
            $data = array(
                'blog_description'              =>  ($this->input->post('blog_description'))??'',
                'blog_alias_name'               =>  $this->common_config->cleanstr($this->input->post('blog_title')),
                'blog_title'                    =>  ucfirst($this->input->post('blog_title')),
                'blog_seo_keywords'             =>  ($this->input->post('keywords'))??'',
                'blog_seo_description'          =>  ($this->input->post('seo_description'))??'',
                'blog_module_id'                =>  $this->input->post('module'),
                "blog_modified_on"              =>  date("Y-m-d h:i:s"),
                "blog_modified_by"              =>  $this->session->userdata("login_id")
            );
            $this->db->update("blogs",$data,array("blog_id" => $uri));
            $vsp   =    $this->db->affected_rows();
            if($vsp > 0){
                return true;
            }
            return FALSE;
        }
        public function cntviewBlogs($params  = array()){
                $params["columns"]  =   "count(*) as cnt";
                $vsp     =  $this->queryBlogs($params)->row_array();
                if($vsp != '' && count($vsp) > 0){
                        return $vsp['cnt'];
                }
                return 0;
        }
        public function viewBlogs($params = array()){
            return $this->queryBlogs($params)->result_array();
        }
        public function getBlogs($params = array()){
            return $this->queryBlogs($params)->row_array();
        }
        public function delete_blog($uro,$vpsl = ''){
                $dta    =   array(
                    "blog_open"            =>  0, 
                    "blog_modified_on" =>    date("Y-m-d h:i:s"),
                    "blog_modified_by" =>    ($this->session->userdata("login_id") != "")?$this->session->userdata("login_id"):$vpsl 
                );
                $this->db->update("blogs",$dta,array("blog_id" => $uro));
                $vsp   =    $this->db->affected_rows();
                if($vsp > 0){
                    return true;
                }
                return FALSE;
            }
        public function activedeactive($uri,$status){
                $ft     =   array(  
                            "blog_acde"       =>    $status,
                            "blog_modified_on" =>    date("Y-m-d h:i:s"),
                            "blog_modified_by" =>    $this->session->userdata("login_id") 
                       );  
                $this->db->update("blogs",$ft,array("blog_id" => $uri));
                if($this->db->affected_rows() > 0){
                    return TRUE;
                }
                return FALSE;
        }
        public function queryBlogs($params = array()){
                 $dt     =   array(
                                "blog_open"         =>  "1",
                                "blog_status"       =>  "1"
                            );
                $sel    =   "*";
                if(array_key_exists("cnt",$params)){
                        $sel    =   "count(*) as cnt";
                }
                if(array_key_exists("columns",$params)){
                        $sel    =   $params["columns"];
                }
                $this->db->select("$sel")
                            ->from('blogs as b')
                            ->join('sub_module as c',"c.sub_module_id  = b.blog_module_id and sub_module_open = 1 and sub_module_status = 1","left")
                            ->join('modules as m','m.moduleid = c.sub_module_module_id and m.module_open = 1 and module_status = 1','left')
                            ->where($dt); 
                if(array_key_exists("keywords",$params)){
                  $this->db->where("(blog_title LIKE '%".$params["keywords"]."%' or sub_module_name like '%".$params["keywords"]."%' or blog_description like '%".$params["keywords"]."%' OR module_name LIKE '%".$params["keywords"]."%' OR blog_acde = '".$params["keywords"]."'  )");
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
//                $this->db->get();echo $this->db->last_query();exit;
                return $this->db->get();
        }
}