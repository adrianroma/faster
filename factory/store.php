<?php
include('data.php');
require 'vendor/autoload.php';
class store extends data
{
public $entity;
public $net;
public $data;
public $attribute;
public $variable;

function __construct($net) {
$this->net = $net;    

$props = $this->READ('props.user');



$this->startDataBase();
// $this->net['action']->input;



$action =$this->LISTEN();





$this->RESPONSE($action);

}


function LISTEN(){
    $response = [];
    
    $raw = $this->net['action']->parameters;
    
    
    if($raw['type']==='POST'){
    $input =   $raw['request']['body']; 
        
    }else{
    $input = $this->net['action']->input;
    }
    
    
    foreach($input as $key=>$action){
    
    if(isset($action['action'])){  
        
     $axion=$this->GET_ACTION($action['action']); 

     eval('$response[$key] = $this->'.$axion.'($input[$key]);');

    }else{
     
     $axion=$this->GET_ACTION('error'); 

     eval('$response[$key] = $this->'.$axion.'($input[$key]);');
     
    }
    
    
    
    }
   
    return $response;
 
   
}

function RESPONSE($response){
    
    $this->net['action']->data = $response;
    
}

function PLAY(){
    
     
    
    
}

function GET_ACTION($action){
    
    try{
    
    $actions = array('service'=>'_PROPERTY','error'=>'_ERROR');
    if(isset($actions[$action])){
    return $actions[$action];
    }else{
    return $actions['error'];    
    }
    
     }catch(ErrorException $e){
         
         
     }
}

function PARAMETERS(){
    

    
}

function _PROPERTY($input){
    
    try{
        
    $property = ["type"=>"property",'requiere'=>["es"=>"text","en"=>"text"],"class"=>"word","category"=>"word","rwx"=>"number","group"=>"word","started"=>"date"];    
    
    
    return array('error'=>FALSE,'out'=>$property,'input'=>$input);
        
    }catch(ErrorException $e){
          return array('error'=>TRUE,'out'=>$property,'input'=>$input);
    }


}




function _ERROR($input=''){
    
    try{
        
     return array('error'=>FALSE,'input'=>'','out'=>$input);   
        
    }catch(ErrorException $e){
        
        
     return array('error'=>TRUE,'out'=>'','input'=>$input);
    }
   
}




}



?>
