<?php
function ichiran($trb){  // $trb=waret ショートコード引数は配列
 global $pdir9; global $ssurl;
 global $juchu;  global $prefs;
 global $fuguai; global $shojo; 
  $tr = $trb[0];

include_once 'jrsearch_form.php';  // 検索フォーム
 
  if(!empty($_GET)){  //検索状態
  
        if(empty($_GET['shiku'])){
          $get_shiku= array('all','全ての地域');
        }else{
          $get_shiku=explode('-',$_GET['shiku']); // [0]"狛江市" [1]"komae"
        } 
      $_SESSION['shiku']=$get_shiku;
        
        $fg= (@$_GET['fuguai']!="all" )? $fuguai[$tr][$_GET['fuguai']]:'すべての不具合';
        $sj= (@$_GET['shojo']!="all" )? $shojo[$tr][$_GET['shojo']]:'すべての症状';
        $gs= ($get_shiku[0]!="all" ) ? "<a href='/".$tr."/area/{$prefs[$_GET['pref']][0]}/".$get_shiku[1]."'>" .$get_shiku[0]."</a>": $get_shiku[1] ;
       $_SESSION['fgsj']=array($fg,$sj,$gs);   
//////////// 箇所&症状&地域検索   //////////////   
    if(isset($_GET['fuguai']) && isset($_GET['shojo']) && empty($_GET['minutes']) && empty($_GET['list'])){
     
     seko_search_form($tr);
    // 東京_water でくるので
   // explode("_",$_GET['pref']);
    
      $dpam=array(
        jb=>$juchu[$tr], // 水
        pf=>$prefs[$_GET['pref']][1],   //all 13
        fk=>$_GET['fuguai'], // num
        sj=>$_GET['shojo'],
        ad=>$get_shiku[0] //all 
      );  // コードで検索 5件がlimitらしい
 
 
        $sdurl .=$ssurl.'/display/index/'. $dpam['jb'].'/'.$dpam['pf'].'/'.$dpam['fk'].'/'.$dpam['sj'].'/'.$dpam['ad'];
        $contents=file_get_contents($sdurl);
//var_dump('/display/index/'. $dpam['jb'].'/'.$dpam['pf'].'/'.$dpam['fk'].'/'.$dpam['sj'].'/'.$dpam['ad']);       
        $ch_url= $pdir9.'/p_img';  // 画像パス
          $contents = str_replace('p_img',$ch_url, $contents);
     
        $ch_url2=$pdir9.'/p_img/case';
          $contents = str_replace($ssurl.'/files/case',$ch_url2, $contents);
    
        // /minuteは予約語なので使えない/?fuguai=1&shojo=1&pref=東京都&shiku=大田区';
      //$home= get_site_url();  
      if($prefs[$_GET['pref']][0]!='all' && $get_shiku[0]=="all" ){
  //市区は選んでない /都道府県 まで選んでいる ".$prefs[$_GET['pref']][0]."
          $ch_url2=' rel="nofollow" href="/'.$tr.'/area?fuguai='.$_GET['fuguai'].'&shojo='.$_GET['shojo'].'&pref='.$_GET['pref'].'&shiku='.$get_shiku[0].'&minutes=';
          
      }elseif( $get_shiku[0]!="all"){ 
  //市区を選んでいる //glas/area/tokyo/kodaira/minute/405904/
                   ///water/area/minute/457394?
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
               $ch_url2="/";
               $ch_url2 .= $tr."/area/";
               $ch_url2 .= $prefs[$_GET['pref']][0]."/";
            //   $ch_url2 .= $get_shiku[1] ."/minute/";
            //   $ch_url2 .= $mints[$i];
               $ch_url2 .= $get_shiku[1] ."/";
               $ch_url2 .= "?fuguai=". $_GET['fuguai'].'&shojo='.$_GET['shojo'].'&pref='.$_GET['pref'].'&shiku='.$get_shiku[0].'&minutes=';
          	
          	 	$v = '<dl '.$value ;
          		$content .= preg_replace('/detail\.php\?p_i=/', $ch_url2,$v);
          		++$i; 
          	}
          		$contents = $content;
        }else{
  // 都道府県、地域を選んでない場合
          $ch_url2=' rel="nofollow" href="/'.$tr.'/area?fuguai='.
          $_GET['fuguai'].'&shojo='.$_GET['shojo'].'&pref='.$_GET['pref'].'&shiku='.$get_shiku[0].'&minutes=';
        }

// リンク書き換え
        $contents = preg_replace('/href="detail\.php\?p_i=/', $ch_url2,$contents); 
      
        if(empty($contents)){
          echo "<h4 id='search_clumb'> 絞り込み条件での事例はありませんでした。</h4>";

        }else{
           echo "<section class='search_result'><h4 id='search_clumb'> $fg &gt; $sj &gt; ";
          // 投稿へのパンくず 見出しのリンク
           if( $prefs[$_GET['pref']][0]!='all'){
             echo "<a href='/".$tr."/area/{$prefs[$_GET['pref']][0]}'>{$_GET['pref']}</a>";
           }else{
             echo $_GET['pref'];
             
           }
           echo " &gt; $gs &nbsp; の結果</h4>
             $contents </section>";
        }

////////// トラブルの詳細記事 ssmgから取得している。DBにはない /////    
    }elseif (!empty($_GET['minutes']) && empty($_GET['list'])) {
      $contents = file_get_contents($ssurl.'/display/detail2/'.$juchu[$tr].'/'.(int)$_GET['minutes']);
  
 
      $contents = str_replace('p_img',$ch_url, $contents);
      
 ///記事一覧を見る ボタン  市区がallなら nofollow
      if($get_shiku[0]=='all'){
       $ch_url2=' rel="nofollow" href="/'.$tr.'/area/?fuguai='.$_GET['fuguai'].'&shojo='.$_GET['shojo'].'&pref='.$_GET['pref'].'&shiku='.$get_shiku[0].'&list=';
      }else{
       $ch_url2=' href="/'.$tr.'/area/?fuguai='.$_GET['fuguai'].'&shojo='.$_GET['shojo'].'&pref='.$_GET['pref'].'&shiku='.$get_shiku[0].'&list=';
      }
    
      $contents = preg_replace('/href="pagelist\.php\?p_p=/', $ch_url2 , $contents);
      
      echo '<section id="minutes" ><img src="/wp-content/uploads/2016/09/image_13.png" alt="image_13" style="max-width:100%; height:auto;" class="aligncenter size-full wp-image-1099">', $contents , ' 
       <img src="/wp-content/uploads/2016/09/image_14.png" alt="image_14" width="650" height="574" class="aligncenter size-full wp-image-1101"></section>';



//////////一覧リンクをクリック  地域を選んでいない場合にはこっち/////////////    
    }elseif(!empty($_GET['list'])){
      $contents = file_get_contents($ssurl.'/display/pagelist/'.$juchu[$tr].'/'.(int)$_GET['list'].'/'.$prefs[$_GET['pref']][1].'/'.$_GET['fuguai'].'/'.$_GET['shojo'].'/'.$get_shiku[0].'/');

      $contents = str_replace('p_img',$ch_url, $contents);

   //   $ch_url2= '/p_img/case';
      $contents = str_replace($ssurl.'/files/case',$ch_url2, $contents);
      
       if($get_shiku[0]=='all'){ // sikuが allなら
          $ch_url2=' rel="nofollow" href="/'.$tr.'/area/?fuguai='.$_GET['fuguai'].'&shojo='.$_GET['shojo'].'&pref='.$_GET['pref'].'&shiku='.$get_shiku[0].'&minutes=' ;
       }else{
          $ch_url2=' href="/'.$tr.'/area/?fuguai='.$_GET['fuguai'].'&shojo='.$_GET['shojo'].'&pref='.$_GET['pref'].'&shiku='.$get_shiku[0].'&minutes=' ;
       }
      $contents = preg_replace('/href="detail\.php\?p_i=/', $ch_url2 , $contents);
 
 //ページャー      
      $ch_url2= '/'.$trb[0].'/area?fuguai='.$_GET['fuguai'].'&shojo='.$_GET['shojo'].'&pref='.$prefs[$_GET['pref']][1] .'&shiku='.$get_shiku[0].'&list=';
      $contents = preg_replace('/pagelist\.php\?p_p=/', $ch_url2 , $contents);

 // 1p目だけの置換     
      $contents = preg_replace('/pagelist\.php/', $ch_url2.'1', $contents);

      echo "<section class='search_result'><h2 id='search_clumb' class='title'>
       $fg &gt; $sj &gt; {$_GET['pref']} &gt; $gs &nbsp; 
      水道トラブル事例一覧</h2>  $contents </section>";
    }
    
      
  } //検索状態
   else{ // 非検索状態 GETがない
  
     seko_search_form($tr);
   } 
   // wp_enqueue_script('orijs', $pdir9.'/jrarea.js',  '3.1.1' );
     include_once 'jrarea.js.php';
} // enf function