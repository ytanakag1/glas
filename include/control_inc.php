<?php
function jquery_apper(){
    global $pdir9;
    global $pdir;
?>    
    
 

<script>


	var baitai_w =jQuery("input[name='water']").val();
	var _wt=jQuery('.scraping_wt').val();
	jQuery('.scraping_wt').val( baitai_w + _wt + '-jire_wt');

	var baitai_g =jQuery("input[name='glas']").val();
	var _wt=jQuery('.scraping_gr').val();
	jQuery('.scraping_gr').val( baitai_g + _wt + '-jire_gr');

	var baitai_k =jQuery("input[name='key']").val();
	var _wt=jQuery('.scraping_ky').val();
	jQuery('.scraping_ky').val( baitai_k + _wt + '-jire_ky');
	

	jQuery("#water_chk").change(function(){
		var name_w =jQuery("input[name='water']");
		var bt_w =jQuery("#scraping_wt");
		if(jQuery(this).prop('checked')) {
			name_w.removeClass('scode');
			name_w.attr('required','required');
			name_w.next().removeClass('scode');
			bt_w.css('display','inline-block');
			bt_w.next().css('display','inline-block');
		}else{	
			name_w.addClass('scode'); 
			name_w.removeAttr('required');
			name_w.next().addClass('scode'); 
			 name_w.val('');
			bt_w.css('display','none');
			bt_w.next().css('display','none');
		}	
	});
	
	jQuery("#glas_chk").change(function(){
		var name_g =jQuery("input[name='glas']");
		var bt_g = jQuery("#scraping_gr");
		if(jQuery(this).prop('checked')) {
			name_g.removeClass('scode');
			name_g.attr('required','required');
			name_g.next().removeClass('scode');
			bt_g.css('display','inline-block');
			bt_g.next().css('display','inline-block');
		}else{	
			name_g.addClass('scode'); 
			name_g.next().addClass('scode'); 
			name_g.removeAttr('required');
			 name_g.val('');
			bt_g.css('display','none');
			bt_g.next().css('display','none');
		}	
	});
	
	jQuery("#key_chk").change(function(){
		var name_k =jQuery("input[name='key']");
		var bt_k = jQuery("#scraping_ky");
		if(jQuery(this).prop('checked')) {
			name_k.removeClass('scode');
			name_k.attr('required','required');
			name_k.next().removeClass('scode');
			bt_k.css('display','inline-block');
			bt_k.next().css('display','inline-block');
		}else{	
			name_k.addClass('scode'); 
			name_k.next().addClass('scode'); 
			name_k.removeAttr('required');
			 name_k.val('');
			bt_k.css('display','none');
			bt_k.next().css('display','none');
		}	
	});
	
////// Ajax でスクレイピング処理 onclick呼び出し	
function get_ssmg_pref(class_name){
    
    jQuery("#loading").fadeIn();
      var data = { class_name : jQuery('#'+class_name).val()};  

      jQuery.ajax({
        type: "POST",
        url: "<?=$pdir9?>/get_minute.php",
        data: data,
      }).success(function(data, dataType) {
       
        // PHPから返ってきたデータの表示
       alert(data);
          jQuery("#loading").fadeOut();
            
      }).error(function(XMLHttpRequest, textStatus, errorThrown) {
        alert('Error : ' + errorThrown);
 //     });
         jQuery("#loading").fadeOut();
      // サブミット後、ページをリロードしないようにする
      return false;
    });
}	

	
////// Ajax 地域データ取得 スクレイピング処理 jQuery呼び出し	
function get_ssmg_area(value){
   var values = value.split('-');
   jQuery("#loading").fadeIn();
   jQuery('#progres').html(values[2] + 'を読み込んでいます。');
      // POSTメソッドで送るデータを定義します var data = {パラメータ名 : 水受注媒体};
      var data = {'area_name' : value};  

      jQuery.ajax({
        type: "POST",
        url: "<?=$pdir9?>/get_minute_area.php",
        data: data,
      }).success(function(data, dataType) {
        // PHPから返ってきたデータの表示
     // alert(data);
         jQuery("#loading").fadeOut();
          jQuery('#progres').html('読み込みが終了しました。');
            
      }).error(function(XMLHttpRequest, textStatus, errorThrown) {
        // エラーメッセージの表示
        //alert('Error : ' + errorThrown);
 //     });
       
      // サブミット後、ページをリロードしないようにする
      return false;
    });
}	
		
</script>   
    
    
<?php }







function csvGetJuchu(){
	global  $pdir;
	//  読み込み→ 連想配列作成    
  $juchu_key=array('water','glas','key');
   $juchu=array();
		if (($handle = fopen($pdir."/inc/juchu.k3", "r")) !== FALSE) {
		     $csv = fgetcsv($handle, 1000, ",");
		     $csv = str_replace("'","",$csv);
		     $juchu = array_combine($juchu_key, $csv); //  ( array $keys , array $values )
		    fclose($handle);
		}
		return $juchu;
}


function csvGetFuguai(){
	global  $pdir;
	global  $fuguai_key;
	//  読み込み→ 連想配列作成    
 
   $fuguai=array();
  	$i=0;
		if (($handle = fopen($pdir."/inc/fuguai.k3", "r")) !== FALSE) {
			while(!feof($handle)){
		     $csv = fgetcsv($handle, 1000, ",");
		     $fuguai[$fuguai_key[$i++]] = str_replace("'","",$csv);
				
			}
		    fclose($handle);
		}
		return $fuguai;
}



function csvGetShojo(){
	global  $pdir;
	global  $fuguai_key;
	//  読み込み→ 連想配列作成    
 
   $shojo=array();
  	$i=0;
		if (($handle = fopen($pdir."/inc/shojo.k3", "r")) !== FALSE) {
			while(!feof($handle)){
		     $csv = fgetcsv($handle, 1000, ",");
		     $shojo[$fuguai_key[$i++]] = str_replace("'","",$csv);
			}
		    fclose($handle);
		}
		return $shojo;
}


function csvReset(){
	global  $pdir;
	global  $fuguai_key;
	//  読み込み→ 連想配列作成    
 
   $shojo=array();
  	$i=0;
		if (($handle = fopen($pdir."/inc/shojo.k3", "r")) !== FALSE) {
			while(!feof($handle)){
		     $csv = fgetcsv($handle, 1000, ",");
		     $shojo[$fuguai_key[$i++]] = str_replace("'","",$csv);
			}
		    fclose($handle);
		}
		return $shojo;
}

function csvShojoReset(){
		global  $pdir;
	global  $fuguai_key;
	$csv = file($pdir.'/inc/shojo.k3');
	$csv = str_replace("'","",$csv[3]);
	$shojo[$fuguai_key[0]] = explode( "," , $csv );
	$shojo[$fuguai_key[1]] = explode( "," , $csv );
	$shojo[$fuguai_key[2]] = explode( "," , $csv );
	return $shojo;
}
function csvFuguaiReset(){
		global  $pdir;
	global  $fuguai_key;
	$csv = file($pdir.'/inc/fuguai.k3');
	$csv = str_replace("'","",$csv[3]);
	$fuguai[$fuguai_key[0]] = explode( "," , $csv );
	$fuguai[$fuguai_key[1]] = explode( "," , $csv );
	$fuguai[$fuguai_key[2]] = explode( "," , $csv );
	return $fuguai;
}