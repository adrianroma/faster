<?php
include('cache.php');
class curl extends cache {
public $net;
    function __construct($net) {
    $this->net=$net;
    $this->render('content');
    }
    function render($entity){

     $action=$this->net['action']->parameters;
     if(isset($action["request"]["body"]["urls"])){
     $urls =  $action["request"]["body"]["urls"];
     }else{
     $urls = $this->net['action']->data['urls'];
     }

     $string = json_encode($action, JSON_PRETTY_PRINT);

     $fp = fopen('./cache/input.txt', 'w');
     fwrite($fp, $string);
     fclose($fp);

     $this->CURL($urls);

     $data = array("live"=>"true","id"=>"4");
     header('Content-Type: application/json');
     echo json_encode($data);

    }

    public function CURL($URLS){

        if(is_array($URLS)){
	foreach($URLS as $URL){
	$ch = curl_init($URL);
	$fp = fopen("./cache/temporal.txt", "w");
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
	}
        }

    }
    public function getNet($entity){

    $net = (array) $this->net;

    if(isset($net[$entity])){

    return $net[$entity];
    }else{
    return array('error'=>'fail');
    }

    }
}
?>
