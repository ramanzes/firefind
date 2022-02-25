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

  var $host = "newtest.local";
  var $db = "firstbase";

  var $db_prefix = "XXX_";
  var $table_name='search';
  var $field=array('text');


  var $user = "root";
  var $password = "3455";

  //переменные для поиска
  var $widthfrags = 255; //средняя ширина фрагментов при выдаче в поиске плюс $beginfrags целых слова влево, и вправо $endfrags до конца текущего слова.
  var $beginfrags=3; //ещё целых слов влево при первом вхождении слова в фрагмент
  var $endfrags=1; // целых слов вправо после завершения фрагмента.



}

 ?>
