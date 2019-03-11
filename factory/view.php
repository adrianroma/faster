<?php

include('cache.php');

class view extends cache {
public $net;
public $url;
public $uri;
    function __construct($net) {
    header('Access-Control-Allow-Origin: *');
    $this->net=$net;
    $this->url = $this->net['action']->url;
    $this->uri = $this->net['action']->uri;
    $this->startCache();
    $this->render('content');
    $this->Data = array();
    $this->Service = array();
    $this->Health = array();
    }

    function axion($entity){



    }

    function render($entity){
        
        $model=$this->net['action']->model;
        
        foreach($model as $m){
            
           $m = str_replace('.','/',$m);
           
           if(file_exists('./'.$m.'.php')){
           include('./'.$m.'.php');
           }
        }
    
        
        unset($this->net);
        
    }
    public function data(){
          
         return json_encode($this->net['action']->data);
    }
    
    public function store(){
        
         return json_encode($this->net['action']->store);
        
    }
    
    public function user(){
        
         return json_encode($this->net['action']->user);
        
    }
    
    public function url(){
        
        return $this->net['action']->protocol.'://'.$this->net['action']->host;
    }
    
    public function service(){
          $this->Service= $this->net['action']->service;
        
        return json_encode($this->Service);
    }

    public function health(){
         $this->health = array('health'=>'ok');
        
        
        return json_encode($this->health);
    }
    
    public function scripts(){
    return $this->net['action']->code;
    }
    public function parameters(){

    return json_encode($this->net['action']->parameters);

    }

    public function getNet($entity){

    $net = (array) $this->net;

    if(isset($net[$entity])){

    return $net[$entity];
    }else{
    return array('error'=>'fail');
    }

    }
 public function log(){

     $action=$this->net['action']->parameters;
     $string = json_encode($action, JSON_PRETTY_PRINT);

     $fp = fopen('./cache/input.txt', 'w');
     fwrite($fp, $string);
     fclose($fp);

 }

 public function get_post($url,$data){

//Initiate cURL.
$ch = curl_init($url);
//Encode the array into JSON.
$jsonDataEncoded = json_encode($data);

//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//Execute the request
return curl_exec($ch);

 }



}
?>
