<?php
header("Content-type: text/plain; charset=UTF-8");
//require_once('../../../wp-blog-header.php');
 
require_once "include/db_connect.php";

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
   && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
  // Ajaxリクエストの場合のみ処理する
   $prefs = $_SESSION['prefs'];
  // 都道府県がPOSTされている場合
 //var_dump($_POST['pref'],$_POST['shiku'],$_POST['baitai']);  
  if(!empty($_POST['pref']) && !empty($_POST['shiku']) && !empty($_POST['baitai']) ){ //漢字の東京
    $pref_alpha = $prefs[$_POST['pref']][0];  // tokyo
    $shiku_kanji = $_POST['shiku'];
    
    $sql = "
      SELECT count(wp_posts.ID)count,wp_posts.ID,wp_posts.post_title,wp_posts.post_name
      FROM wp_posts
      
      , ( SELECT ID 
        FROM wp_posts 
        WHERE post_name='$pref_alpha' AND post_parent =999 AND post_status ='publish' ) as pst 
      
      WHERE post_parent = pst.ID
      AND post_type = 'page' AND post_status = 'publish' 
      AND post_title = '$shiku_kanji'
      ";
    
    $sth = $dbh->prepare($sql);
      $sth->execute();
      foreach($sth  as $v ) {
        if($v['count']){ // 公開記事があれば市区名まで
          $path_area=$_POST['baitai']."$pref_alpha/{$v['post_name']}/#taiochiiki";
          echo $path_area;
        } else{
          $path_area=$_POST['baitai']."$pref_alpha/";
          //header("Location: $path"); 
          echo $path_area;
        }
      } //End foreach
   
  
  }else{
      echo '/';
  }
}
?>