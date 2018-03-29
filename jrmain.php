<?php
@session_start();
/*
Plugin Name: wp-construction-ex2
Plugin URI: https://ultimai.org/plugins
Description: 施工事例
Version: 2.0.1
Author: izumogawakaihatsu
Author URI: https://ultimai.org/
License: CC
*/


date_default_timezone_set('Asia/Tokyo');
   
$pdir9 = "/wp-content/plugins/wp-construction-ex" ; //puraguin URL  plugins_url() . 
 $pdir = plugin_dir_path( __FILE__ ); // 
 $baitai_ID = array('water'=>999,'glas'=>1012,'key'=>1017); // 媒体親記事の投稿ID
  if(empty($_SESSION['baitai_ID'])) $_SESSION['baitai_ID']=$baitai_ID; //use resion_form 
 
  include_once "jrsub.php"; // 検索
   include_once "jrcontrol.php"; // 管理画面用
   	include_once "include/control_inc.php";
   	include_once "page_search.php";  // TOPに埋め込み検索窓
   	include_once "zip_search.php";  // TOPに埋め込み検索窓
   	//include_once "zipsearch_form.php";  // TOPに埋め込み検索窓
 // css書き出し
 function myplugin_css_load() {
  wp_register_style('jrstyle.css', plugins_url('include/jrstyle.css', __FILE__));
  wp_enqueue_style('jrstyle.css');
 }
  add_action('wp_enqueue_scripts', 'myplugin_css_load');

 // wp_enqueue_style('common-style', $pdir9. '/jrstyle.css', '1.0', 'all' );
 
 //詳細URLのための配列作成 
  $fuguai_key=array('water','glas','key','all');
  $juchu=csvGetJuchu();
  
  $fuguai=csvGetFuguai();
     array_push( $fuguai['water'] , 'all');
     array_push( $fuguai['glas'] , 'all');
     array_push( $fuguai['key'] , 'all');
  $shojo=csvGetShojo();
    array_push($shojo['water'],'all');
    array_push($shojo['glas'],'all');
    array_push($shojo['key'],'all');
   
  $ssurl='https://www.sscgm.com/sirius';
   



if( !isset($_SESSION['prefs'])){
  $prefs=array('全ての都道府県'=>['all','all'],'北海道'=>['hokkaidou',1],'青森県'=>['aomori',2],'岩手県'=>['iwate',3],    '宮城県'=>['miyagi',4],'秋田県'=>['akita',5],'山形県'=>['yamagata',6],'福島県'=>['hukusima',7],
    '茨城県'=>['ibaragi',8],'栃木県'=>['tochigi',9],'群馬県'=>['gunma',10],'埼玉県'=>['saitama',11],'千葉県'=>['chiba',12],'東京都'=>['tokyo',13],'神奈川県'=>['kanagawa',14],
    '山梨県'=>['yamanashi',19],'新潟県'=>['niigata',15],'長野県'=>['nagano',20],'富山県'=>['toyama',16],'石川県'=>['ishikawa',17],'福井県'=>['hukui',18],
    '愛知県'=>['aichi',23],'岐阜県'=>['gihu',21],'静岡県'=>['shizuoka',22],'三重県'=>['mie',24],
    '大阪府'=>['oosaka',27],'兵庫県'=>['hyougo',28],'京都府'=>['kyoto',26],'滋賀県'=>['shiga',25],'奈良県'=>['nara',29],'和歌山県'=>['wakayama',30],
    '岡山県'=>['okayama',33],'広島県'=>['hiroshima',34],'山口県'=>['yamaguchi',35],'鳥取県'=>['tottori',31],'島根県'=>['simane',32],
    '徳島県'=>['tokushima',36],'香川県'=>['kagawa',37],'愛媛県'=>['ehime',38],'高知県'=>['kouchi',39],
    '福岡県'=>['hukuoka',40],'佐賀県'=>['saga',41],'長崎県'=>['nagasaki',42],'熊本県'=>['kumamoto',43],'大分県'=>['ooita',44],'宮崎県'=>['miyazaki',45],'鹿児島県'=>['kagoshima',46],'沖縄県'=>['okinawa',47]);
    
    $_SESSION['prefs']=$prefs;
} else{
   $prefs =  $_SESSION['prefs'];
}   
    

