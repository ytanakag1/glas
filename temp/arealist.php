<?php
header("Content-type: text/plain; charset=UTF-8");
require('../../../wp-blog-header.php');
  //    echo  "あきる野市";  exit();//$_POST['request'];
  //var_dump($_SERVER['HTTP_X_REQUESTED_WITH']);

  if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
     && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
    // Ajaxリクエストの場合のみ処理する
    if (isset($_POST['request']))  {
      //  カスタム投稿DBから地域
       $request=explode("_",$_POST['request']);
        for($agyo =1; $agyo <11; ++$agyo) 
        areaList($prefs[$_POST['request']][0],$_POST['trb']);
    }else{
        echo 'The parameter of "request" is not found.';
    }
  }


    
function areaList($a,$p,$tr){
  
  $prepared_sql = $wpdb->prepare( "
      SELECT *
      FROM $wpdb->users
      WHERE status = %d
    ",0 );
  
  $wpdb->get_results($prepared_sql); 
   // カスタム投稿DBから地域を抽出
$args = Array('post_type' => $tr.'_shiku','posts_per_page' => -1,'meta_key' => '都道府県','meta_value' => @$p, // tokyo 
'meta_query' => array(array('key'=>'市区町村あ行～わ行','value'=>$a,)));
      $the_query = new WP_Query($args);
      if( $the_query -> have_posts()){
          while($the_query -> have_posts()): $the_query -> the_post();
              //ここに処理を記述
            $shiku = post_custom('市区町村名');
            $slug = get_page_uri(get_the_ID());
         endwhile;
     } 
     wp_reset_postdata();
}