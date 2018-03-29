<?php
function jirei_registration(){
  
  global  $pdir;  //."/wp-construction-ex";   
  global  $pdir9;  //url/."/wp-construction-ex";   
  global  $fuguai_key;  
  
	include_once "include/control_inc.php";
  	// どのボタンも押されてない
	$juchu=csvGetJuchu();
  $fuguai=csvGetFuguai(); // array(3){'water',
  $shojo = csvGetShojo(); // 
echo '<style> /*admin menu*/
input[type="text"]{width: 420px; margin :10px 0 10px 15px ;}
	.baitai{margin-bottom: 35px}	.baitai label{margin-left: 32px}
		.baitai label:nth-of-type(1){margin-right: 23px;}	.baitai label:nth-of-type(2){margin-right: 16px;}
		.baitai label:nth-of-type(3){margin-right: 31px;}	input[type="submit"]{margin: 0 auto;display:block;}
		.item h3{font-size: 1.2em;text-align: center;}.item label {display: inline-block;width: 90px;margin-left: 14px}
		.item div{width: 30%;float: left ;margin-left: 20px ;border:1px solid #ccc; padding-bottom: 5px ;}
		.item textarea{width: 8em }	.item div p{ margin: 5px 0 3px 6px; width: 45%; float: left;}
		.item input[name="submit_item"]{clear:both;}	.item textarea { min-width: 120px;}
		.item {width:820px;margin-top:40px;}.scode{display:none;}.myoro-wrap {  position: fixed;  width: 90%; height: 60px;    top: 0;  left: 5%; background: rgba(205, 220, 57, 0.7); z-index: 9999;  border-bottom: 2px solid #009688; text-align: center; font-weight: 700; font-size: 2em; padding: 12px;}
    #loading,#area_bt{border:none; display:none;position: absolute;top:100px;left:calc(50% - 260px)} #loading img{width:100%;} #form2 button{ margin:1em; width: 16em;}
</style>';

  
if (!empty($_POST['submit_baitai'])) {
// 受注媒体が更新された
//書き込み
	if(!empty($_POST['water'])|| !empty($_POST['glas'])|| !empty($_POST['key'])){
		$handle = fopen($pdir."/inc/juchu.k3", "w");
		$juchu =array("'".$_POST['water']."'","'".$_POST['glas']."'","'".$_POST['key']."'" );
		fputcsv( $handle , $juchu ,",", "\"" );
		$juchu=csvGetJuchu();
		$_POST=NULL;
			echo '<div class="myoro-wrap"><h2> 更新しました </h2> </div><script> setTimeout(function(){ jQuery("div.myoro-wrap").hide(500);
	   },2000);</script>';
	}else{
		echo  '<div class="myoro-wrap"><h2>一つの業種は選択してください。 </h2> </div><script> setTimeout(function(){ jQuery("div.myoro-wrap").hide(500);
	   },2000);</script>';
	}
	
}elseif(!empty($_POST['submit_item'])){
// item が更新された &全部ある
	if(!empty($_POST['kasho']) && !empty($_POST['josho'])){
		 // 3行のデータを作成
		$post_kasho=$_POST['kasho'];
		if(isset($post_kasho['water']) && isset($post_kasho['glas']) && isset($post_kasho['key']) ){
			
			$kasho = '';
				foreach($post_kasho as $k=>$v){
					if( $k == 'water'){
						$kasho .= implode("','",$v);	$kasho = "'". $kasho ."'\n";
					}elseif($k=='glas'){
						$kasho .= "'". implode("','",$v);
							"'".$kasho .= "'\n";
					}elseif($k = 'key'){
						$kasho .= "'". implode("','",$v);	"'".$kasho .= "'\n";
					}
					// 初期化用
				}
					$kasho .= "'和式トイレ','洗面','洗濯','風呂','キッチン','壁床天井','屋外','共用','不明','その他','洋式トイレ','ガラス'";
							 
			$file = $pdir."/inc/fuguai.k3";
		//	$kasho = rtrim($kasho);
			 file_put_contents($file, $kasho);
		
			$fuguai=csvGetFuguai();
		}else{
				echo "<h2>チェックされていない項目があります。1</h2>";
		}
	

		 // 3行のデータを作成
		$post_shojo=$_POST['josho'];

		if(!empty($post_shojo['water']) && !empty($post_shojo['glas']) && !empty($post_shojo['key']) ){
			
			$josho = '';
				foreach($post_shojo as $k=>$v){
					if( $k == 'water'){
						$josho .= implode("','",$v);	$josho = "'". $josho ."'\n";
					}elseif($k=='glas'){
						$josho .= "'". implode("','",$v);	"'".$josho .= "'\n";
					}elseif($k = 'key'){
						$josho .= "'". implode("','",$v);	"'".$josho .= "'\n";
					}
				}
					$josho .= "'水漏れ','詰まり','器具','水道局','ガス他','事故','自然','災害','犯罪','その他'"; //初期化用	
							 
			$file = $pdir."/inc/shojo.k3";
			/// $josho = rtrim($josho);
			 file_put_contents($file, $josho);
		
			$shojo=csvGetShojo();
		}else{
				echo "<h2>チェックされていない項目があります。2</h2>";
		}
			
	}else{
			echo "<h2>チェックされていない項目があります。3</h2>";
	}
			$_POST=NULL;


	echo '<div class="myoro-wrap"><h2> 更新しました </h2></div>
	<script> setTimeout(function(){ jQuery("div.myoro-wrap").hide(500);
	   },2000);
	</script>';
									   
									   
}else if(!empty($_POST['submit_reset'])){ 
	if(isset($_POST['reset_fuguai']))		$shojo=csvShojoReset();
	if(isset($_POST['reset_shojo']))	$fuguai=csvFuguaiReset();



//// ssgm水県全データ取得
//}else if( !empty($_POST['scraping_wt'])){
//	 include_once $pdir."get_minute.php";
	 
//	 echo '<pre>' ,get_minute_all($_POST['scraping_wt']) , '</pre>' ;
//	 echo '<p>',date("Y-m-d H:i:s") ,'</p>';



	// ssgm水地域全データ取得
}else if( !empty($_POST['scraping_wt_area'])){
	 global $wpdb;
	 //include_once $pdir."get_minute.php";
	   // 県の jire_wt が1で選択
	   $ID = $wpdb->get_results("SELECT ID,pref FROM apref WHERE jire_wt = 1");
	   $button='';
	   $bt_ivent ="<script>\n  jQuery('#loading').fadeIn();\n ";
	   $pref_id=array();
	   $i=0;  $progres='これらの県を読み込みます。<br>';
	   
  			foreach($ID as $v){
  				$progres.= $v->pref ." ";
	  			// 県のボタンを作る
	  			$button .= "<button name='wt_area_single' id='{$v->pref}' value='{$juchu['water']}-{$v->ID}-{$v->pref}-jire_wt'>水_$v->pref</button>&nbsp;\n";
  				 $bt_ivent .="jQuery('#{$v->pref}').click(function(){ get_ssmg_area(jQuery(this).val());return false;});\n"; 
  				  $pref_id[]=$v->pref; 
  			}
  			
  			foreach($pref_id as $pf){
  				$bt_ivent .="
	        	setTimeout(function(){
	            jQuery('#".$pf."').click();
	        	}, ". $i++ * 5 ."0000);";
  			}
	        	
       $bt_ivent .=" </script>";
  
   
	// ssgm ガラス地域全データ取得
}else if( !empty($_POST['scraping_gr_area'])){
	 global $wpdb;
	 //include_once $pdir."get_minute.php";
	   // 県の jire_wt が1で選択
	   $ID = $wpdb->get_results("SELECT ID,pref FROM apref WHERE jire_gr = 1");
	   $button='';
	   $bt_ivent ="<script>\n  jQuery('#loading').fadeIn();\n ";
	   $pref_id=array();
	   $i=0;$progres='これらの県を読み込みます。<br>';
  			foreach($ID as $v){
  					$progres.= $v->pref ." ";
	  			// 県のボタンを作る
	  			$button .= "<button name='gr_area_single' id='{$v->pref}' value='{$juchu['glas']}-{$v->ID}-{$v->pref}-jire_gr'>ガラス_$v->pref</button>&nbsp;\n";
  				 $bt_ivent .="jQuery('#{$v->pref}').click(function(){ get_ssmg_area(jQuery(this).val());return false;});\n"; 
  				  $pref_id[]=$v->pref; 
  			}
  			
  			foreach($pref_id as $pf){
  				$bt_ivent .="
	        	setTimeout(function(){
	            jQuery('#".$pf."').click();
	        	}, ". $i++ * 5 ."0000);";
  			}
	        	
       $bt_ivent .=" </script>";
  	 
  	 
	// ssgm 鍵地域全データ取得
}else if( !empty($_POST['scraping_ky_area'])){
	 global $wpdb;
	 //include_once $pdir."get_minute.php";
	   // 県の jire_wt が1で選択
	   $ID = $wpdb->get_results("SELECT ID,pref FROM apref WHERE jire_ky = 1");
	   $button='';
	   $bt_ivent ="<script>\n  jQuery('#loading').fadeIn();\n ";
	   $pref_id=array();
	   $i=0; $progres='これらの県を読み込みます。<br>';
  			foreach($ID as $v){
  					$progres.= $v->pref ." ";
	  			// 県のボタンを作る
	  			$button .= "<button name='ky_area_single' id='{$v->pref}' value='{$juchu['key']}-{$v->ID}-{$v->pref}-jire_ky'>鍵_$v->pref</button>&nbsp;\n";
  				 $bt_ivent .="jQuery('#{$v->pref}').click(function(){ get_ssmg_area(jQuery(this).val());return false;});\n"; 
  				  $pref_id[]=$v->pref; 
  			}
  			
  			foreach($pref_id as $pf){
  				$bt_ivent .="
	        	setTimeout(function(){
	            jQuery('#".$pf."').click();
	        	}, ". $i++ * 5 ."0000);";
  			}
	        	
       $bt_ivent .=" </script>";
  	 
			
	
}	





 // 媒体設定  値があれば必須でon
	$require=array('','','');
	$checked=array('','','');
