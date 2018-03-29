<?php
session_start();
//////検索フォーム表示関数//////////
//function zip_search_form($trb){  
 
 require_once "include/db_connect.php";
     
     
 global $juchu;  global $prefs;   //global $wpdb;
 global $pdir9; global $baitai_ID;
 
  $prefs =  $_SESSION['prefs'];
  $trb= explode('/',$_POST['baitai']);
  $tr=$trb[1]; 
  $baitai_ID = $_SESSION['baitai_ID'][$tr];

 
  // 地方配列
  $region = array('北海道・東北地方','関東地方','信越・北陸地方','東海地方','関西地方','中国地方','四国地方','九州・沖縄地方');


  $sql ="
    SELECT count(pt.ID)kiji_count,pt. post_parent,pref.post_title
    FROM wp_posts as pt, 
     (select wp_posts .ID,wp_posts .post_title ,wp_posts.post_parent from wp_posts ) as pref
      WHERE pt.post_parent in(
        SELECT ID 
        FROM wp_posts 
        WHERE   post_parent = $baitai_ID AND post_status ='publish' 
      )
    AND post_type = 'page' 
    and post_status ='publish'
    and pt.post_parent = pref.ID
    group by pt.post_parent
    " ;
  
 
  //$sth = $wpdb->get_results($prepared_sql); 
    
   $sth = $dbh->prepare($sql);
   $sth->execute();  

    $kiji_count = array();
    foreach($sth as $value){
    // 'kiji_count' => int(53) 'post_parent' => int(601) 'post_title' => string(24) "東京都対応エリア" 
      $pref_kanji=str_replace('対応エリア','', $value['post_title']);
      $kiji_count += array( $pref_kanji => $value['kiji_count']);
    }
  
 var_dump( $pref_kanji ); exit();  
  //{ '東京都' => int(53) '千葉県' => int(8) '福島県' => int(11) '神奈川県' => int(57)     
  // 都道府県をリンクで
     $k=0; $i=0;
      foreach($prefs as $pref_kanji=>$val){
        
        if($k==1 || $k== 8 || $k==16 || $k==21 || $k==25 || $k==31 || $k==36 || $k==40 ){ 
          $region_chang = true;  //地方が変わる
        
            if($k!=1) echo "</dd></dl>"; //初回でなければ閉じタグ必要
            echo "<dl id='a", $i+1 ,"'> <dt>$region[$i]</dt><dd>";  //北海道東北
            ++$i; 
        }  
           
        $elemID= "id='pr$k'"; 
        if($pref_kanji!='全ての都道府県'){
          $kc = empty($kiji_count[$pref_kanji])? 0 : $kiji_count[$pref_kanji];
          echo "<li><span>
          <a href='/", $tr ,'/area/' , $prefs[$pref_kanji][0] ,"' $elemID > $pref_kanji ( $kc ) </a></span></li>\n";
        }
          
        ++$k;
      }
  echo '</dd></dl>';
  // }  end function
