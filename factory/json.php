<?php
include('cache.php');
class json extends cache {
public $net;
    function __construct($net) {
    $this->net=$net;
     $this->render('content');
    }
    function render($entity){

     $action=$this->net['action']->uri;
     $this->net['action']->cookie($action);
     $data =  $this->net['action']->data;
 
     foreach($data as $key=>$item){
         if(isset($data[$key]['_id'])){
         unset($data[$key]['_id']);
         }
     }

     header('Content-Type: application/json;charset=utf-8');
     $this->log();
  
     echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);


    }
    public function block($entity){

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
     
  
    
      try
    {
     $fp = fopen('./cache/input.txt', 'w');
     fwrite($fp, $string);
     fclose($fp);
      return array('status'=>'true');
     }catch(Exception $e){
      return array('status'=>'false');
     }

 }
}
?>