//	$disabled=array(' disabled',' disabled',' disabled');
	$scode=array('scode','scode','scode');
	
	if( !empty($juchu['water']) ){$require[0]=' required' ; $checked[0]=' checked'; $disabled[0]='';$scode[0]='';}  
	if( !empty($juchu['glas']) ){$require[1]=' required' ; $checked[1]=' checked'; $disabled[1]='';$scode[1]='';}  
	if( !empty($juchu['key']) ){$require[2]=' required' ; $checked[2]=' checked'; $disabled[2]='';$scode[2]='';}  
	
?>	
	
	
	
    
 <div class="baitai"><h2>受注媒体</h2>
  <span>対応する業務分類にチェックしてください</span>
	<form action="" method="post" name="fm" id="fm1">
		<label><input type="checkbox" name="juchu_chk[]" id='water_chk' value="water"	<?=$checked[0]	?>/> 水道 &nbsp;</label>
			<input type="text" name="water" value="<?=$juchu['water']?>" autocomplete="off" <?= $require[0]?> class ='<?=$scode[0]?>' placeholder='更新していない場合はページの再読み込みで復活します' > 
			<span class ='<?=$scode[0]?>'>[seko_ichiran water]</span><br>

		<label><input type="checkbox" name="juchu_chk[]" id='glas_chk' value="glas"	<?=$checked[1]	?>/> ガラス</label>
			<input type="text" name="glas" value="<?=$juchu['glas']?>" autocomplete="off" <?= $require[1]?>  class ='<?=$scode[1]?>' placeholder='更新していない場合はページの再読み込みで復活します'>
			<span  class ='<?=$scode[1]?>'>[seko_ichiran glas]</span><br>

		<label><input type="checkbox" name="juchu_chk[]" id='key_chk' value="key" <?=$checked[2]	?>/>	鍵 &nbsp;&nbsp;</label>
			<input type="text" name="key" value="<?=$juchu['key']?>" autocomplete="off" <?=$require[2]?>  class ='<?=$scode[2]?>' placeholder='更新していない場合はページの再読み込みで復活します'>
			<span  class ='<?=$scode[2]?>'>[seko_ichiran key]</span><br>
				<input type="submit" name="submit_baitai" value="更新する">
	</form>
