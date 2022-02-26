<?php
/**
 *
 */
class Config
{

  var $sitename ="localhost";
  var $address="http://localhost/";
  var $dir="/var/www/html/";
  var $dir_tmpl='/var/www/html/tmpl/';

  var $host = "localhost"; //хост сервера mysqli
  var $db = "firstbase";  //название базы данных msqli

  var $db_prefix = "XXX_";  //префикс имени таблицы..
  var $table_name='search'; //имя таблицы без префикса
  var $field=array('text'); //поле таблицы в которой происходит поиск

  var $user = "root"; //пользователь mysqli
  var $password = ""; //пароль пользователя mysqli


  //переменные для поиска
  var $widthfrags = 255; //средняя ширина фрагментов при выдаче в поиске плюс $beginfrags целых слова влево, и вправо $endfrags до конца текущего слова.
  var $beginfrags=3; //ещё целых слов влево при первом вхождении слова в фрагмент
  var $endfrags=1; // целых слов вправо после завершения фрагмента.


}

 ?>
