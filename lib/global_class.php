<?php

require_once 'config_class.php';
require_once 'datebase_class.php';


abstract class GlobalClass{
  
  protected $db='';
  protected $table_name='';
  protected $config;
  // public $valid;

  protected function __construct($table_name, $db=''){

    if ($this->db=='') $this->db=new DateBase();     //если не указана другая база, то соединяемся с базой из класса DataBase
    else $this->db=$db;
    $this->table_name=$table_name;
    $this->config = new Config();
    // $this->valid = new CheckValid();

  }


//
//
// //  abstract public function validFieldValueDB($field,$value);
// //
// //адаптер для manage_class
//  protected function manageadd($new_values,$parent='Global::manageadd->add'){
//      return $this->add($new_values,$parent);
//  }
//
//
// protected function add($new_values,$parent='Global::add->insert'){
//     return $this->db->insert($this->table_name,$new_values,$parent);
// }
//
//
//
//
//
//
//
// //передаётся ассоциативный массив полей со значениями в $udp_fields
// protected function updateFieldsOnID($id,$udp_fields){
//     if ($this->db->existsID($this->table_name,$id))
//     return $this->db->update($this->table_name,$udp_fields,"`id`='".$id."'");
//     else return false;
// }   //проверить на выходе должен бытьтакой запрос в базу который работает UPDATE rsa_users SET `name`='newlogin',`passwd`='12345',`regdate`='1642921365' WHERE `id`='49'
//
// public function deleteOnID($id){
//     return $this->db->deleteOnID($this->table_name,$id);
// }
//
// public function deleteALL(){
//     return $this->db->deleteALL($this->table_name);
// }

protected function getField($field_out,$field_in,$value_in){
    return $this->db->getField($this->table_name,$field_out,$field_in,$value_in);
}

protected function getFieldOnID($id,$field){
    return $this->db->getFieldOnID($this->table_name,$id,$field);
}

protected function setFieldOnID($id,$field,$value){
    return $this->db->setFieldOnID($this->table_name,$id,$field,$value);
}

protected function setField($tofield,$tovalue,$offield,$ofvalue){
    return $this->db->setField($this->table_name,$tofield,$tovalue,$offield,$ofvalue);
}

public function get($id){
  return $this->db->getElementOnID($this->table_name,$id);
}

public function getALL($order="",$up=true){

  return $this->db->getALL($this->table_name,$order,$up);
}

//получить все записи конкретного поля таблицы
public function getAllforField($field, $order='', $up=true){
      return $this->db->select($this->table_name,array($field),'',$order,$up);
}


//здесь мы вызываем метод с массивами array($fields),array($values) потому что эту выборку можем делать по нескольким полям сразу
//$fields,$values это массивы. можно передвать как массив из одного значения. но массив.
protected function getALLOnField($fields,$values,$order="",$up=true){
 return $this->db->getALLOnField($this->table_name,$fields,$values,$order,$up);
}

public function getRandomElement($count){
  return $this->db->getRandomElements($this->table_name,$count);
}

public function getLastID(){
    return $this->db->maximumID($this->table_name);
}

public function getCount(){
   return $this->db->getCount($this->table_name);
}

  protected function isExists($field, $value){
   return $this->db->isExists($this->table_name, $field, $value);
}


  //адаптер метода query
  protected function query($query){
    return $this->db->query($query);
  }

//адаптер метода DataBase->checkFields (в формате ассоциативного массива со значениями, но здесь проверяем только поля), в случае ошибок будет запись в лог на сервере /log/err/err_database_class
    // public function checkFields($fields,$parent='DataBase::checkFields')
    // {
    //     return $this->db->checkFields($this->$table_name, $fields,$parent='GlobalClass->db->checkFields');
    // }



protected function search($words, $morphyarray='', $fields){
  return $this->db->search($this->table_name, $words,$morphyarray, $fields);
}

}

 ?>
