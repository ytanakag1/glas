<?php
function zipSearch($trb){
  // 郵便番号検索 、現在地、入力 本体ファイル  
 global $pdir9; global $ssurl;
 global $juchu;  global $prefs;
 global $fuguai; global $shojo;  global $wpdb;
  $tr = $trb[0];

  $isToppage= ( $_SERVER["REQUEST_URI"] == '/')? true : false;
 ?>


 <style type="text/css">
   #first_label, #first_label+label{width: 50%;float: left;font-size: 1.3em;}
   .article #area dl li{display: inline-block;line-height: 1em;font-size: 1em;}
   .article #area dl dd{height: 60px;}
   .article #area dl li a{font-weight: bold;  color: #06C;  text-decoration: none;}
   #area dl li a:hover{color: #39f;  text-decoration: underline;}
   #seko_search {text-align:left;width: 100% !important;  float: none !important; }
   #seko_search input[type='submit']{position: relative;  top: -7px;}
     
 </style>
 <script>
   var POSTAL_version = "1.1.0";

    var protocol = document.location.protocol == "https:" ? "https:" : "http:";
    var POSTAL_uri = protocol + "//www.anchor-gr.jp/s/POSTAL/?";
    var POSTAL_callback = "displayOut";
    
    /* default:[jsonp_html] json, jsonp */
    var POSTAL_mode = "jsonp";
    
    /* default:[utf-8] utf-8, euc-jp, shift_jis */
    var POSTAL_encoding = "utf-8";
    
    var POSTAL_zipcodeObj = "";
    var POSTAL_loadingObj = "";
    var POSTAL_alert = "err.";
    
    function searchPOSTAL() {
      POSTAL_loadingObj.style.display = 'block';
      if(!POSTAL_zipcodeObj.value.match(/^\d\d\d\-?\d\d\d\d$/)){
        alert(POSTAL_alert + ":E38");
        POSTAL_loadingObj.style.display = 'none';
        return;
      }
    
      var opts = new Array();
      opts.push("mode="+POSTAL_mode);
      opts.push("encoding="+POSTAL_encoding);
      opts.push("callback="+POSTAL_callback);
      opts.push("send=q");
      opts.push("code="+POSTAL_zipcodeObj.value);
    
      var elm = document.createElement('script');
      var head = document.getElementsByTagName('head')[0];
      elm.setAttribute('type', 'text/javascript');
      elm.setAttribute('src', POSTAL_uri + opts.join("&amp;"));
      head.appendChild(elm);// head.removeChild(elm);
      return;
    }

 </script>

 <div class="ziparea">
      
<script type="text/javascript" src="<?=$pdir9?>/include/postal_search.js"></script>
  <form method="get" name="fmz" id="fmz" action="/<?=$tr?>/area/">
    <h3>出動エリアから探す</h3>
      
      <div class="yubin">
        <label>〒</label>
        <input type="text" id="ID_CODE" onkeyup="searchPOSTALex(event); ajaxSetformAction();" autocomplete="off" placeholder="1234567"/>
         <div class="balloon"><strong>!</strong>7桁の数字を｢ハイフンなし｣で入れてください｡</div>
        <input type="button" id="sagasu" value="住所自動入力" onclick="searchPOSTAL();" />　
        <button type="submit" id="btn_fmz" value="探す" />探す</button>

 
         <input type="hidden" name="fuguai" value="all" /> 
         <input type="hidden" name="shojo" value="all" /> 
         <input type="hidden" name="pref" value="" id="ID_ADDR1" /> <!--都道府県-->
         <input type="hidden" name="shiku" value="" id="ID_ADDR2" />
         <input type="hidden" value="" id="ID_ADDR3" />
        <div id="ID_LOADING" style="display:none;">
         <img src="https://www.anchor-gr.jp/image/common/indicator.gif"> 検索中です
        </div>
  
      </div>
  </form>
    
      <div class="btn_ichi">
        <button onclick="geoFindMe()">現在地から探す</button>
        <button type="button" id="region_ac" value="region" onclick="ajaxRegion()">地域から探す</button>
      </div>
    
 </div>
 





<?php  $isToppage= ( $_SERVER["REQUEST_URI"] == '/')? true : false; //top の場合 ?>
    <select name="baitai" id="baitai" <?php if(!$isToppage)echo 'style="display:none"'; ?> >
        <option value="/water/area/">水のトラブル</option>
        <option value="/glas/area/">ガラスのトラブル</option>
        <option value="/key/area/">鍵のトラブル</option>
      </select>



<section class='search_wrap'>
 <h3>対応地域検索</h3>
  
  <div id="seko_search" class="article">
      <div id="area">
        <div id="first_label" style="display: block;" onclick='ajaxPrefsearch()'><button>都道府県から選ぶ</button> </div>
                 
        <div style="display: none;" id="second_label"><button> 閉じる</button></div>
  
        <div id='region_dl'></div>
   </div>   <!--area-->
  </div> <!--"seko_search"-->
<?php  include_once 'jrarea.js.php'; ?>
</section>

<script>
  
  function ajaxPrefsearch(){
       var baitai = jQuery('[name="baitai"]').val(); // /water/area/
      // POSTメソッドで送るデータを定義します var data = {パラメータ名 : 値};
        var data = {'baitai':baitai};
          // * @param data  : サーバに送信する値
        jQuery.ajax({
          type: "POST",     //zipsearch_form.php
          url: "<?=$pdir9?>/zipsearch_form.php",
          data: data,
        }).done(function(data, dataType) {
          // PHPから返ってきたデータの表示
          if( data =="") data ="登録されている地域はありません";
          
           jQuery('#first_label').parents('#area').append( data ); // region_form.phpからの戻り値 
           
        }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
          alert('Error : ' + errorThrown);
        });
    
     return false;
          
  }
