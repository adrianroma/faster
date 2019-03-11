<?php
class factory
{
public $entity;
public $net=array();
public $set=array();
public $action;
function __construct($action,$entity) {

 

$this->net['action']=$action;

$entities = explode('.',$entity);
foreach($entities as $key=>$entity){

if($entity!=NULL){
include('./factory/'.$entity.'.php');
}

$this->set[$key]=$entity;

if($entity!=NULL){
eval('$this->net["'.$entity.'"]= new '.$entity.'($this->net);');
}

}

$this->follow();

}

function follow(){



}

}
?>