//ショートコードを登録
add_shortcode('seko_ichiran', 'ichiran' );
      
      

// 管理画面へメニュー登録
 function add_jirei_registration(){
  add_menu_page('事例検索管理', //HTMLのページタイトル
        '事例設定',//管理画面メニューの表示名
        '8',//この機能を利用できるユーザー''administrator=8 投稿者=2 
        'jirei_control',//urlに入る名前
        'jirei_registration', //機能を提供する関数をここで呼び出す
        'dashicons-pressthis'//アイコンhttps://developer.wordpress.org/resource/dashicons/
        );
 }
 add_action('admin_menu', 'add_jirei_registration');
 
 //市区を選ばないリンクにはnoindex 付与
 if(isset($_GET['shiku'])){
   if( $_GET['shiku']=='all' ){
     
    add_action( 'wp_head', 'add_meta_to_head' );
    function add_meta_to_head() {
      echo '<meta name="robots" content="noindex" />';
    }
   }
 }
 
 
 
 // page_2 へ埋め込み
 function shosai_page2($trb){
   $nowUriArray = explode('/', $_SERVER['REQUEST_URI']); 
   $tr = $trb[0];

   global $ssurl;   global $juchu; global $prefs; global $fuguai; global $shojo; global $pdir9;
   $get_shiku = $_SESSION['shiku_kanji'] ; // [0]=>漢字で大田区
   $shiku_arr = @$_SESSION['shiku'];   

    if(!empty($custom_fields['市区町村名'][0])){
    	$shiku_kanji=$custom_fields['市区町村名'][0];
    }elseif(!empty($_SESSION['shiku'])){
    	$shiku_kanji= $_SESSION['shiku'][0]; 
    }elseif(!empty($_SESSION['fgsj'])){
    	$shiku_kanji=$_SESSION['fgsj'][2];
    }else{
    	$shiku_kanji=$_GET['shiku'];
    }
    
    if(!empty($nowUriArray[4])){
      $shiku = $nowUriArray[4];
    }elseif(!empty($_SESSION['shiku'])){
      $shiku = $_SESSION['shiku'][1];
    }

// GETパラメータが無い場合 はURLを分解する
      $nowUriArray = explode('/', $_SERVER['REQUEST_URI']);
      $topDir = $nowUriArray[1]; // water
    if(empty($_GET['pref'])){
// [1] =>  "water" [2] =>   "area" [3] => "tokyo" [4] =>"oota"   [5] => "minute" [6] =>  "457395"    
    $pref_key = array_search( $nowUriArray[3] ,array_column($prefs, 0 )) ; // 13 都道府県番号
     $contents = file_get_contents($ssurl.'/display/detail2/'
    .$juchu[$tr].'/' . (int)@$nowUriArray[6] . '/'.$pref_key.'/all/all/'. $get_shiku .'/');
 var_dump('/display/detail2/'.$juchu[$tr].'/' . (int)@$nowUriArray[6] . '/'.$pref_key.'/all/all/'. $get_shiku .'/');   
//詳細リンク作成のための置換    
    $pref_key_kanji = isset($_SESSION['area']) ? $_SESSION['area']:"";  ; // 漢字の 都道府
    $ch_url = '/'.$tr.'/area/'.$nowUriArray[3].'/'.$nowUriArray[4].'/list/1/?list=1&pref='.$pref_key_kanji .'&shiku='. $_SESSION['shiku'];
    
      $contents = preg_replace('/pagelist\.php\?p_p=1/',$ch_url , $contents);
   
    }else{
 
     $contents = file_get_contents($ssurl.'/display/detail2/'
    .$juchu[$tr].'/' . (int)@$_GET['minutes'] . '/'.$prefs[$_GET['pref']][1].'/'.$_GET['fuguai'].'/'.$_GET['shojo'].'/'. $_GET['shiku'].'/');
//var_dump('/display/detail2/'.$juchu[$tr].'/' . (int)@$_GET['minutes'] . '/'.$prefs[$_GET['pref']][1].'/'.$_GET['fuguai'].'/'.$_GET['shojo'].'/'. $_GET['shiku'].'/'); 

      $contents = preg_replace('/pagelist\.php\?p_p=1/', '/'.$tr.'/area/'.$prefs[$_GET['pref']][0].'/'.$shiku.'/list/1/?list=1&pref='.$_GET['pref'].'&shiku='. $_GET['shiku'] .'#jpost', $contents);
      //パラメータがあったらセッションに入れる
        if(isset($_GET['shiku'])) $_SESSION['shiku_kanji']=$_GET['shiku'] ;
//var_dump($_SESSION['shiku_kanji']);      // 3から戻ったときにない 
    }

//[受注媒体]/ (int)@$_GET['p_i'] . '/[都道府県]/[不具合箇所]/[症状]/[住所]
   // 45781013/$_GET['pref']東京/all/小平市
     
    $contents = str_replace('p_img',"$pdir9/p_img", $contents);
// こっちの市区,都道府県はアルファベットだ    
    echo $contents;
 }
 //ショートコードを登録
