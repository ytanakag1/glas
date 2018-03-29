<?php 
session_start();
//////検索フォーム表示関数//////////
//function taiou_search_form($tr){  
require_once "include/db_connect.php";
     $prefs =  $_SESSION['prefs'];
    global $pdir9;  global $prefs;  
 
  // 地方配列
  $region = array('東北地方','関東地方','信越・北陸地方','東海地方','関西地方','中国地方','四国地方','九州・沖縄地方');
  $trb= explode('/',$_POST['baitai']);
  $tr=$trb[1]; 
  $baitai_ID = $_SESSION['baitai_ID'][$tr];
    // { 'water' => int(999) 'glas' => int(1012) 'key' => int(1017) }  
    
    $sql="
    SELECT count(pt.ID)kiji_count,pt. post_parent,pref.post_title
    FROM `wp_posts` as pt, 
     (select wp_posts .ID,wp_posts .post_title ,wp_posts.post_parent from wp_posts ) as pref
      WHERE pt.post_parent in(
        SELECT ID 
        FROM wp_posts 
        WHERE   post_parent =$baitai_ID AND post_status ='publish' 
      )
    AND post_type = 'page' 
    and post_status ='publish'
    and pt.post_parent = pref.ID
    group by pt.post_parent";
   $sth = $dbh->prepare($sql);
   $sth->execute();  

    $kiji_count = array();
    foreach($sth as $value){
    // 'kiji_count' => int(53) 'post_parent' => int(601) 'post_title' => string(24) "東京都対応エリア" 
      $pref_kanji=str_replace('対応エリア','', $value['post_title']);
      $kiji_count += array( $pref_kanji => $value['kiji_count']);
  //{ '東京都' => int(53) '千葉県' => int(8) '福島県' => int(11) '神奈川県' => int(57)     
    }
//var_dump($kiji_count); exit();    
?>
<section class='taio_search_wrap'>
 <h3>対応地域検索</h3>
  
  <div id="taio_search" class="article">
      <ul id="region">
         
<?php  // 都道府県をラジオボタンで
     $k=0; $i=0;
      foreach($prefs as $pref_kanji=>$val){
       if($pref_kanji=='全ての都道府県')continue;
        if($k==1 || $k== 7 || $k==15 || $k==20 || $k==24 || $k==30 || $k==35 || $k==39 ){ 
          //地域が変わる
            if($k!=1) echo "</ul>\n</li>"; //初回でなければ閉じタグ必要
            
              echo "<li id='re", $i+1 ,"'><h4 onclick='re",$i+1,"()'>", $region[$i] ,'</h4>';
              echo "<ul>";
            ++$i; 
        } 
        // 同じ地域の県  
          if ( isset($kiji_count[$pref_kanji]) ){
            echo "<li id='pr{$k}'>
              <a href='/", $tr ,'/area/' , $prefs[$pref_kanji][0]  ,"'>
                  $pref_kanji ( $kiji_count[$pref_kanji] )
              </a></li>  \n";
            
          }else{
            echo "<li id='pr{$k}'> $pref_kanji ( 0 )</li>  \n";
          } 
        ++$k;
      }

?>
   </ul>
  </div> <!--"taio_search"-->
</section>

<?php // } 
