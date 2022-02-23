<?php
require_once "firefind_class.php";


class SearchContent {
    private $words;
    private $firewind;
    private $firefind;
    protected $config;
    protected $data;

    protected $table_name;
    protected $field;


    public function __construct(){
      $this->config = new Config ();
      require_once $this->config->dir."/firewind/firewind.php";

      $this->table_name=$this->config->table_name;
      $this->field=$this->config->field;

      if($_SESSION['morphyus']=='checked')

      $this->firewind = new firewind();
      $this->firefind = new fireFind($this->table_name);


       $this->data = $this->secureData(array_merge($_POST, $_GET)); //отчистка запросов от потенциальных sql-инъекций

       $this->words = $this->data['words'];
       require_once $this->config->dir.'/firewind/firewind.php';  //подключаем модуль morphyus
    }


     public function getContent(){

          if(!isset($_SESSION)) session_start();

         if (isset($_SESSION['withsection']))  //чекбокс расширяющий поиск и по разделам заводится в сессию в index.php при отмеченном чекбоксе. подставляем в main.tml
         $sr['withsection']='checked';
         if (isset($_SESSION['morphyus']))  //чекбокс подлключающий firewind
         $sr['morphyus']='checked';

         $sr['title']=$this->getTitle();
         // $sr['meta_desc']=$this->getDescription();
         // $sr['meta_key']=$this->getKeyWords();
         //
         $sr['top']=$this->getTop();
         $sr['middle']=$this->getMiddle();

         if (isset($_SESSION['login']))  $sr['login']=$_SESSION['login'];
         else { $sr['login']='';}

         return $this->getReplaceTemplate($sr,'main');
     }


     protected function getTop(){       // способ иметь имя метода пустышки в родителе, но если потребуется мы можем его переализовать в дочернем классе
       $data=$this->firefind->getAll($this->table_name);
      // var_dump($this->data); echo('<hr>');
      // echo(intval($this->data['id']));echo('<hr>');
      // var_dump($data);
       $sr['alltext']=count($data);
       $sr['db']="<b>".$this->config->db."</b>";
       $sr['table']="<b>".$this->table_name."</b>";
       $sr['field']='<b>`'.$this->field[0].'`</b>';
       $sr['id']="<b>".$this->data['id']."</b>";
       $sr['text']=$data[intval($this->data['id'])-1][$this->field[0]];

       for($i=0;$i<count($data);$i++){
         $new_sr['id']=$i;

         $text.=$this->getReplaceTemplate($new_sr,'string');
       }

       $sr['string']=$text;

       return $this->getReplaceTemplate($sr,'top');
     }


 private function secureData($data){
    foreach ($data as $key => $value) {
        if (is_array($value)) $this->secureData($value);
        else $data[$key] = htmlspecialchars($value);

    }
//  echo "secure=";    print_r($data);
    return $data;

 }

    protected function getTitle(){
       return "Результаты поиска: ".$this->words;

    }
    protected function getDescription(){
        return  $this->words;
    }
    protected function getKeyWords(){
        return mb_strtoupper($this->words);
      }


      public function setTeg($str,$words) //установить теги выделения на слова в строке
      {
      $words = mb_strtoupper($words); //привести в верхний регистр
      $words = trim($words);// обрезать пробелы спереди и сзади
      $words = quotemeta($words); //заковычить специальные символы для работы с sql
      if ($words=='') return false;
      $arraywords=explode(' ',$words); //разбить строку слов на массив слов
      $words=$arraywords;
      for ($i=0;$i<count($words);$i++){
        $str=str_replace($words[$i],"<span class='tofind'><u>".$words[$i].'</u></span>',$str);
      }

        return $str;
      }


          //создали общую форму для вывода фрагментов в каруселе со своим id для контроля. теперь формируем то что будет внутри карусели начиная с 0-го элемента потому, что он имеет дополинтельный класс css,js activ/ он поставляется через слово frags, остальные элементы карусели через слово allfrags, из обёртки шаблона searchallfrags внутри которого снова frags уже конечный элемент строка для тегов <p></p>