</div>



<div class="item"><h2>事例選択アイテム</h2>
<form action="" method="post" name="bt">
	<div><h3>水道</h3>
		<p>	<strong>不具合箇所</strong>  <br>
<?php
 //不具合箇所中身
	foreach ( $fuguai['water'] as $v ) { ?>
		<label><input type='checkbox' name="kasho[water][]" value='<?=$v?>' checked><?=$v?></label><br>
<?php	}
?>
	 </p>
		<p> 	<strong>症状</strong><br>
<?php
 //不具合箇所中身
	foreach ( $shojo['water'] as $v ) { ?>
		<label><input type='checkbox' name="josho[water][]" value='<?=$v?>' checked><?=$v?></label><br>
<?php	}
?>
	 </p>	</div>	

	<div><h3>ガラス</h3>
		<p>	<strong>不具合箇所</strong> <br>
<?php
 //不具合箇所中身
	foreach ( $fuguai['glas'] as $v ) { ?>
		 <label><input type='checkbox' name="kasho[glas][]" value='<?=$v?>' checked><?=$v?></label><br>
<?php }
?>
			
		</p>
		<p><strong>症状</strong><br>
<?php
 //不具合箇所中身
	foreach ( $shojo['glas'] as $v ) { ?>
	  <label><input type='checkbox' name="josho[glas][]" value='<?=$v?>' checked><?=$v?></label><br>
<?php }
?>		
		</p>	</div>	

	<div><h3>鍵</h3>
		<p>	<strong>不具合箇所</strong><br>