add_shortcode('shosai_page2', 'shosai_page2' );






 // page_3 へ埋め込み 地域を選んでいる場合
 function shosai_page3($trb){
    $nowUriArray = explode('/', $_SERVER['REQUEST_URI']);
    $juchu_kanji=array('water'=>'水道','glas'=>'ガラス','key'=>'鍵');
   $tr = $trb[0]; // water

   global $ssurl;   global $juchu; global $prefs; global $fuguai; global $shojo;global $pdir9;
   
    $url="http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $site_url=explode("/",$_SERVER["REQUEST_URI"]);
    $custom_fields = get_post_custom(url_to_postid($url));
   
    if(!empty($custom_fields['市区町村名'][0])){
      $_SESSION['shiku_kanji']= $custom_fields['市区町村名'][0];
      	$shiku_kanji= $_SESSION['shiku-kanji']; 
    }
    if(!empty($_GET['shiku'])){
    	$shiku_kanji= $_GET['shiku']; 
      	$_SESSION['shiku-kanji']= $_GET['shiku']; 
    }
    if(!empty($_SESSION['fgsj'])){
    	$shiku_kanji=$_SESSION['fgsj'][2];
    		$_SESSION['shiku-kanji']= $_SESSION['fgsj'][2];
    }
    if(isset($site_url[4]) ){
      if($site_url[4]=="all"){
        $shiku_kanji =='全ての地域'; 
      }
      	$shiku_kanji_obj= area_to_kanji($site_url[4]);
      	$shiku_kanji =  $shiku_kanji_obj->area_name;
    }
 
    
    
   $shiku_arr = $_SESSION['shiku']; 
   
    if(!empty($nowUriArray[4])){
      $shiku = $nowUriArray[4];
    }elseif(!empty($_SESSION['shiku'])){
      $shiku = $_SESSION['shiku'][1];
    }
    
   $list = isset($_GET['list'])? (int)$_GET['list'] : 1 ;
   
// GETパラメータが無い場合 はURLを分解する
    if(empty($_GET['pref'])){
      $nowUriArray = explode('/', $_SERVER['REQUEST_URI']);
      $topDir = $nowUriArray[1]; // water
// [1] =>  "water" [2] =>   "area" [3] => "tokyo" [4] =>"oota"   [5] => "minute" [6] =>  "457395"    
      $pref_key = array_search( $nowUriArray[3] ,array_column($prefs, 0 )) ; // 13 都道府県番号
      
       $contents = file_get_contents($ssurl.'/display/pagelist/'.$juchu[$tr].'/'.$list.'/'.$pref_key.'/all/all/'. $_SESSION['shiku_kanji'] .'/');
//詳細リンク作成のための置換    
       $pref_key_kanji = isset($_SESSION['area']) ? $_SESSION['area']:"";  ; // 漢字の 都道府
      //$contents = preg_replace('/pagelist\.php\?p_p=1/', '/'.$tr.'/area/'.$nowUriArray[3].'/'.$nowUriArray[4].'/list/1/?list=1&pref='.$pref_key_kanji .'&shiku='. $_SESSION['shiku'] , $contents);
 
    }else{
// GETパラメータがある
     $contents = file_get_contents($ssurl.'/display/pagelist/'.$juchu[$tr].'/'.$list.'/' . $prefs[$_GET['pref']][1].'/all/all/'.$_SESSION['shiku_kanji'] );
    }

     $contents = str_replace('p_img',"$pdir9/p_img", $contents);
 
         $minutes =  explode('?p_i=',$contents); 
          	foreach ($minutes as $value) {
          		$min = substr($value ,0,6);
          			if(is_numeric($min)) $mints[]= $min;  //405904だけ取り出す
          	}
           $dllist =  explode('<dl',$contents);  // 5件を一つずつ配列に
            $content='';
            $i=0;
          		
          	foreach ($dllist as $value) {
          	  if(strlen($value)<99) continue;
               $ch_url = '/'.$tr."/area/";
               $ch_url .= isset($prefs[$_GET['pref']][0]) ? $prefs[$_GET['pref']][0].'/' : $nowUriArray[3].'/' ;
               $ch_url .= isset($nowUriArray[4]) ? $nowUriArray[4] : $shiku_arr[1] ;
               $ch_url .= "/?fuguai=all&shojo=all&pref=";
               //$ch_url2 .= $mints[$i];
               $ch_url .= isset($_GET['pref']) ? $_GET['pref'] :$pref_key_kanji;
               $ch_url .='&shiku=';
               $ch_url .= isset($_SESSION['shiku_kanji'])? $_SESSION['shiku_kanji'] : $shiku_arr[0];
               $ch_url2 = $ch_url.'&minutes=';
               $ch_url3 = $ch_url.'&list=';
          	
          	 	$v = '<dl '.$value ;
          		$content .= preg_replace('/detail\.php\?p_i=/', $ch_url2,$v);
          		++$i; 
          	}
          		$contents = $content;
 
      $contents = preg_replace('/href="detail\.php\?p_i=/', $ch_url2 , $contents);

      $contents = str_replace('pagelist.php?p_p=', $ch_url3 , $contents);
      
 // 1p目へ戻るときの置換     
     $contents = str_replace('pagelist.php', $ch_url3.'1', $contents);     

   if(!empty($pref_key_kanji)){
     $pref_kanji =$pref_key_kanji;
   }elseif(!empty($_GET['pref'])){
     $pref_kanji =$_GET['pref'];
   }
   //h2タイトル
  if( !empty($_SESSION['fgsj']) ){
      echo "<section class='search_result'><h2 id='search_clumb' class='title'>
       {$_SESSION['fgsj'][0]} &gt; {$_SESSION['fgsj'][1]} &gt; $pref_kanji &gt; $shiku_kanji &nbsp; {$juchu_kanji[$tr]}トラブル事例一覧</h2>  $contents </section>";
    }else{
      echo "<section class='search_result'><h2 id='search_clumb' class='title'>
       全ての不具合 &gt; 全ての症状 &gt; $pref_kanji &gt; {$shiku_kanji} &nbsp; 
      {$juchu_kanji[$tr]}トラブル事例一覧</h2>  $contents </section>";
    }
 }
 //ショートコードを登録
