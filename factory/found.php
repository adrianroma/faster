<?php
include('data.php');

class found extends data
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

$this->SERVICE_USER();

$this->SERVICE();



$this->net['action']->model = $this->SERVICE_BLOCK('404');




}


function SERVICE_BLOCK($entity){
    
      $block =$this->net['action']->READ('stage.blocks');
      
      return $block[$entity];
    
}

function GET_URL(){
    
    
    return $this->net['action']->URL;
}


function VARIABLE(){
    
    return array('user:session:id','service:search:id','service:search:word','service:search:page','service:search:number');
    
}

function REQUIERE(){
     
       array('SERVICE_SEARCH'=>array('word:string','number:number','page:number'));
    
}

function PROCESS(){
    
    return array('service'=>array('search'=>array('SERVICE_SEARCH')));
}


function GET_PARAMETER(){
$parameters = $this->net['action']->parameters;
$request = $parameters['request'];
if($request!=NULL){
    foreach($request as $param=>$value){
      
       $bit = explode(':',$param); 
       foreach($this->VARIABLE() as $variable){
       $inbit = explode(':',$variable);
       if($bit===$inbit){
            
            $this->variable[$bit[0]][$bit[1]][$bit[2]] = $value; 
        }
       }  

   
    
}
    
}


    
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
        
        foreach($store as $_key=>$item){
            
            foreach($item as $key => $entity){
                
               
              $bit = explode('.',$key);
               $tag = "";
               foreach($bit as $min){
                  
                   $tag .= '["'.$min.'"]';
                   
               }

              eval('$_store'.'["'.$_key.'"]'.$tag.'=$entity;'); 
                
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
