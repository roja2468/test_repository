<?php  
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_config {  
    public function configemail($toemail,$subject,$messge,$userid,$ustype){
            $ci     =   &get_instance();  
            $ci->load->library("email"); 
            $fromemail  =   sitedata("site_email");
            $config     =   array(
                            'protocol'    =>  'smtp', 
                            'smtp_user'   =>  $fromemail,
                            'smtp_host'   =>  sitedata("site_host"),
                            'smtp_pass'   =>  sitedata("site_emailpassword"),
                            'smtp_port'   =>  sitedata("site_port"), 
                            'wordwrap'  =>    TRUE,
                            'mailtype'  =>    'html'
                        ); 
            $datamsd    =   array(
                "email_to"    		=>  $toemail,
                "email_subject"   	=>  $subject,
                "email_message"   	=>  $messge,
                "email_userid"          =>  $userid,
                "email_user"            =>  $ustype,
                "email_created_on"	=>  date("Y-m-d H:i:s")
            );
            $smslog     =   $ci->db->insert("emaillog",$datamsd);
            $ssm        =   $ci->db->insert_id();
            $ci->email->initialize($config); 
            $ci->email->to($toemail);
            $ci->email->from($fromemail, "Administrator");
            $ci->email->subject($subject);
            $ci->email->message($messge);
            $mail   =   $ci->email->send(); 
            if($mail){
              	$ci->db->update("emaillog",array("email_sent" => "1"),array("emailid" => $ssm));
                return true;
            }
            return false;
    }
    public function getString($n){ 
            $characters 	= 	'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
            $randomString 	= 	''; 
            for ($i = 0; $i < $n; $i++) { 
                    $index = rand(0, strlen($characters) - 1); 
                    $randomString .= $characters[$index]; 
            } 
            return $randomString;  		
    }
    public function cleanstr($string){
            $string =   str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
            $res    =   preg_replace('/[^A-Za-z0-9\-]/', '', $string);
            return $res;
    }
    public function time_zonelist() {
        $zones_array    = array();
        $timestamp      = time();
        foreach(timezone_identifiers_list() as $key => $zone) {
              date_default_timezone_set($zone);
              $zones_array[$key]['zone'] = $zone;
              $zones_array[$key]['diff_from_GMT'] =  date('P', $timestamp);
        }
        return $zones_array;
    }
    public function send_pushnotifications($title,$message,$mobileno = 0,$image = "",$push_type = '',$to = ''){ 
        $ci     =   &get_instance();
        $ci->load->database(); 
        $url        =   'https://fcm.googleapis.com/fcm/send';
        $priority   =   "high";
        $content   = array('title' => $title,'body' => $message);
        if($to == 'all'){
            $to     =   "/topics/global";
	}
	else{
            if($mobileno != '0'){
                $tu     =   $ci->db->get_where("tokens",array("token_open" => '1',"token_mobile" => $mobileno))->row_array();
                $to     =   $tu["token_name"];
            }            
            if(strtoupper($push_type) == 'VENDOR'){
                $fields = array('to' => $to,'notification' => $content);
            }
            else{
                $fields = array('to' => $to,'' => $content);
            }
        }	
        $fields["to"]               =   $to;
        $fields["notification"]     =   $content;
        if(strtoupper($push_type) == 'VENDOR'){
            $header_key =   "AAAAFypDW1g:APA91bFWvhiHB6gg4kWT3HKLQMFcx3kcejUDApaQ3yw2GyExl846oI2mPLFp4TWG9lo5J_ulWTIeuJaYGwRuWEsll775e23WmItk_eywV4cR0dZr2k7vROZEqrhVVQcakdQJ_A1fT6Oc";
        }else{
            $header_key =   "AAAA6FYi5Sg:APA91bFCBkbcKqRCz5Xe_MCZSEnCBwR7h-4IdQFizSdpZvvBnlx8wOl23i8ilOQKqyBQGKgDhe8mK8APHLU5E0KjzhnSbQBFMxOJpiCKzg9om8MId9KBfAA1u06yfnYgEmHX9inTvHVI";
        }
	$headers = array('Authorization: key='.$header_key, 'Content-Type: application/json');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $reult      =   curl_exec($ch);
        $result     =   json_decode($reult,true);
        curl_close($ch);
        $ad         = $result["failure"];
        if($ad > "0"){
            if($to != "all"){
                $ci->db->update("tokens",array("token_open" => '0',"token_update" => date("Y-m-d H:i:s")),array("token_name" => $to));
            }
        }
        return true;
    }
    public function getBalance(){
        $url    =   "http://login4.spearuc.com/MOBILE_APPS_API/get_balance_api.php?user=".sitedata("site_mobilename").
                "&pass=".sitedata("site_mobilepassword");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch); 
        if($result != ""){
            $obj 	= (array)json_decode($result);    
            if(is_array($obj)){
                if($obj['status_id'] == "sucess_1001"){
                    return true;
                }
            } 
        } 
        return false;
    }
    public function sendmessagemobile($phonenumber,$message_string){
        $vsp    =   $this->getBalance();
        if($vsp){
            $url 	=   "http://login4.spearuc.com/MOBILE_APPS_API/sms_api.php?type=smsquicksend&"
                    . "user=".sitedata("site_mobilename")
                    . "&pass=".sitedata("site_mobilepassword")
                    . "&sender=".sitedata("site_mobilefrom")."&to_mobileno=".$phonenumber."&sms_text=".$message_string;
            $ulr    =   ($url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_URL, $ulr);
            $result = curl_exec($ch); 
            curl_close($ch);
            $obj 	= (array)json_decode($result); 
            $ci     =   &get_instance();
            $ci->load->database();   
            $data    =   array( 
                "sms_to"            =>  $phonenumber,
                "sms_content"       =>  urldecode($message_string),
                "sms_sent_time"     =>  date("Y-m-d H:i:s"),
                "sms_response"      =>  $result
            );
            $ci->db->insert('sms_log',$data);
            $vsdp    =   $ci->db->insert_id();   
            if(is_array($obj)){
                /*
                if($obj['Status'] == "Success"){ 
                    $ci->db->update("sms_log",array("sms_sent" => "1"),array("smsid" => $vsdp));  
                    return TRUE; 
                }
                */
                if($obj['status_id'] == "success_1002"){ 
                    $ci->db->update("sms_log",array("sms_sent" => "1"),array("smsid" => $vsdp));  
                    return TRUE; 
                }
            } 
            return false;
        }
        return false;
    }
    public function strreplace($vlur){
        $vlur   = str_replace('"',"", $vlur);
        $vlur   = str_replace("]","", $vlur);
        return str_replace("[","",$vlur);
    }
    public function get_timeago( $ptime ){
        $etime = time() - $ptime;
        if( $etime < 1 ){
            return 'less than '.$etime.' second ago';
        }
        $a = array( 12 * 30 * 24 * 60 * 60  =>  'yr',
                    30 * 24 * 60 * 60       =>  'mon',
                    24 * 60 * 60            =>  'day',
                    60 * 60                 =>  'hr',
                    60                      =>  'min',
                    1                       =>  'sec'
        );
        foreach( $a as $secs => $str ){
            $d = $etime / $secs;
            if( $d >= 1 ){
                $r = round( $d );
                return $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
            }
        }
    }
    public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        $theta  = $lon1 - $lon2;
        $dist   = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist   = acos($dist);
        $dist   = rad2deg($dist);
        $miles  = $dist * 60 * 1.1515;
        $unit   = strtoupper($unit);
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}  