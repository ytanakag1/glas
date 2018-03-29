<?php
// top に埋め込む用の検索窓
function kijiKensaku($trb){
  $tr = $trb[0]!='top'? $trb[0] :'' ;
  $cate_arr=array('water'=>33,'glas'=>35,'key'=>34);
  
?>


  
<div class="kiwado_wrap">
  <h3>キーワードで検索</h3>
  <div class="kiwado">
  	<form method="get" action="<?php bloginfo( 'url' ); ?>">
      <input name="s" id="s" type="text" placeholder=" キーワードを入力"/>
      <button id="submit" type="submit">
        <span class="sbico Cws1Yc tdD53">
          <svg focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
          </svg></span>
      </button>  
  <?php if($tr != 'top')  
    echo "<input type='hidden' name='cat' value='{$cate_arr[$tr]}'>";
  ?>  
    </form>
 <script>
   //<select name="cat" id="cat" class="postform">
   //<option class="level-0" value="33">水回り情報集</option>
   
 </script>
 


	</div>
</div>


<?php 
} 
 //ショートコードを登録
add_shortcode('kijiKensaku', 'kijiKensaku' );

