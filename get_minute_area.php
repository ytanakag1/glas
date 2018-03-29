<?php    
//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
  
  try {    
  header("Content-type: text/plain; charset=UTF-8");
   require_once "include/db_connect.php";

   $trb =$_POST['area_name']; //水受注媒体
     $trbs = explode('-',$trb); //0=>受注コード、1=>県ID 、2=>県名 3=>jiew_wt
  
  // 事例ありの県で絞り込み ID area 検索
  $sql ="SELECT ID,area_name FROM area WHERE pref = ?";  // 
     $sth = $dbh->prepare($sql);
       $sth->bindParam(1, $trbs[2] , PDO::PARAM_STR);
        $sth->execute();


  foreach($sth as $k=>$v){
    //  13  千代田区 で検索
     $url =  $ssurl.'/display/index/'.$trbs[0].'/'.$trbs[1]."/all/all/".$v['area_name']."/";
        $contents = file_get_contents($url);
//var_dump($v);// exit();
    
         if(!empty($contents)){
           echo htmlspecialchars($contents); 
            $sql="UPDATE `area` 
              SET {$trbs[3]} = 1 WHERE ID = ?";// .$v[1];
  //  var_dump($sql) ;  exit(); 
             $sth = $dbh->prepare($sql);
             $sth->bindParam(1, $v['ID'] , PDO::PARAM_INT);
              $sth->execute();
         }
     }

  } catch (PDOException $e) {
   exit('データベース接続失敗。'.$e->getMessage());
  }

   
// }// Ajax通信のみ
// else{
//   echo "404 Not Found <pre>";
 
// }