<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="text/html; charset=utf-8">
  <meta name="keywords" content="%meta_key%"/>
  <meta name="description" content="%meta_desc%"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="%address%css/main.css" type="text/css"/>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
  <div id="main_content">
    <h2>Вывод таблицы из базы данных, по которой осуществляется поиск:</h2><hr>
    %top%
        <form name="search" action="%address%" method="get">
          <table>
<tr>
              <td>
                <input type="text" name="words" placeholder="введите поисковой запрос"/>
              </td>
            </tr>
            <!-- <tr><td>
            <input type="checkbox" id="section" name="withsection" value="yes" %withsection%>
            <label for="section">+ по разделам</label><br></tr></td>
            <tr> -->
                <td colspan="2" align="center">
                    <input type="hidden" name="view" value="search"/>
                  <input class="btn btn-github" type="submit" name="search" value="искать на сайте"/>
                </td>
              </tr>
<tr>
<!-- <input type="checkbox" id="article" name="search_area1" value="article" checked>
<label for="article">В статьях</label><br> -->
<td><input type="checkbox" id="morphyus" name="morphyus" value="yes" %morphyus%>
<label for="morphyus">Включить phpmorphy</label></td>
</tr>
              <tr><td  colspan="2"><hr></td></tr>
          </table>
        </form>

  <div class="clear"></div>
  <hr/>
  <div id="footer">
  <p>Все права защищены &copy; 2022</p>
  </div>
</body>
</html>
