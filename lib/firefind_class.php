<?php

require_once 'config_class.php';
require_once 'fragments_class.php';
// //require_once 'datebase_class.php';
//
class fireFind{
  protected $mysqli='';
  protected $table_name='';
  protected $config;


    public function __construct($table_name){
    $this->config = new Config();

  $this->mysqli = new mysqli($this->config->host, $this->config->user, $this->config->password, $this->config->db);
  $this->mysqli->query("SET NAMES 'utf8'");

  $this->table_name=$table_name;

    }

    public function query($query){
      return $this->mysqli->query($query);
    }
  //
    //метод выборки из таблицы, универсальный
    //>select($table_name,array('*'),'',$order,$up);
    public function select($table_name, $fields, $where='', $order='', $up=true, $limit='')
    {

    //  echo("Мы в select");
      for ($i=0;$i<count($fields);$i++){
        if ((strpos($fields[$i],"(") === false) and ($fields[$i]!="*")) $fields[$i]="`".$fields[$i]."`";
  //    print_r($fields[$i]);
      }
        $fields=implode(",",$fields);

        $table_name = $this->config->db_prefix.$table_name;

        if (!$order) $order = "ORDER BY `id`";  //если порядок не задан задаём его по id
          else {
            if ($order!="RAND()") {  //ИНАЧЕ, ЕСЛИ ЗАПРОС НА СЛУЧАЙНУЮ СТРОКУ
                                  $order = "ORDER BY `$order`";
      //                            if (!$up) $order .= " DESC";  //если не по убыванию
                                  }
            else $order = "ORDER BY $order";
          }
          if (!$up) $order .= " DESC";

          if ($limit) $limit = "LIMIT $limit";
          if ($where) $query="SELECT $fields FROM $table_name WHERE $where $order $limit";
          else $query = "SELECT $fields FROM $table_name $order $limit";
    //echo("$query"."<br>");
          $result_set = $this->query($query);
          if (!$result_set) return false;
          $i=0;
          while ($row = $result_set->fetch_assoc()){
            $data[$i] = $row;
            $i++;
          }
          $result_set->close();

          return $data;
      }

      public function getField($table_name, $field_out, $field_in, $value_in){
        $date=$this->select($table_name, $field_out,"`$field_in`='".addslashes($value_in)."'");
        if (count($date)!=1) return false; //если получаем несколько значений или 0, то возвращаем фолс
        return $date[0];
      }


      public function getFieldOnId($table_name, $id, $field_out){
        if (!$this->existsID($table_name, $id)) return false;  //если такого id в таблице нет то выход с фолс.

        return $this->getField($table_name, $field_out,'id',$id);
      }

      public function getAll($table_name, $order='', $up=true){
            return $this->select($table_name,array('*'),'',$order,$up);
      }
      //получить все записи конкретного поля таблицы
          public function getAllforField($table_name, $field, $order, $up){
                return $this->select($table_name,array($field),'',$order,$up);
          }

          public function getElementOnID($table_name,$id){
              if (!$this->existsID($table_name,$id)) return false;
              $arr = $this->select($table_name, array('*'),"`id`='".$id."'");
              return $arr[0];
          }

          //метод возвращает количество записей в таблице
          public function getCount($table_name){
            $data = $this->select($table_name, array("COUNT(`id`)"));
            return $data[0]["COUNT(`id`)"];
          }



          public function search($table_name, $words,$arraywords='', $fields){
            if ($arraywords==''){ //массив не передавался, а он передаётся только с включённым морфиусом то создаём его сами. более простой естественно.
            $words = mb_strtoupper($words); //привести в верхний регистр
            $words = trim($words);// обрезать пробелы спереди и сзади
            $words = quotemeta($words); //заковычить специальные символы для работы с sql
            if ($words=='') return false;
            $where ='';
            $arraywords=explode(' ',$words); //разбить строку слов на массив слов
            }
            //реализуем поиск по хотябы одному присутствию одного из указанных слов
            $logic = 'OR';

            foreach ($arraywords as $key => $value) {
              if (isset($arraywords[$key-1])) $where .= $logic; //если это уже не первый элемент то добавляем
              for ($i=0; $i<count($fields);$i++){

                    $where .= "`".$fields[$i]."` LIKE '%".addslashes($value)."%'";
                    if (($i + 1) != count ($fields)) $where .= " OR"; //если это у нас не последнее поле то мы добавляем OR

              }
            }
            $results = $this->select($table_name, array('*'), $where);
            if (!$results) return false;
            $k=0;
            for ($i=0; $i<count($results);$i++){
              for ($j=0; $j<count($fields);$j++){
                $results[$i][$fields[$j]] = mb_strtoupper(strip_tags($results[$i][$fields[$j]])); //приводим всё в нижний регистр и убираем все теги html из статей
              }
              $data[$k] = $results[$i];
              $data[$k]['relevant'] = $this->getRelevantForSearch($results[$i], $fields, $words);
              $k++;
            }
            return $this->orderResultSearch($data,'relevant',false,$words,$table_name);
          }


