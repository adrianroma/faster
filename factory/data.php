<?php

require 'vendor/autoload.php';
use MongoDB\Driver\Manager;
use MongoDB\Database;

class data {

    public $config;
    public $mongoDB;
    public $DB;
    public $mongoActive = FALSE;
    public $helper;
    public $network;
    private $host;
    public $database;
    private $node;
    public $limit;
    public $entities = array();
    private $databases = array();
    private $weight = array();
    public $error;
    private $system;

    public function startDataBase() {
        if (isset($_SERVER['SERVER_ADDR'])) {
            $currentHost = $_SERVER['SERVER_ADDR'];
        } else {
            $currentHost = '';
        }
        $getConfig = $this->CONFIG('factory.configuration');
        if ($currentHost == '127.0.0.1') {
            $this->host = $getConfig["H"];
        } else {
            $this->host = $getConfig["0"];
        }
        $this->connection();
    }

    public function connection() {
        if (isset($_SERVER['SERVER_ADDR'])) {
            $currentHost = $_SERVER['SERVER_ADDR'];
        } else {
            $currentHost = '';
        }
        if ($currentHost == '127.0.0.1') {
            $host = $this->host['host'];
            $database = $this->host['database'];
            $this->mongoDB = $database;
            $port = $this->host['port'];
            $username = $this->host['username'];
            $password = $this->host['password'];
            $connecting_string = sprintf('mongodb://%s:%d/%s', $host, $port, $database);
            try {
                $connection = new MongoDB\Driver\Manager($connecting_string, array('username' => $username, 'password' => $password));
                $this->database = $this->host['database'];

                $this->DB = $connection;
                $this->mongoActive = TRUE;
            } catch (Exception $e) {

                $this->mongoActive = FALSE;
                $this->helper = $e;
            }
        } else {
            $host = $this->host['host'];
            $database = $this->host['database'];
            $this->mongoDB = $database;
            $port = $this->host['port'];
            $username = $this->host['username'];
            $password = $this->host['password'];
            $connecting_string = sprintf('mongodb://%s:%d/%s', $host, $port, $database);
            try {
                $connection = new MongoDB\Driver\Manager($connecting_string, array('username' => $username, 'password' => $password));
                $DB = $this->host['database'];
                $this->DB = $connection;
                $this->mongoActive = TRUE;
            } catch (Exception $e) {
                $this->mongoActive = FALSE;
                $this->helper = $e;
            }
        }
    }
    
    public function CLIENT(){
        
         $client = new MongoDB\Client();
        
         
         
         
        return $client;
    }
    
    
    public function DRIVER(){
        
      $driver = new MongoDB\Driver\Manager('mongodb://localhost', [
    'username' => 'commerce',
    'password' => 'commerce'
    ]);
        
      return $driver;
      
    }
    

    public function DATABASES() {
        if ($this->mongoActive) {
            $dbname = $this->DB;
            $command = new MongoDB\Driver\Command(['listDatabases' => 1]);
            try {
                $cursor = $dbname->executeCommand('admin', $command);
                $response = $cursor->toArray()[0];
                foreach ($response as $DBs) {
                    if (is_array($DBs)) {
                        foreach ($DBs as $dbs) {
                            $this->databases[$dbs->name] = $dbs->sizeOnDisk;
                        }
                    }
                }
            } catch (Exception $e) {
                var_dump($e);
            }
            return $this->databases;
        }else{
            
            return $this->READ('data.databases');
            
        }
    }

    public function COLLECTIONS($database) {
        if($this->mongoActive){
        try {
            if ($this->mongoActive) {
                $dbname = $this->DB;
                $list = new MongoDB\Driver\Command((['listCollections' => 1]));
                $mlist = $dbname->executeCommand($database, $list);
                $res = $mlist->toArray();
                foreach ($res as $data) {
                    $this->entities[] = $data->name;
                }
            }
            return $this->entities;
        } catch (Error $e) {
            return array("exist" => "false");
        }
        }else{
           return $this->READ('data.collections');   
        }
    }

    public function GET_INDEX($DATABASE, $ENTITY) {
          if($this->mongoActive){
            $COLLECTION = (new MongoDB\Client)->$DATABASE->$ENTITY;
            foreach ($COLLECTION->listIndexes() as $index) {
            var_dump($index);
            }
         }else{
             return $this->READ('data.index');
         }
    }

