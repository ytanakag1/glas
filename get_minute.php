<?php
//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
 try {    
  header("Content-type: text/plain; charset=UTF-8");
   require_once "include/db_connect.php";

  //function get_minute_all(){
    if(!empty($_POST['class_name'])){
      $trb =$_POST['class_name']; //水受注媒体
      $trbs = explode('-',$trb);
    }else{
      exit("検索情報がありません");
    }
        
     unset($prefs['全ての都道府県']);
    $i=3;
      foreach($prefs as $k => $v){
        $url =  $ssurl.'/display/index/'.$trbs[0].'/'.$v[1].'/all/all/all/';
 //  var_dump($url) ; exit();
        $contents = file_get_contents($url);
        
          if(!empty($contents)){
        // UPDATE `apref` SET jire_wt = 1 WHERE ID = 2;
        // $wpdb->update( $table, $data, $where, $format = null, $where_format = null );
            $sql="UPDATE `apref` 
              SET {$trbs[1]} = 1 WHERE ID = ?";// .$v[1];
  //  var_dump($sql) ;  exit(); 
             $sth = $dbh->prepare($sql);
             $sth->bindParam(1, $v[1] , PDO::PARAM_INT);
              $sth->execute();
          } 
          
       echo ++$i;   
      }
 // }
 } catch (PDOException $e) {
   exit('データベース接続失敗。'.$e->getMessage());
 }




   
// }// Ajax通信のみ
// else{
//   echo "404 Not Found <pre>";
 
// }