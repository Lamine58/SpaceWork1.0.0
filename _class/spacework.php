<?php 
    
   #spacework v1.0.0  

   class spacework {

    public $fields=[];

    public function __construct(){

      global $db;
      $class_name=get_called_class();
      $query = $db->query("SELECT column_name  FROM information_schema.columns WHERE table_schema='".DB."' AND table_name='".$class_name."'");
      $fields_class = $query->FetchAll(PDO::FETCH_OBJ);

      foreach ($fields_class as $field) {
         $field_name=$field->column_name;
         $this->$field_name=null;
      }


    }

    public static function DB(){

      global $db; 

      return $db;

    }

    public static function create($_array){

      global $db;

      $class_name=get_called_class();
      $query = $db->query("SELECT column_name  FROM information_schema.columns WHERE table_schema='".DB."' AND table_name='".$class_name."'");
      $fields_class = $query->FetchAll(PDO::FETCH_OBJ);
      $inset_into='';

      foreach ($fields_class as $field) {
        $field_name=$field->column_name;
        $inset_into.=" $field_name=:$field_name ,";
      }

      $query = $db->prepare("INSERT INTO $class_name SET ".substr($inset_into, 0,-1));

      foreach ($fields_class as $field) {
        
        $field_name=$field->column_name;
          if(isset($_array[$field_name]))
            $query->bindValue(":$field_name", strip_tags($_array[$field_name]));
          else
            $query->bindValue(":$field_name",'');
      }

      return $query->execute();     
    }

    public function add(){

      global $db;
      $class_name=get_called_class();
      $inset_into='';

      foreach ($this->fields as $field) {
        $inset_into.=" $field=:$field ,";
      }

      $query = $db->prepare("INSERT INTO $class_name SET ".substr($inset_into, 0,-1));

      foreach ($this->fields as $field) {
        $query->bindValue(":$field", strip_tags($this->$field));
      }

      return $query->execute();

    }

    public function update(){


      if(is_null($this->id))
        return false;

      global $db;
      $class_name=get_called_class();
      $inset_into='';

      foreach ($this as $field => $value) {
        if($field!="fields")
          $inset_into.=" $field=:$field ,";
      }

      $query = $db->prepare("UPDATE $class_name SET ".substr($inset_into, 0,-1).' WHERE id='.$this->id);

      foreach ($this as $field => $value) {
        if($field!="fields")
          $query->bindValue(":$field", strip_tags($value));
      }

      return $query->execute();

    }

    public static function all(){

      global $db;
      $class_name=get_called_class();

      $query=$db->prepare("SELECT * FROM $class_name");
      $query->execute();
      return $query->FetchAll(PDO::FETCH_OBJ);

    }


    public static function find($id){

      global $db;
      $class_name=get_called_class();
      $query = $db->query("SELECT column_name  FROM information_schema.columns WHERE table_schema='".DB."' AND table_name='".$class_name."'");
      $fields_class = $query->FetchAll(PDO::FETCH_OBJ);

      if (!is_null($id)){

        $query=$db->prepare("SELECT * FROM $class_name WHERE id = :id LIMIT 1");
        $query->execute(["id"=>$id]);
        $fields_this=$query->Fetch(PDO::FETCH_OBJ);

        $object = new $class_name;        

        if(!is_object($fields_this))
          return $object;

        foreach ($fields_class as $field) {
           $field_name=$field->column_name;
           $object->$field_name=$fields_this->$field_name;
        }

        return $object;

      }

    }

    public static function delete($id){

      global $db;
      $class_name=get_called_class();

      $query=$db->prepare("DELETE FROM $class_name WHERE id = :id ");
      return $query->execute(["id"=>$id]);            
    }

    public static function get($field,$value){

      global $db;
      $class_name=get_called_class();

      $query=$db->prepare("SELECT * FROM $class_name WHERE $field=:value");
      $query->execute(["value"=>$value]);
      return $query->FetchAll(PDO::FETCH_OBJ);            
    }

    public static function query($condition=NULL){

      if (is_null($condition))
        return NULL;

      global $db;
      $class_name=get_called_class();

      $query=$db->prepare("SELECT * FROM $class_name WHERE ".implode(' ', $condition));
      $query->execute();
      return $query->FetchAll(PDO::FETCH_OBJ);            
    }

  }
        