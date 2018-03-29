<?php

//水の市町村を固定ページに県の親をつけて引っ越しする
    require_once('../../../../wp-config.php');
    try {
      $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',DB_USER,DB_PASSWORD,
      array(PDO::ATTR_EMULATE_PREPARES => false));
      } catch (PDOException $e) {
       exit('データベース接続失敗。'.$e->getMessage());
    }
    
 function water_shiku_c_to_p(){
   global $dbh;
    $wtoya=array(607=>'hokkaidou',609=>'aomori',611=>'iwate',613=>'miyagi',615=>'akita',617=>'yamagata',619=>'hukusima',601=>'tokyo',605=>'chiba',621=>'kanagawa',623=>'saitama',625=>'ibaragi',627=>'tochigi',629=>'gunma',631=>'yamanashi',633=>'niigata',635=>'nagano',637=>'toyama',639=>'ishikawa',641=>'hukui',643=>'aichi',645=>'gihu',647=>'shizuoka',649=>'mie',651=>'oosaka',653=>'hyougo',655=>'kyoto',657=>'shiga',659=>'nara',661=>'wakayama',663=>'okayama',665=>'hiroshima',667=>'yamaguchi',669=>'tokushima',671=>'kagawa',673=>'ehime',675=>'kouchi',677=>'hukuoka',679=>'saga',681=>'nagasaki',683=>'kumamoto',685=>'ooita',687=>'miyazaki',689=>'kagoshima',691=>'okinawa',881=>'tottori',883=>'shimane');
   $oya=array(693=>'hokkaidou',697=>'aomori',699=>'iwate',701=>'miyagi',703=>'akita',705=>'yamagata',707=>'hukusima',709=>'tokyo',711=>'kanagawa',713=>'saitama',715=>'chiba',717=>'ibaragi',719=>'tochigi',721=>'gunma',723=>'yamanashi',725=>'niigata',727=>'nagano',729=>'toyama',731=>'ishikawa',733=>'hukui',735=>'aichi',737=>'gihu',739=>'shizuoka',741=>'mie',743=>'oosaka',745=>'hyougo',747=>'kyoto',749=>'shiga',751=>'nara',753=>'wakayama',755=>'okayama',757=>'hiroshima',759=>'yamaguchi',761=>'tokushima',763=>'kagawa',765=>'ehime',767=>'kouchi',771=>'hukuoka',773=>'saga',775=>'nagasaki',777=>'kumamoto',779=>'ooita',781=>'miyazaki',783=>'kagoshima',785=>'okinawa',885=>'tottori',887=>'shimane'); 
  
    foreach($oya as $k=>$v){
     $sql ="UPDATE wp_posts as pt ,
        (
        SELECT ID, pt.post_title, pt.post_name,  pt.post_type
        FROM `wp_posts` as pt
          RIGHT JOIN(
            SELECT distinct post_title,post_id  FROM wp_posts 
             right join (
               SELECT distinct post_id 
               FROM `wp_postmeta`
               where
               wp_postmeta.meta_value = ?
             ) as pm 
            on pm.post_id = wp_posts.ID
          ) as ppm
          ON ID = ppm.post_id
        ) as me
         
        SET pt.post_type='page', pt.post_parent=? 
        WHERE pt.post_status='publish' 
        and pt.post_type='glas_shiku' AND pt.ID = me.ID;";  // 
    
    
       $sth = $dbh->prepare($sql);
       $sth->bindParam(1, $v , PDO::PARAM_STR);
       $sth->bindParam(2, $k , PDO::PARAM_INT);
        $sth->execute();
    }
 }  
 
 water_shiku_c_to_p();