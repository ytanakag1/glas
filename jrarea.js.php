<script>

jQuery(function() {
  for(var i=0 ;i<5;++i){  // ページにアンカーを付け足す
    var hf= jQuery('.d001p a').eq(i).attr('href');
    jQuery('.d001p a').eq(i).attr('href',hf+'#minutes')
  }
    if(jQuery('#search_clumb').length){ //  あれば ,検索結果までスクロール 
      jQuery("html,body").animate({scrollTop:jQuery('#search_clumb').offset().top},900,'swing');
    }
}); 

//幅取得
jQuery(function() {
  var path = location.pathname ;
  var sw = jQuery('.search_wrap');
   if(sw.width() <900 && path.match(/area/) ){
     jQuery('#small-map').addClass('under900');
     jQuery('#seko_search').addClass('under900');
   }
});  

jQuery(function() {
  jQuery('dd input[name="pref"]').change(function(){
    var oyadd= jQuery(this).parents('dd');
       jQuery('.shiku').remove();
      oyadd.append("<a class='shiku' onclick='sarani()'>&gt;&gt;さらに絞り込む</a>"); 
  });
});



    /*** さらに絞り込むクリック  */
   function sarani(){
     var elem=jQuery('dd input[name="pref"]:checked');
     var tr= elem.attr('data-tr');
      // POSTメソッドで送るデータを定義します var data = {パラメータ名 : 値};
      var data = {'request' : elem.val(),'trb':tr};
        // * @param data  : サーバに送信する値

      jQuery.ajax({
        type: "POST",
        url: "<?=$pdir9?>/send.php",
        data: data,
      }).done(function(data, dataType) {
        // PHPから返ってきたデータの表示
        if( data =="") data ="登録されている地域はありません";
        jQuery('.shiku').parents('dd').append('<div id="shiku_button">'+ data +'</div>');
      }).fail(function(XMLHttpRequest, textStatus, errorThrown) {

        alert('Error : ' + errorThrown);
      });
      
      return false;
  }
 
 
 
 jQuery(document).click(function(event) {
  if(!jQuery(event.target).closest('#shiku_button').length) {
     jQuery('#shiku_button').remove();
  }
 }); 
  
 </script>
 