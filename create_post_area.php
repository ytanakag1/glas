<?php   //wp-content/plugins/wp-construction-ex2/create_post_area.php
// 投稿のない市区を追加、有る市区はテンプレ設定
  try {    
  header("Content-type: text/plain; charset=UTF-8");
   require_once "include/db_connect.php";
  } catch (PDOException $e) {
   exit('データベース接続失敗。'.$e->getMessage());
  }

 //allshiku を回す 事例有り無しは関係ない //,jire_wt,jire_gr,jire_ky やり直しadminer.sql
  $sql_area = "SELECT area.ID, area.pref,apref.wamei as ken ,area_name,slug
              FROM area 
              LEFT JOIN apref
              ON apref.pref = area.pref
              " ; 
       //       where area.ID >1897;
  // 59	hokkaidou	函館市	hakodateshi 1890件
     $stha = $dbh->prepare($sql_area);
     $stha->execute();        


  $i=0; //insert counter
    foreach($stha as $k=>$v){
//wp_postからarea_name(函館市)で検索 	札幌市中央区
      $sql_post = "SELECT count(ID) as n ,GROUP_CONCAT(ID) as IDs,post_title,post_name,
       GROUP_CONCAT(char_length(post_content)) as kiji_length,GROUP_CONCAT(post_parent) as ps
       FROM wp_posts 
       WHERE post_title = '{$v['area_name']}' AND post_name='{$v['slug']}';"; 
       // 2	(1031,1314)IDs	あきる野市	akiruno	(0,598)kiji_length	(709,601)ps ... 0文字が709 keyは801
       // 0	NULL	NULL	NULL	NULL	NULL\
 echo $sql_post ,"\n"; 
        $sth_p = $dbh->prepare($sql_post);
          //$sth->bindParam(1, $trbs[2] , PDO::PARAM_STR);
        $sth_p->execute();
      $ngyou = $sth_p->fetch(PDO::FETCH_ASSOC); 
      $con_length = explode(',', $ngyou['kiji_length']) ; //文字数(0,598)
      $IDs = explode(',', $ngyou['IDs']) ; // ID 配列
      $ps =  explode(',', $ngyou['ps']) ;  // 親の県
       
        // ヒットpost_parent で OR 検索
 echo '=============================' ; var_dump($ngyou['n']); echo "\n";
        if( $ngyou['n'] == 3){
            // 既存ページは3つあるのでupdateのみ3回
            draft_update($con_length,$IDs,$v['slug']);
            
        }elseif ( $ngyou['n'] === 2 || $ngyou['n'] === 1 ){  
            // 既存ページが1つか2つある｡update2回,insert1回
            
          $single_template = array(999=>'single-water_shiku.php',1012=>'single-glas_shiku.php',1017=>'single-key_shiku.php');
      // 投稿のない媒体を探す｡市区名から 親の親のID取得 県→ 水ToP 999 / 1012 /1017
            $sql="SELECT wp_posts.post_parent FROM wp_posts ,
              ( SELECT post_parent FROM wp_posts 
                WHERE post_name = '{$v['slug']}' ) as child
              WHERE wp_posts.ID = child.post_parent";
               $sth = $dbh->prepare($sql);
                $sth->execute();  
                  //php のシングルページテンプレート
   echo $sql ,"\n";             
        
                draft_update($con_length,$IDs,$v['slug']);  //UPDATE & meta insert 関数側でループする｡ hukusimaも送る
                
                foreach($sth as $val){
                  $kizon_baitai[]= $val['post_parent'] ;//有る媒体を配列化
                  unset($single_template[$val['post_parent']]);   //有る媒体を消して 残るのは水のみ
                } 
                
//var_dump($single_template,$ps[$i++], $v['area_name'], $v['slug'], $v['pref']);   exit();               
                insert_try_roop($single_template, $ps[$i++], $v['area_name'], $v['slug'], $v['pref']);

          
        }elseif ( $ngyou['n'] === 0 ){  
           $single_template = array(999=>'single-water_shiku.php',1012=>'single-glas_shiku.php',1017=>'single-key_shiku.php');
                

      // 市区の投稿がぜんぜんないので3つ作る
           insert_try_roop($single_template, "", $v['area_name'], $v['slug'], $v['pref']);
  
        }else{  
            // code...
            echo '/_/_/_/_/_その他の/_/_/_ ',$ngyou['n'];
        }
 
    } //市区を回す foreach