add_shortcode('shosai_page3', 'shosai_page3' );



 function area_to_kanji( $slug  ){
   global $wpdb;
    $sql = $wpdb->prepare( "
      SELECT apref.wamei,an.area_name
      FROM `apref` ,
        (SELECT pref , area_name
        FROM `area`
        where slug = %s ) as an
      where apref.pref =  an.pref
     " ,$slug );  //'東京 品川'
  
    $res = $wpdb->get_results($sql); 
  
     foreach ($res as $value) {
        return $value;
     }
 }
add_shortcode('area_to_kanji', 'area_to_kanji' );  

//任意のGETパラメーターの値に応じて読み込ませるテンプレート	
function load_original_template(){
  
  $nowUriArray = explode('/', $_SERVER['REQUEST_URI']);
  $topDir = $nowUriArray[1]; // 
	if( isset($_GET['minutes']) || $nowUriArray[5]=="minute"){
	  	include(TEMPLATEPATH . '/page_'.$topDir.'_2.php');
		exit();
	}elseif( isset($_GET['list'])){
	  	include(TEMPLATEPATH . '/page_'.$topDir.'_3.php');
		exit();
	}elseif( isset($_GET['cat'])){
	  	include(TEMPLATEPATH . '/index.php');
		exit();
	}
}	
  add_action('template_redirect', 'load_original_template');
 