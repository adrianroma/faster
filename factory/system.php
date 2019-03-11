<?php
class system {
    public $system = array();
    public $net;
    public $src;
    public $attribute;
    public $data= array();
    function __construct($net) {
    $this->net=$net;
    $this->start();
    }
    function start(){

     

    
    
     $this->net['action']->input = $this->input();

    }
    
    
    function countdim($array)
{
    if (is_array(reset($array)))
    {
        $return = countdim(reset($array)) + 1;
    }

    else
    {
        $return = 1;
    }

    return $return;
    }
    
    
    function input(){
        
       $parameters = $this->net['action']->parameters;
       $_variable = array();
       $_value = array();
       $_default = array(); 
       
       
       if(isset($parameters['request'])){
           
        
           
           foreach($parameters['request'] as $variable =>$value){
               
          
               
              $string= str_replace('_', '"]["', $variable);
              $_var = '$_variable["'.$string.'"]=$value;'; 
 
              @eval($_var);
 
           }
           
         
           
       }
       
     
        return $_variable;
        
    }
    

    function read($entity){

    $entity=str_replace('.','/',$entity);
    try{
    //$str=file_get_contents("./".$entity.".json");
    //$this->system=json_decode($str,true);
    }catch(Exception $e){
    return $this->system;    
    } 
    return $this->system;
    }

    function exist($entity){
        
    $entity=str_replace('.','/',$entity);
    $pathFile= "./".$entity.".json";

    if(file_exists($pathFile)){
    return true;
    }else{
    return false;
    }
    }
    
 
    
  
    
    
    function getVariables(){
        
        
    }
    
    
    function getState(){
        
        
        
    }
    
    function getProps(){
        
        
        
    }
    
  
    
    function load(){
        
        
     $parameters  =  $this->net['action']->parameters;

     $inputAttributes = $parameters["request"];

     $inputType = $parameters["type"];

     $defaultAttribute = $this->read('resource.default');
     $systemAttribute =  $this->read('resource.system');

     $attributes = array_merge($defaultAttribute,$systemAttribute);
 

     if(is_array($attributes)){
     foreach($attributes as $key=> $attribute){

      if(isset($attribute['src'])){
         
         $path = str_replace('.','/',$attribute['src']);
         
         $bit = explode('.',$attribute['src']);
         
         $final = count($bit)-1;
               
         $class = $bit[$final];
        
         include('./factory/'.$path.".php");
         eval('$src= new '.$class.'($this->data);');
          $value =$src->src();
          $attributes[$key]['value'] = $value;
         }
       }
     }
        
    }

 
    


}
?>
