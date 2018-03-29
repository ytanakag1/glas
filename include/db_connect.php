<?php
    require_once('../../../wp-config.php');
    try {
      $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8',DB_USER,DB_PASSWORD,
      array(PDO::ATTR_EMULATE_PREPARES => false));
      $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      $dbh ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
     } catch (PDOException $e) {
       exit('データベース接続失敗。'.$e->getMessage());
    }
    
     $prefs =  $_SESSION['prefs'];
        $ssurl='https://www.sscgm.com/sirius';