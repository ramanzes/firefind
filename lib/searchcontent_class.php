<?php
require_once "firefind_class.php";


class SearchContent {
    private $words;
//    private $firewind;
    public $morphyus_ru;
    public $morphyus_en;

    private $firefind;
    protected $config;
    protected $data;

    protected $table_name;
    protected $field;


    public function __construct(){
      $this->config = new Config ();
    //  require_once $this->config->dir."/firewind/firewind.php";
      require_once $this->config->dir."/firewind/morphyus.php";

      $this->table_name=$this->config->table_name;
      $this->field=$this->config->field;

  //    if($_SESSION['morphyus']=='checked')

    //  $this->firewind = new firewind();
			$this->morphyus_ru = new morphyus();
      $this->morphyus_en = new morphyus('en_EN');

      $this->firefind = new fireFind($this->table_name);


       $this->data = $this->secureData(array_merge($_POST, $_GET)); //отчистка запросов от потенциальных sql-инъекций

       $this->words = $this->data['words'];
       require_once $this->config->dir.'/firewind/firewind.php';  //подключаем модуль morphyus
    }


     public function getContent(){
         $sr['debug']=$this->getDebug();
    //     exit;
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
       $sr['text']=$data[intval($this->data['id'])][$this->field[0]];

       for($i=0;$i<count($data);$i++){
         $new_sr['id']=$i;

         $text.=$this->getReplaceTemplate($new_sr,'string');
       }

       $sr['string']=$text;

       return $this->getReplaceTemplate($sr,'top');
     }

// public function MorphyfindWord($word){
//
// return $this->firewind->morphyus->findWord( $word );
//
// }

     private function getMorphyArray(){
       $reg_en="/[a-z]+/i";
       $reg_ru="/[а-я]+/i";

       $morphywords=$this->morphyus_ru->get_words( $this->words );

//пропарсить массив если есть англ. по регулярному выражению сложить в другой массив

    //   $morphywords1=$this->morphyus->lemmatize( $morphywords );
    
       if (preg_match($reg_ru, $this->words))
       $morphywords_ru=$this->morphyus_ru->getAllFormsWithGramInfo( $morphywords );
       else if (preg_match($reg_en, $this->words))
       $morphywords_en=$this->morphyus_en->getAllFormsWithGramInfo( $morphywords );

   //     $morphywords2=$this->firewind->morphyus->findWord( $morphywords );
   // print_r($morphywords_ru);
   // echo "<br>";
   // print_r($morphywords_en);
   // exit;
      if(count($morphywords_ru)>0)
       foreach ($morphywords_ru as $key => $value)
         for ($i=0;$i<count($value);$i++)
           if ($key!=='ДЛЯ')   //для этого слова какой то неадекват происходит то что заметил не нужны нам слова ДЛИТЬ например
           for($j=0;$j<count($value[$i]['forms']);$j++) {
               if(!in_array($value[$i]['forms'][$j],$morphyarray))
               $morphyarray[]=$value[$i]['forms'][$j];
           }
           else $morphyarray[]='ДЛЯ';


//если есть другой массив для английских букв, то добавляем сюда же
      if(count($morphywords_en)>0)
       foreach ($morphywords_en as $key => $value)
         for ($i=0;$i<count($value);$i++)
           for($j=0;$j<count($value[$i]['forms']);$j++) {
               if(!in_array($value[$i]['forms'][$j],$morphyarray))
               $morphyarray[]=$value[$i]['forms'][$j];
         }

   //        $morphyarray=array_unique($morphyarray);// Отчистить массив от одинаковых элементов
       return $morphyarray;
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


    if (!$result) {
      $new_sr['words']=$this->words;
      return $this->getReplaceTemplate($new_sr,'search_notfound');
    }
// сначала собираем %allfrags% для слайдера, через searchallfrags.tpl уитывая что 0-й будет активным в слайдере.
//  var_dump($result);exit;
for($i=0;$i<count($result);$i++){ //если несколько статей имеет фрагменты
    for($j=0;$j<count($result[$i]['fragments']);$j++){
      if($j==0) $newsr['active']='active';    //первый элемент в каруселе делаем активным через шаблон
      else $newsr['active']='';
      //activ будет актуален для двух шаблонов searchallfrags и sliderindicators
      $newsr['frags']=$this->setTeg($result[$i]['fragments'][$j][0],$this->words); // получаем фрагмент с выделенными словами поиска
      //для
      //id и $j здесь нужен только sliderindicators
      $newsr['id']=$result[$i]['id'];
      $newsr['j']="$j";
      $textallfrags .=$this->getReplaceTemplate($newsr,'searchallfrags');
      $textindicators .=$this->getReplaceTemplate($newsr,'sliderindicators');
//          сохраняем этот фрагмент через шаблон searchallfrags
    }

    $sr['allfrags']=$textallfrags;  //<div class="carousel-item"> под каждый фрагмент
    $sr['indicators']=$textindicators;
//теперь соберём %mainfrags% через шаблон mainfrags.tpl
      $textallfrags='';
      $textindicators='';
      $sr['id']=$result[$i]['id'];  // фактически это id="carouselExampleControls%id%" в коде html
//       $sr['frags']=$this->setTeg($result[$i]['fragments'][0][0],$this->words);
// //          получаем первый элемент карусели
      $newsr['mainfrags']=$this->getReplaceTemplate($sr,'mainfrags');
//теперь подставляем $newsr['mainfrags'] с досборкой шаблона search_item.tpl
      $newsr['link']= $this->config->address."?view=&amp;id=".$result[$i]['id'];
      $newsr['title']="Вывод поля из базы по 'id'=".$result[$i]['id'];
      $text .= $this->getReplaceTemplate($newsr,'search_item');
    }
//теперь завершающая сборка вывод в шаблоне search_result.tpl

    $new_sr['words']=$this->words;
    $new_sr['search_items']=$text;
    return $this->getReplaceTemplate($new_sr,'search_result');

    }


    protected function getDebug(){
  //    $str="Результаты findWord(): ".$this->MorphyfindWord($this->words);
       //  $morphywords=$this->morphyus->get_words( $this->words );
       // $str=$this->morphyus->getAllFormsWithGramInfo($morphywords);
       $str=$this->getMorphyArray();
    //   $str=$this->morphyus->findWord($morphywords);
// print_r($str);
       $str['str']=implode(",",$str);
//       echo $str;
    return $this->getReplaceTemplate($str,'debug');
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
