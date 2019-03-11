<?php
include('data.php');

class trigger extends data
{
public $entity;
public $net;
public $data;

function __construct($net) {
$this->net = $net;
$this->startDataBase();

$this->SERVICE_STORE();

$this->entity="match";

$uri = $this->net['action']->uri;

//


$match = "";

$words= str_replace('_',' ', $uri);

$parameter = $this->net['action']->parameters;

$section = $this->net['action']->uri;

 $_section =explode('/',$section);

 if(is_numeric($_section[0])){
     
   $match = $_section[0];  
 }
 
 if(isset($parameter['request']['code'])){
   $match = $parameter['request']['code'];  
 }

//$collection = $this->SEARCH('places_mexico','code',111527,100,1);



$collection=$this->GET_PROPERTY('places_mexico','career',$match);

$last = array();

$_collection = array();

 foreach($collection as $key=>$item){
     
     if(!in_array($item['place'],$last)){
     array_push($last, $item['place']);
     
     array_push($_collection,$collection[$key]);
     
     }     
         
 }
 
 $block=$this->SERVICE_BLOCK('start');
 
 if(TRUE){
 $this->net['action']->model = $block;
 }else{
$this->net['action']->redirect('404');     
 }
    


$this->net['action']->data=$_collection;

$this->net['action']->user=array('user');
$this->net['action']->service=array('service');
$this->net['action']->health=array('health');
$this->net['action']->input=array('input');


}

function SERVICE_BLOCK($entity){
    
      $block =$this->net['action']->READ('stage.blocks');
      
      return $block[$entity];
    
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

    

}

?>
