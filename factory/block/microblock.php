<?php


class microblock {
public $data;    
    function __construct($data) {
        $this->data = $data;
        $this->put();
    }
   function put(){
       return TRUE;
   }
   public function src(){
       return TRUE;
   }
}
?>