    public function GET($collection,$entity, $value) {
        if($this->mongoActive){
        $filter = [$entity=>$value];
	$options = [];
	$query = new MongoDB\Driver\Query($filter, $options);
	$manager = $this->DB;
	$database = $this->host['database'];
	$rows = $manager->executeQuery($database.'.'.$collection, $query); // $mongo contains the connection object to MongoDB
	$result = iterator_to_array($rows);
	$result = json_decode(json_encode($result), True);
	return $result;
        }
    }

    public function GET_COLLECTION($entity){
       if($this->mongoActive){
           
             $filter = ['*'=>'*'];
	     $options = [];
            $query = new MongoDB\Driver\Query($filter,$options);
            $database = $this->host['database'];
            $manager = $this->DB;
            $rows=$manager->executeQuery($database . "." ."collection". $query);
        
            $result = iterator_to_array($rows);
            $result = json_decode(json_encode($result), True);
            if (count($result) == 0) {
                $result = array("error" => "empty");
            }
            return $result;
           
           
       }else{
           return $this->READ('data.collection');   
        }
    }

    public function SET_COLLECTION($entities){
         
        if($this->mongoActive){
        $response = array();
        $bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
        foreach ($entities as $entity) {
            $bulk->insert($entity);
        }
        $manager = $this->DB;
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        try {
            $result = $manager->executeBulkWrite($this->host['database'] . '.' .'collection', $bulk, $writeConcern);
        } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
            $result = $e->getWriteResult();
            if ($writeConcernError = $result->getWriteConcernError()) {
                return $this->ERROR($writeConcernError);
            }
            foreach ($result->getWriteErrors() as $writeError) {
                $this->ERROR($writeError);
            }
        } catch (MongoDB\Driver\Exception\Exception $e) {
            $this->ERROR($e->getMessage());
            exit;
        }
        $response["result"] = $result->getInsertedCount();
        $response["total"] = $result->getModifiedCount();
        return $response;
        }
    }

    public function GET_VARIABLE($name){
      if($this->mongoActive){
        $filter = ['attribute'=>$name];
        $options = [];
        $query = new MongoDB\Driver\Query($filter, $options);
        $manager = $this->DB;
        $database = $this->host['database'];
        $rows = $manager->executeQuery($database.'.'.'default', $query); // $mongo contains the connection object to MongoDB
        $result = iterator_to_array($rows);
        $result = json_decode(json_encode($result), True);
        if(isset($result[0])){
        unset($result[0]['_id']);    
        return $result[0];
        }else{
        return array();    
        }
        unset($result[0]['_id']);
        
       }
    }
    public function SET_VARIABLE($name,$label,$type,$group='user'){
        
      $exist=$this->GET_VARIABLE($name);
        
      if(0!=(count($exist))){
        
    $variable = 
       array(
        "entity"=>'variable',
        "attribute"=>$name,
        "value:default"=>'',
        "label:en:us"=>$label, 
        "type:default"=>$type,
        "src:default"=>'',
        "group:default"=>$group,   
        "rwx:default"=>'777',
        "state:true"=>'true',
        "state:false"=>'false',   
        "in:default"=>array()
         );  
        
      if($this->mongoActive){
          
          $response = $this->PUSH('default',array($variable));
          
          
      }
      
      }
      return array('exist'=>'TRUE');
    }
    
    
    public function DELETE_VARIABLE($name){
        
        
       $variable=$this->GET_VARIABLE($variable['name']);
        
      if(0!=(count($variable))){
          
          
      if($this->mongoActive){
          
          $response = $this->DELETE('default',array($variable));     
          
      }
          
      } 
        
       return array('status'=>'TRUE'); 
    }
    
    public function GET_VARIABLE_TYPE($name){
        
        $variable = $this->GET_VARIABLE($name);
        
        return $variable['type'];
        
        
    }
    
    
    public function GET_TYPE($EAV){
        
        
        if($EAV ==='variable'){
            
            return array('string','number','alpha','alphaNumber','list','structure','reference');
            
        }elseif($EAV ==='attribute'){
            
            return array(
                         'id',
                         'entity',
                         'name',
                         'user',
                         'client',
                         'admin',
                         'robot',
                         'human',
                         'process',
                         'machine',
                         'message',
                         'url',
                         'parameter',
                         'session',
                         'source',
                         'title',
                         'link',
                         'description',
                         'classification',
                         'speed',
                         'mark',
                         'require',
                         'option',
                         'field',
                         'grade',
                         'handle',
                         'update',
                         'upgrade',
                         'performance',
                         'health',
                         'filter',
                         'edit',
                         'action',
                         'trigger',
                         'listen',
                         'observer',
                         'watch',
                         'node',
                         'step',
                         'actual',
                         'past',
                         'date',
                         'hour',
                         'day',
                         'week',
                         'way',
                         'vector',
                         'word',
                         'key',
                         'keyword',
                         'combination',
                         'translate',
                         'view',
                         'more',
                         'less',
                         'tool',
                         'random',
                         'rank',
                         'like',
                         'review',
                         'owner',
                         'position',
                         'order',
                         'posible',
                         'page',
                         'account',
                         'billing',
                         'build',
                         'address',
                         'street',
                         'county',
                         'neighborhood',
                         'cost',
                         'price',
                         'discount',
                         'card',
                         'chapter',
                         'collection',
                         'book',
                         'ofert',
                         'credit',
                         'demand',
                         'operation',
                         'resume',
                         'total',
                         'equal',
                         'different',
                         'reference',
                         'result',
                         'ok',
                         'fake',
                         'real',
                         'right',
                         'almost',
                         'opposite',
                         'near',
                         'far',
                         'fail',
                         'response',
                         'request',
                         'answer',
                         'question',
                         'error',
                         'log',
                         'file',
                         'song',
                         'video',
                         'dash',
                         'width',
                         'height',
                         'bottom',
                         'background',
                         'color',
                         'allow',
                         'avail',
                         'visible',
                         'hidden',
                         'full',
                         'empty',
                         'input',
                         'output',
                         'put',
                         'in',
                         'out',
                         'store',
                         'inventary',
                         'stock',
                         'storage',
                         'config',
                         'weight',
                         'shape',
                         'surface',
                         'face',
                         'token',
                         'secret',
                         'label',
                         'related',
                         'map',
                         'coordenate',
                         'lat',
                         'lng',
                         'tag',
                         'parent',
                         'child',
                         'class',
                         'style',
                         'group',
                         'combo',
                         'instruction',
                         'action',
                         'state',
                         'status',
                         'set',
                         'user',
                         'password',
                         'section',
                         'region',
                         'country',
                         'language',
                         'product',
                         'service',
                         'api',
                         'date',
                         'hour',
                         'image',
                         'icon',
                         'logo',
                         'avatar',
                         'slide',
                         'album',
                         'article',
                         'comments'
                );
            
        }
        
        
        
        
    }

    public function GET_ENTITY($collection,$attribute){
        if($this->mongoActive){
        $filter = ['attribute'=>$attribute];
        $options = [];
        $query = new MongoDB\Driver\Query($filter, $options);
        $manager = $this->DB;
        $database = $this->host['database'];
        $rows = $manager->executeQuery($database.'.'.$collection, $query); // $mongo contains the connection object to MongoDB
        $result = iterator_to_array($rows);
        $result = json_decode(json_encode($result), True);
        unset($result[0]['_id']);
        return $result[0];
        }
    }

    public function GET_VALUE($collection,$variable,$value){
        if($this->mongoActive){
        $filter = ['entity:default'=>'store'];
        $options = [];
        $query = new MongoDB\Driver\Query($filter, $options);
        $manager = $this->DB;
        $database = $this->host['database'];
        $rows = $manager->executeQuery($database.'.'.$collection, $query); // $mongo contains the connection object to MongoDB
        $result = iterator_to_array($rows);
        $result = json_decode(json_encode($result), True);
        unset($result[0]['_id']);
        return $result[0];
        }
    }

    public function GET_ATTRIBUTE($collection,$entity){
        if($this->mongoActive){
        $filter = ['entity:default'=>$entity];
        $options = [];
        $query = new MongoDB\Driver\Query($filter, $options);
        $manager = $this->DB;
        $database = $this->host['database'];
        $rows = $manager->executeQuery($database.'.'.$collection, $query); // $mongo contains the connection object to MongoDB
        $result = iterator_to_array($rows);
        $result = json_decode(json_encode($result), True);
        unset($result[0]['_id']);
        return $result[0];
        }
    }

    public function GET_EAV($collection,$entity,$attribute){
        if($this->mongoActive){
        $filter = ['attribute:default'=>$attribute,'entity:default'=>$entity];
        $options = [];
        $query = new MongoDB\Driver\Query($filter, $options);
        $manager = $this->DB;
        $database = $this->host['database'];
        $rows = $manager->executeQuery($database.'.'.$collection, $query); // $mongo contains the connection object to MongoDB
        $result = iterator_to_array($rows);
        $result = json_decode(json_encode($result), True);
        
        if(isset($result[0])){
        unset($result[0]['_id']);
        return $result[0];
        }else{
        return array();    
        }
        
        }
    }
    
    
    
    public function GET_PROPERTY($collection,$entity,$attribute){
        if($this->mongoActive){
        $filter = [$entity=>$attribute];
        $options = [];
        $query = new MongoDB\Driver\Query($filter, $options);
        $manager = $this->DB;
        $database = $this->host['database'];
        $rows = $manager->executeQuery($database.'.'.$collection, $query); // $mongo contains the connection object to MongoDB
        $result = iterator_to_array($rows);
        $result = json_decode(json_encode($result), True);
        
        if(isset($result[0])){
        unset($result[0]['_id']);
        return $result;
        }else{
        return array();    
        }
        
        }
    }
    
    

    public function SET_EAV($collection,$values){
        
       
        
    if($this->mongoActive){
        
        $EAV = array();
        
        $collection = '';
        $lastCollection ='';
        
        foreach($values as $value){
        
        
            
        //$exist = $this->GET_EAV($collection, $entity, $attribute);
        
            
        $collection= $value['collection:default'];
        
        
        unset($value['collection:default']); 
       
        
       $eav = array(
        "value:default"=>'',
        "label:en:us"=>'', 
        "type:default"=>'sample',
        "src:default"=>'',
        "group:default"=>'user',   
        "rwx:default"=>'777',
        "state:true"=>'true',
        "state:false"=>'false',   
        "in:default"=>''
         );  
       
      
       
       
        $eav = array_merge($value,$eav);
       
       
         array_push($EAV,$eav);
       
        }
       
    
        $result = $this->PUSH($collection,$EAV);
       
     }
    }

    public function GET_AND($collection,$entities){
         if($this->mongoActive){
         // $filter = ['$and'=>array(array('state'=>'Distrito Federal'),array($entity=>$value))];
         $filter = ['$and'=>$entities];
         $options = [];
         $query = new MongoDB\Driver\Query($filter, $options);
         $manager = $this->DB;
         $database = $this->host['database'];
         $rows = $manager->executeQuery($database.'.'.$collection, $query); // $mongo contains the connection object to MongoDB
         $result = iterator_to_array($rows);
         $result = json_decode(json_encode($result), True);
         return $result;
         }
    }


    public function COLLECTION($collection) {
      if($this->mongoActive){
          
        $result = array("error" => "TRUE");
        $manager = $this->DB;
        $collections = $this->COLLECTIONS($this->host["database"]);
        if (in_array($collection, $collections)) {
           
            $options = ['projection'=>["name" =>['$regex'=>'a']]];
            
            $query = new MongoDB\Driver\Query([],[]);
           
            $database = $this->host['database'];
            $rows = $manager->executeQuery($database . "." . $collection, $query);
       
            $result = iterator_to_array($rows);
            $result = json_decode(json_encode($result), True);
        
            if (count($result) == 0) {
                $result = array("error" => "empty");
            }
            return $result;
         } else {
            $database = $this->host['database']; 
            $command = new MongoDB\Driver\Command([
            "create" => $collection
            ]);
            $result = $manager->executeCommand($database, $command);
            $result = array("error"=>"created");
            return $result;
         }
      }
    }

    public function PUSH($collection, $entities = array()) {
        if($this->mongoActive){
        $response = array();
        $bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
        if(is_array($entities)){
        foreach ($entities as $entity) {
            if(is_array($entity)){
            $bulk->insert($entity);
            }else{
            //$bulk->insert(array());    
            }
        }
        }
        $manager = $this->DB;
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        try {
            if(count($bulk)!=0){
            $result = $manager->executeBulkWrite($this->host['database'] . '.' . $collection, $bulk, $writeConcern);
            }else{
            $result = '';    
            }
        } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
            $result = $e->getWriteResult();
            if ($writeConcernError = $result->getWriteConcernError()) {
                return $this->ERROR($writeConcernError);
            }
            foreach ($result->getWriteErrors() as $writeError) {
                $this->ERROR($writeError);
            }
        } catch (MongoDB\Driver\Exception\Exception $e) {
            $this->ERROR($e->getMessage());
            exit;
        }
        if($result!=''){
        $response["result"] = $result->getInsertedCount();
        $response["total"] = $result->getModifiedCount();
        }else{
        $response["result"] = array();
        $response["result"] = array();
        }
        return $response;
        }
    }
    
    public function ERROR($error){
        
         var_dump($error);
        
    }
    

    public function DELETE($collection, $entities = array()) {
        if($this->mongoActive){
        $response = array();
        $bulk = new MongoDB\Driver\BulkWrite;
        foreach ($entities as $entity) {
    
            $bulk->delete($entity);
        }
        $manager = $this->DB;
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        try {
            $result = $manager->executeBulkWrite($this->host['database'] . '.' . $collection, $bulk, $writeConcern);
        } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
            $result = $e->getWriteResult();
            if ($writeConcernError = $result->getWriteConcernError()) {
                return $this->ERROR($writeConcernError);
            }
            foreach ($result->getWriteErrors() as $writeError) {
                $this->ERROR($writeError);
            }
        } catch (MongoDB\Driver\Exception\Exception $e) {
            $this->ERROR($e->getMessage());
            exit;
        }
       
        $response["total"] = $result->getModifiedCount();
        return $response;
        }
    }    
    
    

    public function POP($collection,$id ,$entityValue) {
        if($this->mongoActive){
        $pop = [];
        $filter = array();
        $options = [];
        $manager = $this->DB;
      
       
        foreach ($entityValue as $k=>$ids) {
            $filter = [$id => $ids];
            $query = new MongoDB\Driver\Query($filter, $options);
            $database = $this->host['database'];
            $rows = $manager->executeQuery($database . "." . $collection, $query); // $mongo contains the connection object to MongoDB
            $array = json_decode(json_encode($rows), True);
            foreach ($rows as $r) {
                $st =(json_decode(json_encode($r), True));
                unset($st['_id']);
                $pop[$k] = $st;
            }
           
        }
       
       
        //return array('not'=>'error');
        return $pop;
        }
    }
    
    
    public function UPDATE($collection, $entity ,$id, $value){
        
         $database = $this->host['database'];
        
         $item = $this->GET_PROPERTY($collection,$entity,$id);
         
         
         foreach($item[0] as $key=>$bit){
             
             $item[0][$key]=$value[$key];
             
         }
        
          
         
         $bulk = new MongoDB\Driver\BulkWrite;
         $bulk->update(
           [$entity => $id],$item[0]
);

$manager = $this->DB;

$result = $manager->executeBulkWrite($database . "." . $collection, $bulk);
        
return $result;
        
    }

    public function SEARCH($collection, $variable = "word", $like, $num, $page) {
        if($this->mongoActive){
        $like = str_replace(array('/'), array('.'), $like);
        $limit = $num;
        $skip = ($page - 1) * $limit;
        $manager = $this->DB;
        $database = $this->host['database'];
        $regex = new MongoDB\BSON\Regex($like, 'i');
        $filter = array($variable => $regex);
        $options = ["sort" => array($variable => 1), "skip" => $skip, "limit" => $limit];
        $query = new MongoDB\Driver\Query($filter, $options);
        $rows = $manager->executeQuery($database . "." . $collection, $query); // $mongo contains the connection object to MongoDB
        $rows = iterator_to_array($rows);
        $command = new \MongoDB\Driver\Command(['count' => $collection, 'query' => $filter]);
        $total = $manager->executeCommand($database, $command);
        $total = iterator_to_array($total);
        $total = (array) $total[0];
        $total["page"] = $page;
        $total["limit"] = $limit;
        $total["search"] = $like;
        $total["variable"] = $variable;
        $total = (object) $total;
        array_unshift($rows, $total);
        $total = json_decode(json_encode($rows), True);
        return $total;
        }
    }
    
    public function READ($collection){

      $collection=str_replace('.','/',$collection);
      $str=file_get_contents("./".$collection.".json");
      return json_decode($str,true);

    }
    
    public function WRITE($collection,$object){

       $collection=str_replace('.','/',$collection);
       $pathFile="./".$collection.".json";
       $file = fopen($pathFile,"w");
       $string = json_encode($object,JSON_PRETTY_PRINT);
       fwrite($file,$string);
       fclose($file);
    
     }
     
    public function MATCH($collection,$variable,$value){
        
         $collection =$this->READ($collection);
        
         foreach($collection as $item){
             
             
             
         }
        
    } 
    
    function CONFIG($entity){

    $entity=str_replace('.','/',$entity);
    try{
    $str=file_get_contents("./".$entity.".json");
    $this->system=json_decode($str,true);
    }catch(Exception $e){
    return $e;   
    } 
    return $this->system;
    }
    
}


