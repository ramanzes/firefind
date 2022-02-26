<?php
/**
 *
 */
class Config
{

  var $sitename ="newtest.local/firefind";
  var $address="http://newtest.local/firefind/";
  var $dir="/var/www/html/firefind/";
  var $dir_tmpl='/var/www/html/firefind/tmpl/';

  var $host = "newtest.local"; //хост сервера mysqli
  var $db = "firstbase";  //название базы данных msqli

  var $db_prefix = "XXX_";  //префикс имени таблицы..
  var $table_name='search'; //имя таблицы без префикса
  var $field=array('text'); //поле таблицы в которой происходит поиск

  var $user = "root"; //пользователь mysqli
  var $password = "3455"; //пароль пользователя mysqli


  //переменные для поиска
  var $widthfrags = 255; //средняя ширина фрагментов при выдаче в поиске плюс $beginfrags целых слова влево, и вправо $endfrags до конца текущего слова.
  var $beginfrags=3; //ещё целых слов влево при первом вхождении слова в фрагмент
  var $endfrags=1; // целых слов вправо после завершения фрагмента.


}

 ?>