          private function getRelevantForSearch($result, $fields, $words){
            $relevant=0;
            $arraywords=explode(" ", $words);
            for ($i=0; $i<count($fields); $i++){
              for ($j=0; $j<count($arraywords); $j++){
                  $relevant += substr_count($result[$fields[$i]], $arraywords[$j]);
              }
            }
            return $relevant;
          }

          //!!!можно ускорить
          private function orderResultSearch($data,$key,$order=false,$words,$table_name){  //массив по убыванию получаем если order не указываем. удобно для поиска.
            $data=$this->uporyad_puzir($data,$key,$order);    //здесь у нас упорядочен массив по количеству вхождений слов, будет статья сверху с максимум вхождений
          //нужно этот показатель приводить в процентном соотношении по общему количеству символов. чтоы не получалось что одна большая статья будет вылазить вверх постоянно просто потому что там в 10 раз больше любых символов. И/или все статьи должны быть у меня примерно одного размера для релевантного поиска.
            return $this->orderResultSearch2($data,$words,$table_name); //а в этом массиве мы получаем упорядоченные фрагменты. возможно что стоит изменить релевантность по фрагментам а не по количеству вхождений. тестить нужно 17.02.22.
          }

          //моё упорядочивание с помощью фрагментов
          private function orderResultSearch2($data,$words,$table_name){
          //текущую релевантность сохраним так, добавим это число к четвёртому элементу массива фрагментов. чтобы каждой статья в которой больше встречается по количеству искомых слов добавляла веса эти фрагментам. !!!ПОКА БЕЗ ЭТОГО МОМЕНТА возможно лишнее.
          //-------------------------------------------------------------------------
          //массив фрагментов будет составляться на основе поля full_text и упорядочен по убыванию, и сохранён в отдельное поле массива data поле fragments целиком. по сколько элементов выводить из этого массива будем решать уже из класса вывода на странице.

          $fr = new fragments ();
  //      var_dump();
          //
          // // выбираем поле из таблицы из которого будем делать фрагменты
          // if ($table_name=='article') //если поиск по статьям
          // $thisfild='full_text';
          // if ($table_name=='section') //если поиск по разделам
          // $thisfild='description';
          $thisfild=$this->config->field[0];
      //    echo($thisfild);
          for ($i =0; $i<count($data); $i++){
          $d1=$fr->getFragments_v2($data[$i][$thisfild],$words); //получаем массив фргаментов этого элемента из общего массива поля thisfild
          $d2=$fr->getGoodArrfrag($d1); //получаем и создаём 4-е поле, поле возможных пересечений фрагментов между собой, в этом массиве для разных слов. потому что у нас сейчас общий массив фрагментов для всех слов
          $d3=$this->uporyad_puzir($d2,3,false);
          //упорядочен по 4-му полю от 0-я в этом массиве масиввов, с фрагментами. где четвёртое поле является суммой пересечений фрагментов и слов между собой.
          $data[$i]['fragments']=$d3; //вносим упорядоченный массив в поле фрагментов.
          }
          return $data;
          }

          //
          // private static function myswap($x,$y){
          //                 $t = $x;
          //                 $x = $y;
          //                 $y = $t;
          // }

          private function relevantArrFragsData($data,$words){
            //return $this->relevantArrFrags($data,$words);

            $datafrags = new fragments($data,$words);
            return $datafrags->arrFrags;
          }


          //вернуть упорядоченный массив либо по возрастанию либо по убыванию, зависит от переменной $b, если true то массив упорядочен по возрастанию, с минимума идти к максимому. параметр $key передаёт имя поля по которым идёт упорядочевание
          private static function uporyad_puzir($a,$key,$b){
              if ($b) {
                     $w=count($a)-1;
                     for ($i=0;$i<$w;$i++){
                                $c=$w-$i;
                                for ($j=0;$j<$c;$j++){
                                        if ($a[$j][$key]>$a[$j+1][$key]){
                                          $t = $a[$j];
                                          $a[$j] = $a[$j+1];
                                          $a[$j+1] = $t;
                                    //        self::myswap($a[$j][$key],$a[$j+1][$key]);

                                        }
                                    }
                              }
                        } else {
                            $w=count($a)-1;
                            for ($i=0;$i<$w;$i++){
                                     $c=$w-$i;
                                       for ($j=0;$j<$c;$j++){
                                               if ($a[$j][$key]<$a[$j+1][$key]){
                                                 $t = $a[$j];
                                                 $a[$j] = $a[$j+1];
                                                 $a[$j+1] = $t;
                                            //       self::myswap($a[$j][$key],$a[$j+1][$key]);
                                                   }
                                           }
                                      }
                        }
             return $a;
            }




public function __destruct(){
  if ($this->mysqli) $this->mysqli->close();
}


}

 ?>
