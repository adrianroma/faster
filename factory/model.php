<?php
include('data.php');

class model extends data
{
public $entity;
public $net;
public $data;
public $store;
public $user;
public $service;
public $attribute;
public $variable;
public $code;

function __construct($net) {
  
$this->net = $net;
$this->startDataBase();
$this->search = array();

$this->GET_PARAMETER();

$this->SERVICE_STORE();

$this->SERVICE_STORE('stage.default');

$this->SERVICE_USER();

$this->SERVICE();

$this->net['action']->model = $this->SERVICE_BLOCK('start');




}


function SERVICE_BLOCK($entity){
    
      $block =$this->net['action']->READ('stage.blocks');
      
      return $block[$entity];
    
}


function GET_URL(){
    
    
    return $this->net['action']->URL;
}


function VARIABLE(){
    
    return array('user');
    
}

function REQUIERE(){
     
       array('user'=>array('name:string','email:string','phone:number'));
    
}

function PROCESS(){
    
    return array('service'=>array('search'=>array('SERVICE_SEARCH')));
}


function GET_PARAMETER(){
    $response = [];
    
    $raw = $this->net['action']->parameters;
    
    
    if($raw['type']==='POST'){
    $input =   $raw['request']['body']; 
        
    }else{
    $input = $this->net['action']->input;
    }
    
    
    foreach($input as $key=>$action){
    
  
    $response[] = $action;
    
    
    }
   
    return $response;
 
   
}

function SERVICE(){
    
     $this->net['action']->service = array('service:store','service:search','service:user');
    
}

function SERVICE_CODE(){
    
    $this->net['action']->code = array('fortune'=>array('src'=>'http://www.google.com'));
    
}

function SERVICE_STORE() {

        $store =$this->net['action']->READ('stage.default');
        
        $_store = array();
        
        if(is_array($store)){
        
        foreach($store as $_key=>$item){
            
            if(is_array($item)){
            
            foreach($item as $key => $entity){
                
               
              $bit = explode('.',$key);
               $tag = "";
               foreach($bit as $min){
                  
                   $tag .= '["'.$min.'"]';
                   
               }

              eval('$_store'.'["'.$_key.'"]'.$tag.'=$entity;'); 
                
            }
            }
        }
       }

        $this->net['action']->store = $_store;
        
 }

function SERVICE_SEARCH($service,$word,$number=10,$page=1){
      
$this->net['action']->data = $this->SEARCH($service,'word',$word,$number,$page);
    
}

function SEARCH_STORE($value){
    
$this->net['action']->data = $this->SEARCH('places_mexico','career',$value,10,1);
   
}

function SERVICE_USER(){
   
$this->net['action']->user = array('user'=>'guest','secret'=>'sjfejajfñdr','language'=>'us','region'=>'us');
    
}






}





?>