<?php
 //不具合箇所中身
	foreach ( $fuguai['key'] as $v ) { ?>
		 <label><input type='checkbox' name="kasho[key][]" value='<?=$v?>' checked><?=$v?></label><br>
<?php }
?>		
	 </p>
		<p> <strong>症状</strong><br>
<?php
 //不具合箇所 中身
	foreach ( $shojo['key'] as $v ) { ?>
		 <label><input type='checkbox' name="josho[key][]" value='<?=$v?>' checked><?=$v?></label><br>
<?php }
?>		
		</p> </div>	

 <input type="submit" name="submit_item" value="更新する">
 <p>
 	
 	<label><input type='checkbox' name="reset_fuguai" value='true' >不具合箇所</label>
 	<label><input type='checkbox' name="reset_shojo" value='true'  >症状</label>
 	
  <button type="submit" name="submit_reset" value="リセット">チェックした項目をリセット</button>
</p>
</form>
</div>





<!--スクレイピング処理-->
<div class="item"><h2>事例取得</h2>
	<form id="form2" action="" method="post">
		<p><button type="button" class='scraping_wt' id='scraping_wt' name='scraping_wt' value="" onclick='get_ssmg_pref("scraping_wt")'>水県データを取得</button>
		
		<button type="submit" class='scraping_wt' id="scraping_wt_area" name='scraping_wt_area' value="" >水地域データを取得</button></p>
		
		<p><button type="button" class='scraping_gr' id='scraping_gr' name='scraping_gr' value="" onclick='get_ssmg_pref("scraping_gr")'>ガラス県データを取得</button>
		
		<button type="submit" class='scraping_gr' id="scraping_gr_area" name='scraping_gr_area' value="" >ガラス地域データを取得</button></p>
		
		<p><button type="button" class='scraping_ky' id='scraping_ky' name='scraping_ky' value="" onclick='get_ssmg_pref("scraping_ky")'>鍵県データを取得</button>
		
		<button type="submit" class='scraping_ky' id="scraping_ky_area" name='scraping_ky_area' value="" >鍵地域データを取得</button></p>
		
		<p id="area_bt"><?php echo isset($button)?$button:'' ?></p>
		<p><?php echo isset($progres)?$progres:'' ?> </p>
		<h3 id="progres"> </h3>
		
	</form>

	<div id="loading">
		<img src="<?=$pdir9?>/include/ajax-loader.gif">
	</div>
	
</div>
     

<?php	
	echo isset($bt_ivent)?$bt_ivent:'';



	
 
	jquery_apper();




} // end function