</script>



<?php  
 // phpの配列からjsの配列を作成
  foreach ($fuguai as $key=>$values) {
   $fuguai_str ='';
     foreach($values as $k=>$value){
        $fuguai_str .= "'$value',";
     }
    
      echo "<script>
        var fuguai_$key = [$fuguai_str];
        </script>";
  }
     
  foreach ($shojo as $key=>$values) {
   $shojo_str ='';
     foreach($values as $k=>$value){
        $shojo_str .= "'$value',";
     }
    
      echo "<script>
        var shojo_$key = [$shojo_str];
        </script>";
  }
     

 ?>
 
<script>
  
  function ajaxRegion(){
         var baitai = jQuery('[name="baitai"]').val(); // /water/area/
      // POSTメソッドで送るデータを定義します var data = {パラメータ名 : 値};
        var data = {'baitai':baitai};
          // * @param data  : サーバに送信する値
        jQuery.ajax({
          type: "POST",
          url: "<?=$pdir9?>/region_form.php",
          data: data,
        }).done(function(data, dataType) {
          // PHPから返ってきたデータの表示
          if( data =="") data ="登録されている地域はありません";
          
           jQuery('#region_ac').parents('.btn_ichi').append( data ); // region_form.phpからの戻り値 
           
        }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
          alert('Error : ' + errorThrown);
        });
       return false;
      
  }
</script>


<?php
 echo "<script>";
  for ($i = 0; $i < 9; $i++) {
     echo "
    function re$i(){
      if(jQuery('#re$i ul').css('display') == 'none'){
          jQuery('#re$i ul').show(300);
      }else{
          jQuery('#re$i ul').hide(300);
      }    
    };   
   ";
  }
  
  echo "jQuery(document).click(function(event) { ";
    for ($i = 0; $i < 9; $i++) {
      echo "if(!jQuery(event.target).closest('#re$i h4').length) {
       jQuery('#re$i ul').hide(300);
      }  ";
    }
  echo "});"; 
    
    
  echo "</script>";

  $option_select=array('top'=>0,'water'=>0,'glas'=>1,'key'=>2);
