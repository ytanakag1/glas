<?php
header("Content-type: text/plain; charset=UTF-8");
//require_once('../../../wp-blog-header.php');
 
require_once "include/db_connect.php";

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
   && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
  // Ajaxリクエストの場合のみ処理する
   $prefs =  $_SESSION['prefs'];


  if (isset($_POST['request']))  {
   //    areaList($prefs[$_POST['request']][0],$_POST['trb']);
  $trbs=array('water'=>'jire_wt','glas'=>'jire_gr','key'=>'jire_ky'); 
    $t=$trbs[$_POST['trb']];
    $p=$prefs[$_POST['request']][0];
     
   $sql = "SELECT  area_name,slug 
        FROM `area`
        where pref = '$p' AND $t = 1
    "  ; //, Subquery returns more than 1 row;  //'tokyo' // SELECT post_title,post_name FROM wp_posts  // WHERE post_type = 'page' AND  post_parent= 
    // (SELECT ID  FROM wp_posts     //   WHERE post_type = 'page' AND post_name = ? AND post_parent=?    //   );
   
  $sth = $dbh->prepare($sql);
  // $sth->bindValue(1, $p , PDO::PARAM_STR);
  // $sth->bindValue(2, $t , PDO::PARAM_INT);
   //$sth->bindParam(2, '999' , PDO::PARAM_INT);
    $sth->execute();
  
    foreach ($sth as $value) {
      //var_dump($value);
      echo '<button type="submit" name="shiku" id="'.$value['slug']
      .'" data-area="'.$value['slug'].'" value="'.$value['area_name']
      .'-'.$value['slug'].'" >'.$value['area_name'].'</button>';
    }
    
  }
  else
  {
      echo 'The parameter of "request" is not found.';
  }
}
?>