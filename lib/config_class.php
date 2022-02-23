<?php
/**
 *
 */
class Config
{

  var $sitename ="firefind.v1";
  var $address="http://firefind.v1/";
  var $dir="/srv/www/htdocs/";
  // var $salt ="cat88";
  var $host = "localhost";
  var $db = "firstbase";

  var $db_prefix = "XXX_";
  var $table_name='search';
  var $field=array('text');


  var $user = "root";
  var $password = "3455";
  // var $admname="ramanzes";
  // var $admemail="sahroman@inbox.ru";    //admin@itdid.ru
  // var $imagedir="image/";
  var $logdir="/srv/www/htdocs/log/";
  var $errlogdir="/srv/www/htdocs/log/err/";
  var $dir_tmpl='/srv/www/htdocs/tmpl/';

//  var $count_blog =3; //количество статей которые выводятся на странице

//  var $dir_text="/srv/www/htdocs/lib/text/";

  // var $min_login = 2;
  // var $max_login = 255;
  // var $min_pass = 3;
  // var $max_pass = 255;
  // var $firstdate =1642575211; //дата редактирования поля даты пользователя, не может быть меньше этой.
  //
  // var $min_title = 10;  //допустимый размер заголовка в статьях таблицы article
  // var $max_title = 255;
  //
  // var $min_comment = 3;  //допустимый размер комментариев пользователей к статьям
  // var $max_comment = 777;
  //
  // var $min_field = 2;  //допустимый размер имён полей в базе данных
  // var $max_field = 108; //
  //
  // var $author_comm ='Гость'; //автор комментария по умолчанию у неавторизованного пользователя
  //
  // var $valid_field = "/^[a-z]+_?[a-z]+$/i"; //регулярное выражение которому должны соответствтовать все поля в базе
  //
  //
  // var $subject = "Письмо с сайта itdid.ru";  //тема емейл сообщения по умолчанию. можно менять.
  // var $mail_from1 = "mail@itdid.ru"; //от кого
  // var $mail_from2 = "mail@itdid.ru"; // кому отвечать
  //

  //переменные для поиска
  var $widthfrags = 255; //средняя ширина фрагментов при выдаче в поиске плюс $beginfrags целых слова влево, и вправо $endfrags до конца текущего слова.
  var $beginfrags=3; //ещё целых слов влево при первом вхождении слова в фрагмент
  var $endfrags=1; // целых слов вправо после завершения фрагмента.

  // var $mail_headers;
  // var $subject_code;

// function __construct()
//  {
//    // code...
//
// }



}

 ?>
