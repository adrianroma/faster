<?php

class action{

 public $URL;
 public $url;
 public $host;
 public $protocol;
 public $uri;
 public $data = array();
 public $parameters = array();
 public $props = array();
 public $state =array();
 public $ip;
 public $port;
 public $clientName;
 public $entity;
 public $module;
 public $notfound;
 public $input;
 public $output;
 public $model;

 public function getUrl (){

 $PORT = $_SERVER['SERVER_PORT'];
 
 
 if( isset($_SERVER['HTTPS'] ) ) {
     
    $this->protocol = 'https'; 
 }else{
    $this->protocol = 'http'; 
 }
 

 $this->port = $PORT;
 if($PORT!='80'){
 $this->URL = $this->protocol."://" . $_SERVER['SERVER_NAME'].":".$PORT . $_SERVER['REQUEST_URI'];
 }else{
 $this->URL = $this->protocol."://" . $_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI'];
 }

 $_URL=  explode('?',$this->URL);

 if(isset($_URL)){
 $this->url = $_URL[0];
 }
 
 
 

 $this->host = $_SERVER['HTTP_HOST'];

 $uri = $_SERVER['REQUEST_URI'];
 $uri= ltrim($uri, '/');
 $_uri= explode('?',$uri);

 if(isset($_uri[0])){
 $this->uri = $_uri[0];
 }

 if(isset($_uri[1])){
     $_input = $_uri[1];
     $match=explode('=',$_input);

 }else{
     $match= array();
 }



 $this->input = $match;
 $this->parameters= $this->data();
 $this->ip=$this->getIP();
 $this->clientName= gethostbyaddr($this->ip);
 }

public function reservado($uri){
$reserv = array("data","factory","eav","entity","store","cache","history","mind","props","state","attribute","block","js","style","lib","url","vendor","nbproject");
if(in_array($uri,$reserv)){
 http_response_code(404);
 include('404.php');
 die();
}

}
 public function readUrl(){}
 public function getIP()
 {
   $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

   return $ipaddress;
 }

public function rewrite($url){
$on = 0;
$urls=$this->read('url.rewrite');
$request = NULL;

if(is_array($urls)){
foreach($urls as $key=>$value){
    
  
   if($value["id"]===$url || $value["canonical"]["mx"]===$url){
   $this->module = $value;
   $on = 1;
   }
   
   if($value["id"]!=""){
       
       if($value['rgx']!=='*'){
         
       if (preg_match($value['rgx'], $url))
         {
            $this->module = $value;
           
        }
       }
   }
   
   if($value["id"]==="404"){
   $this->notfound = $value;
   
   }
   
   if($value["id"]==="request"){
       
       $request = $value;
   }else{
       $request = $this->notfound;
   }

  }
}

if($this->module!=NULL){
  
return $this->module;
}else{
return $this->module = $this->notfound;
}

}

public function read($entity){

    $entity=str_replace('.','/',$entity);
    $str=file_get_contents("./".$entity.".json");
    $this->system=json_decode($str,true);
    return $this->system;

}

public function write($entity,$object){

    $entity=str_replace('.','/',$entity);
    $pathFile="./".$entity.".json";
    $file = fopen($pathFile,"w");
    $string = json_encode($object,JSON_PRETTY_PRINT);
    fwrite($file,$string);
    fclose($file);
}


 public function redirect($url,$permanent = false){

    if (headers_sent() === false)
    {
    	header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }
    exit();

 }

 public function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
 }

 public function cookie($uri){

            if($uri=='admin'){
                if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $cookie_name = "key";
                $key = $this->generateRandomString(12);
                $time =NULL;
                if(!isset($_COOKIE["key"])){
                setcookie($cookie_name,$key,$time, "/"); // 86400 = 1 day
                }
                $_SESSION[$key] = "key";
                }
            }else{
                if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $cookie_name = "key";
                $time = NULL;
                $key = $this->generateRandomString(12);
                if(!isset($_COOKIE['key'])){
                setcookie($cookie_name,$key,$time, "/"); // 86400 = 1 day
                $_SESSION[$key] = "key";
                }
                }

            }

}


 public function data(){

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
$dataPOST = $_POST;
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array


//$post=json_decode($request);


 switch ($method) {
  case 'PUT':
    return array("type"=>"PUT","request"=>$request);
    break;
  case 'POST':
    return array("type"=>"POST","request"=>array('body'=>$input,'parameters'=>$this->input,'object'=>$request,'data'=>$dataPOST));
    break;
  case 'GET':
    return array("type"=>"GET","request"=>$_GET);
    break;
  case 'DELETE':
    return array("type"=>"DELETE","request"=>$request);
    break;
  default:
    return array("type"=>"GET","request"=>$request);
    break;
}
}
}
?>