?>   
<script> 

  window.onpageshow = function(){
   jQuery('[name="baitai"] option').eq("<?=$option_select[$tr]?>").attr('selected','selected');
  };
      
  // 領域外をクリックで消える
    jQuery(document).click(function(event) {
      if(!jQuery(event.target).closest('.taio_search_wrap').length) {
       jQuery('.taio_search_wrap').remove();
    }  });   
  
  
var vala = jQuery('#fmz').attr('action');
 jQuery(function(){
  jQuery('form[name="fm"]').attr('action', vala);
 });
 
 
 // 業種をchange
  jQuery('[name="baitai"]').change(function() {
      vala = jQuery('[name="baitai"]').val();
       jQuery('form[name="fm"]').attr('action', vala);
       jQuery('form[name="fmz"]').attr('action', vala);
      //配列ループ option
      if(vala == '/water/area/'){
        var fuguai_arr = fuguai_water;
        var shojo_arr = shojo_water;
      }else if(vala == '/glas/area/'){
        var fuguai_arr = fuguai_glas; 
        var shojo_arr = shojo_glas; 
      }else if(vala == '/key/area/'){
        var fuguai_arr = fuguai_key;
        var shojo_arr = shojo_key;
      }
     // 箇所と症状の選択肢を入れ替える 
      jQuery('#fuguai').html(''); 
        jQuery.each(fuguai_arr, function(i, value) {
          //<option value="all" selected="selected" '="">すべての箇所</option>
          if(value == 'all'){
            jQuery('#fuguai').append('<option value="all" selected="selected">すべての箇所</option>');
          }else{
            jQuery('#fuguai').append('<option value="'+i + '">' + value + '</option>');
          }
      });      
    
      jQuery('#shojo').html(''); 
        jQuery.each(shojo_arr, function(i, value) {
          //<option value="all" selected="selected" '="">すべての箇所</option>
          if(value == 'all'){
            jQuery('#shojo').append('<option value="all" selected="selected">すべての症状</option>');
          }else{
            jQuery('#shojo').append('<option value="'+i + '">' + value + '</option>');
          }
       jQuery('#fuguai').focus();      
        });
        
  });
 
    
  function ajaxSetformAction(){
    if(jQuery('#ID_CODE').val().length < 7) return;
    
      jQuery("#btn_fmz").prop("disabled", true);
      setTimeout(function(){
         var pref=jQuery('#ID_ADDR1').val();
         var shiku= jQuery('#ID_ADDR2').val();
         var baitai = jQuery('[name="baitai"]').val();
      // POSTメソッドで送るデータを定義します var data = {パラメータ名 : 値};
        var data = {'pref':pref,'shiku':shiku, 'baitai':baitai};
          // * @param data  : サーバに送信する値
        jQuery.ajax({
          type: "POST",
          url: "<?=$pdir9?>/send_prefshiku.php",
          data: data,
        }).done(function(data, dataType) {
          // PHPから返ってきたデータの表示
              //	window.location.href = hhref ;
         setTimeout(function(){
            var ziplength=data.toString().length
            if(  data.toString().length < 3) {
              alert("都道府県が取得できません。\n他の番号でお試しください");
              jQuery('form[name="fmz"]').attr('action', ''); 
            }else{
              jQuery('form[name="fmz"]').attr('action', data);  // water/area/tokyo/adachi/
              jQuery("#btn_fmz").prop("disabled", false);
            }  
          },1500);
           
            //  	return false;
        }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
          alert('Error : 正確な住所が取得できませんでした' );
            jQuery("#btn_fmz").prop("disabled", false);
        });
       return false;
      },1000);  
      
  }
 
 
 function zipCheck(sp){  // 郵便番号チェック
    if( sp.match(/^\d{7}$/)  ){
         return true;       // 条件を満たしたらtrueを返す
    }else{
      errAlert(jQuery("#ID_CODE"));   // 満たさないなら自作のアラート
      return false;
    }
 }
 function errAlert(sp){ // バルーン式に開閉するユーザ定義関数
    sp.focus().next().show();
      setTimeout( function(){ sp.next().hide(1500);
       },3000) ;
 }
 
 
 
 
 function geoFindMe(){
   jQuery('#ID_LOADING').css('display','block');
   
	  if(navigator.geolocation){
	    navigator.geolocation.getCurrentPosition(showLocation);
	  } else {
	    document.write("No location available");
	  }
 
 	  function showLocation(position){
		    jQuery.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng=" + position.coords.latitude + "," + position.coords.longitude + "&sensor=true", function(response){
		      var geoadder = response.results[0].formatted_address ;  
		      var inYubin = geoadder.search("〒");    // 見つからない場合は -1が帰るので
          		       
          if (geoadder.indexOf(' ') != -1) {
              dr = ' ';
          }else if(geoadder.indexOf('、') != -1){
              dr = '、';
          }
              var kenshiku = geoadder.split(dr);
             // console.log(kenshiku[0]);
             // kenshiku = kenshiku[1].split(' ');
            if(kenshiku[1].indexOf('県')!= -1){
                left_num = kenshiku[1].indexOf('県')
            }else if(kenshiku[1].indexOf('都')!= -1){
                left_num = kenshiku[1].indexOf('都')
            }else if(kenshiku[1].indexOf('府')!= -1){
                left_num = kenshiku[1].indexOf('府');
            }else if(kenshiku[1].indexOf('都')!= -1){
                left_num = kenshiku[1].indexOf('都')
            }else if(kenshiku[1].indexOf('道')!= -1){
                left_num = kenshiku[1].indexOf('道')
            }
             left_num++;
             var pref=kenshiku[1].substring(0, left_num);
             //console.log(pref);     
    
                var shiku=kenshiku[1].substring(left_num,20); 
                if(shiku.lastIndexOf("区")!= -1){
                    left_num= shiku.lastIndexOf("区");
                }else if(shiku.lastIndexOf("市")!= -1){
                    left_num=shiku.lastIndexOf("市");
                }else if(shiku.lastIndexOf("町")!= -1){
                    left_num=shiku.lastIndexOf("町");
                }else if (shiku.lastIndexOf("村")!= -1) { // 含まれなければ-1
                    left_num=shiku.lastIndexOf("村");
                }
                left_num++;
                var shiku=shiku.substring(0, left_num);
              //  console.log(shiku);
            
		       //ここから県 市区のローマ字を取得するAjax実行  
            // POSTメソッドで送るデータを定義します var data = {パラメータ名 : 値};
            var baitai = jQuery('[name="baitai"]').val();
              var data = {'pref':pref,'shiku':shiku, 'baitai':baitai};
                // * @param data  : サーバに送信する値
              jQuery.ajax({
                type: "POST",
                url: "<?=$pdir9?>/send_prefshiku.php",
                data: data,
              }).done(function(data, dataType) {
                // PHPから返ってきたデータの表示
                if( data =="") data ="登録されている地域はありません";
                 jQuery('form[name="fmz"]').attr('action', data);  // water/area/tokyo/adachi/
        
          setTimeout(function(){
                var hhref=  data.toString();
              	window.location.href = hhref ;
          },1500);
              }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                alert('Error : 正確な住所が取得できませんでした' );
              });
             return false;
		        
		    });
		  }
	};
	
    // 探すボタン
    jQuery(function(){
      jQuery("#btn_fmz").on("click", function() {
        
      var zio_code= jQuery('#ID_CODE').val();   // 書式のチェック
      if( !zipCheck(zio_code)) return false;  // bool値
  
        
      });
    });
      
  
    jQuery(function(){
      jQuery("#first_label").on("click", function() {
          jQuery('#area dl').show(400);
          jQuery('#second_label').show(400);
          jQuery('#first_label').hide(400);
      });
      jQuery("#second_label").on("click", function() {
          jQuery('#area dl').hide(400);
          jQuery('#second_label').hide(400);
          jQuery('#first_label').show(400);
          jQuery('#area dl').remove();
      });
    });	
    
    
  
</script>
<?php    
}
 //ショートコードを登録
add_shortcode('zipSearch', 'zipSearch' );

