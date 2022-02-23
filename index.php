<?php
//    mb_internal_encoding("UTF-8");

// error_reporting(E_USER_ERROR,E_COMPILE_WARNING,E_COMPILE_ERROR,E_CORE_WARNING,E_CORE_ERROR,E_PARSE,E_ERROR,E_WARNING);



error_reporting(E_ERROR);
ini_set('display_errors', 'On');


  //  require_once 'lib/datebase_class.php';

    // require_once 'lib/frontpagecontent_class.php';
    // require_once 'lib/sectioncontent_class.php';
    // require_once 'lib/articlecontent_class.php';
    // require_once 'lib/regcontent_class.php';
    // require_once 'lib/lostpasscontent_class.php';
    // require_once 'lib/newpasscontent_class.php';
    // require_once 'lib/messagecontent_class.php';
    require_once 'lib/searchcontent_class.php';

    // require_once 'lib/mail_class.php';
//    require_once 'lib/notfoundcontent_class.php';
    // require_once 'lib/oproscontent_class.php';
    // require_once 'lib/changepasscontent_class.php';
    if(!isset($_SESSION)) session_start();



  //   // phpinfo();
  //
  //
  //   $user= User::getObject();
  // //  print_r($user->getAll());
  //   echo"<hr>";
  //   print_r($user->getField(['id','passwd'], 'name', 'admin'));    //!!!
  //    exit;





//    $db = new DateBase();
    $view = $_GET['view'];      //у нас обработчик через гет параметр открывает нужные страницы.



// echo "otpravim email";
// $mail=new Mail();
// $to='sahroman@inbox.ru';
// $subject='new time';
// $text="proverka svyazy s cat /var/mail/db \n\n новая строка через строку";
// echo $mail->mailto($to,$subject,$text);
//
// exit;

    switch ($view) {    // здесь получаем нужную страницу
        case "":
          //   if ($_SESSION['lastform']=='reg'){
          //     $_SESSION['login']='';
          //     $_SESSION['page_message']=array('');
          //   }
          //   $_SESSION['lastform']='auth';
          //   $content = new FrontPageContent ($db);
          //   break;
          // case "section":
          //   $content = new SectionContent ($db);
          //   break;
          // case "article":
          //   $content = new ArticleContent ($db);
          //   break;
          // case "reg":
          // if ($_SESSION['lastform']=='auth'){
          //   $_SESSION['login']='';
          //   $_SESSION['nik']='';
          //   $_SESSION['mail']='';
          //   $_SESSION['page_message']=array('');
          // }
          //     $_SESSION['lastform']='reg';
          //     $content = new RegContent ($db);
          //     break;
          // case "message":
          //       $_SESSION['lastform']='auth';
          //       $content = new MessageContent ($db);
          //       break;
          // case "lostpass":
          // $_SESSION['lastform']='reg';
          // $content = new lostpassContent ($db);
          // break;
          // case "newpass":
          // $_SESSION['lastform']='auth';
          // $_SESSION['lost']=$_GET['lost'];
          // $content = new newpassContent ($db);
          // break;
          case "search":
          if ($_GET['withsection']=='yes')
          $_SESSION['withsection']='checked';
          else unset($_SESSION['withsection']);
          if ($_GET['morphyus']=='yes')
          $_SESSION['morphyus']='checked';
          else unset($_SESSION['morphyus']);
  //        $_SESSION['lost']=$_GET['lost'];
          $content = new searchContent ();
          break;

//           case "logout":
//         //    $_SESSION['login']='';
//             $_SESSION['password']='';
//             $_SESSION['lastform']='auth';
//             $_SESSION['done']=false;
//             $_SESSION['page_message']=array('');
// //            $content = new FrontPageContent ($db);
//             //    $content = new LogoutContent ($db);
    // case "notfound":
    //       $content = new notFoundContent ($db);
    //       break;
    // case "opros":
    //
    //             $content = new oprosContent ();
    //             break;
    // case "changepass":
    //             $content = new changepassContent ();
    //             break;
       default:          if ($_GET['withsection']=='yes')
                 $_SESSION['withsection']='checked';
                 else unset($_SESSION['withsection']);
                 if ($_GET['morphyus']=='yes')
                 $_SESSION['morphyus']='checked';
                 else unset($_SESSION['morphyus']);
         //        $_SESSION['lost']=$_GET['lost'];
                 $content = new searchContent ();
                 break;
        }


        echo $content->getContent();

?>