    protected function getMiddle(){

      if ($this->data['morphyus']=='yes'){
        $morphyarray=$this->getMorphyArray();
        $this->words=implode(" ",$morphyarray);
    }
      else  $morphyarray='';  //обязательно делать это в случае отсутсвия включенного морфиуса

          $result = $this->firefind->search($this->table_name,$this->words,$morphyarray,$this->field);
  //   var_dump($result);
    // var_dump($result[0]['fragments']);

    if (!$result) {
      $new_sr['words']=$this->words;
      return $this->getReplaceTemplate($new_sr,'search_notfound');
    }
// сначала собираем %allfrags% для слайдера, через searchallfrags.tpl без первого фрагмента(0) который будет активным в слайдере.
//  var_dump($result);exit;
for($i=0;$i<count($result);$i++){ //если несколько статей имеет фрагменты
    for($j=1;$j<count($result[$i]['fragments']);$j++){
      $newsr['frags']=$this->setTeg($result[$i]['fragments'][$j][0],$this->words); // получаем фрагмент с выделенными словами поиска
      $textcarousel .=$this->getReplaceTemplate($newsr,'searchallfrags');
//          сохраняем этот фрагмент через шаблон searchallfrags
    }

    $sr['allfrags']=$textcarousel;  //<div class="carousel-item"> под каждый фрагмент
//      echo 'sr[allfrags]='.$sr['allfrags']."<hr>"; exit;
//теперь соберём %mainfrags% через шаблон mainfrags.tpl
      $sr['id']=$var.$result[$i]['id'];  // должно меняться для каждой статьи или раздела поэтому создаём на основе его ссылки/ это для манипуляторов карусели чтобы каждая карусель отвечала за свои фрагменты, кнопки вперёд назад. фактически это id="carouselExampleControls%id%" в коде html
      $sr['frags']=$this->setTeg($result[$i]['fragments'][0][0],$this->words);
//          получаем первый элемент карусели
      $newsr['mainfrags']=$this->getReplaceTemplate($sr,'mainfrags');

//теперь подставляем $newsr['mainfrags'] с досборкой шаблона search_item.tpl
      $newsr['link']= $this->config->address."?view=$var&amp;id=".$result[$i]['id'];
      $newsr['title']=$result[$i]['title'];
      $text .= $this->getReplaceTemplate($newsr,'search_item');
    }
//теперь завершающая сборка вывод в шаблоне search_result.tpl

    $new_sr['words']=$this->words;
    $new_sr['search_items']=$text;
    return $this->getReplaceTemplate($new_sr,'search_result');

    }

    protected function getTemplate($name){
  //     echo $this->config->dir_tmpl.$name.".tpl";
        $text = file_get_contents($this->config->dir_tmpl.$name.".tpl");
        return str_replace("%address%", $this->config->address, $text);
    }


    protected function getReplaceTemplate($sr,$template){
        return $this->getReplaceContent($sr,$this->getTemplate($template));
    }

    protected function getReplaceContent($sr, $content){

        $search = array ();
        $replace = array ();
        $i=0;
            //   echo "<hr>";    echo "<hr>";
            //  var_dump($sr);
            //  echo "<hr>";    echo "<hr>";
        foreach ($sr as $key => $value){
            $search[$i] = "%$key%";
            $replace[$i] = $value;
            $i++;
        //     echo "<hr>";    echo '$search[$i] =';    echo "$search[$i]";    echo "<hr>";
        //     echo "<hr>";    echo '$replace[$i] =';    echo "$replace[$i]";    echo "<hr>";
         }
        return str_replace($search, $replace, $content);
    }

    protected function redirect($link='')
    {
      header("Location: $link");
      exit;
    }



}

?>