// UPDATE関数
function draft_update($con_length,$IDs,$shiku_slug=''){
   $io=0; global $dbh;
    $single_template = array(999=>'single-water_shiku.php',1012=>'single-glas_shiku.php',1017=>'single-key_shiku.php');
   //テンプレート変更のための、既存投稿済み媒体のコードを取得  999/1012/1017
   $sql= " 
        SELECT wp_posts.post_parent FROM wp_posts ,
        ( SELECT post_parent FROM wp_posts 
          WHERE post_name = '$shiku_slug' ) as child
        WHERE wp_posts.ID = child.post_parent;
   ";
echo $sql ,"\n";   
    $sth = $dbh->prepare($sql);
    $sth->execute();  
      foreach($sth as $vl) $kizon_template[]=$vl['post_parent'] ;//999 1012
var_dump($kizon_template);  
   foreach ($con_length as $con) { //文字数(0,0,598)
        // 記事が1文字以上あるなら
      $status= $con ?'publish':'draft'; //公開、下書き
     
      $sql_up="UPDATE wp_posts SET post_status= '{$status}'
        WHERE ID= {$IDs[$io]};";
   echo $sql_up;     
      $sthb = $dbh->prepare($sql_up);
      $sthb->execute();
      // 記事を下書きにする
      
      // post metaにINSERTする IGNOREは使えない
      //はじめにSELECT  レコードがあるかないか
        $sql="
          SELECT count(meta_id) as mID FROM `wp_postmeta` where post_id = {$IDs[$io]} AND meta_value ='{$single_template[$kizon_template[$io]]}'
        ";
        $sthc = $dbh->prepare($sql);
         $sthc->execute();
         $meta_id_count = $sthc->fetch(PDO::FETCH_ASSOC);
echo $sql , "\n meta_id_count "; var_dump($meta_id_count);    
         if($meta_id_count['mID']===0){
     //カスタム テンプレート 鍵,ガラス は未指定なので追加する
            $sql= "INSERT INTO wp_postmeta( post_id,meta_key,meta_value) VALUES";
            $sql.= "({$IDs[$io]},'_wp_page_template','{$single_template[$kizon_template[$io]]}') ;";  
            $sthmeta = $dbh->prepare($sql);
            $sthmeta->execute();
echo $sql , "\n";             
         }
      
      $io++;
   }
}


function  insert_try_roop($single_template,$ID,$area_name,$slug,$pref){  //5
  global $dbh;
 
    $sql="
      SELECT ID ,post_parent FROM `wp_posts`  WHERE post_name = '$pref' AND post_status = 'publish' ORDER BY post_parent 
    ";
    $sthb = $dbh->prepare($sql);
    $sthb->execute();
    
     foreach($sthb as $v) $pref_id[$v['post_parent']] = $v['ID']; // 1012=>707 keyが媒体
   
 var_dump($pref_id); 
 
 
  foreach($single_template as $key=>$val){  // 残った媒体を回す｡1012=>'single_'
    try{
      $dbh->beginTransaction();
      
      $sql_in="insert into wp_posts(post_author,post_date,post_date_gmt,post_title,
      post_status,comment_status,ping_status,post_name,post_modified,post_modified_gmt,post_parent,menu_order,post_type,comment_count)
       values(1,now(),now(),'{$area_name}','draft','closed','closed','{$slug}',now(),now(),'{$pref_id[$key]}','0','page',0);";
  $i++;
echo 'INSERT ',$sql_in ,"\n";   
      $sthb = $dbh->prepare($sql_in);
      $sthb->execute();
    // カスタムフィールド市町村  
      $lastID=$dbh->lastInsertId(); // 記事のpost ID 
      $sql= "INSERT  INTO wp_postmeta( post_id,meta_key,meta_value)
      VALUES($lastID,'市区町村名','{$area_name}' ),";
    // カスタム 道府県  
      $sql .= "($lastID,'都道府県', '{$pref}'),";
    // カスタムnoindex  
      $sql .= "($lastID,'index', 'noindex'),";
    // カスタム 道府県  
      $sql.= "($lastID,'follow', 'nofollow'),";
    // カスタム テンプレート 鍵,ガラス
      $sql.= "($lastID,'_wp_page_template','{$val}') ;";
   
 echo $sql ,"\n";        
      $sthb = $dbh->prepare($sql);
      $sthb->execute();
        //コミット
      $dbh->commit();
      
     }catch(PDOException $e){
        $dbh->rollback();
        throw $e;
  	    echo $e->getMessage();
  			
     };

  } //end foreach
}