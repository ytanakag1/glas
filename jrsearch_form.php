<?php
//////検索フォーム表示関数//////////
function seko_search_form($tr){  
    global $prefs; global $fuguai; global $shojo; 
    global $pdir9;  global $prefs;
 
  $fuguai = $fuguai[$tr]; // 1元に変換
  $shojo = $shojo[$tr]; // 1元に変換
  // 地方配列
  $region = array('北海道・東北地方','関東地方','信越・北陸地方','東海地方','関西地方','中国地方','四国地方','九州・沖縄地方');

?>
<section class='search_wrap'>
 <h3>施工事例検索</h3>
  <div id="small-map">
      <img src="<?=get_home_url()?>/wp-content/uploads/2016/04/japan.png" alt="japan"/>
  </div>
  
  
  <div id="seko_search" class="article">
    <form method="get" name="fm"  action="" >
    
      <select name="fuguai" id="fuguai">
  <?php  //不具合リスト
      foreach($fuguai as $key => $val){
        if($val  == 'all') {
          $key = 'all\' selected="selected"';
          $val="すべての箇所";
        }
          echo "<option value='$key' >$val</option> " ;
      }
  ?>
      </select>
      
      <select name="shojo" id="shojo">
  <?php  //症状リスト
      foreach($shojo as $key => $val){
        if($val == 'all'){
          $key = 'all\' selected="selected"';
          $val="すべての症状";
        }
          echo "<option value='$key' >$val</option> " ;
      }
  ?>
      </select>
          <input type="submit" value="この条件で絞り込む"/>  
      
      <div id="area">
         
<?php  // 都道府県をラジオボタンで
     $k=0; $i=0;
      foreach($prefs as $key=>$val){
        if($k==1 || $k== 8 || $k==16 || $k==21 || $k==25 || $k==31 || $k==36 || $k==40 ){ 
          $region_chang = true;  //地方が変わる
        
            if($k!=1) echo "</dd></dl>"; //初回でなければ閉じタグ必要
            echo "<dl id='a", $i+1 ,"'> <dt>$region[$i]</dt><dd>";  //北海道東北
            ++$i; 
        }  
           
        $chk= $k ?'':"checked='checked'"; 
        echo "<label><span>
        <input type='radio' name='pref' value='".$key."' data-tr='".$tr."'  $chk >$key</span></label>\n";
        ++$k;
      }

?>
    </dd></dl>
   </div>   <!--area-->
  </div> <!--"seko_search"-->
</form>
<?php  include_once 'jrarea.js.php'; ?>
</section>
<?php } 
