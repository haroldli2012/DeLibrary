$("[href='search.php']").addClass("active");

//use setTimeout and clearTimeout to control the search frequency
var m=0;
var relay = "";
function searchajax(event) {
  var n = event.timeStamp;
  var t=n-m;
  if(t<3000) {
     if(relay != "") clearTimeout(relay);
     relay = setTimeout(search, 3000); 
  }
  m=n;
}

function search() {
  var elem = $("#trysearch").val();
  if(elem.length>0) {
      $(".search_result").load("/lib/searchphp.php",
       {
        seek:elem
       }
      );
  }
}


$(document).ready(function(){
  

    var city=getCookie("city");
    var user=getCookie("user");
    var s=$("#trysearch").val();

    //jump from index page to search page, else default seach nanjing
    if(s!="") {
      $(".search_result").load("/lib/searchphp.php",
       {
        seek:s
       }
      );    
    }
    else {
      $(".search_result").load("/lib/searchphp.php",
       {
        seek:"职场"
       }
      );
      $("#trysearch").val("职场");
   }


    if(user!="") {
       
    }

   // Get the modal
   var modal = document.getElementById('myModal');

   // Get the <span> element that closes the modal
   var span = document.getElementsByClassName("close")[0];

   // When the user clicks on <span> (x), close the modal
   span.onclick = function() { 
      modal.style.display = "none";
   }
  
    var modalImg = document.getElementById("img01");
       $(".search_result").on("click","img",function(){
          modal.style.display = "block";
          modalImg.src = this.src;
          modalImg.title=this.title;
       });

    //choose the key words by hint and show search result, then hide hint
    $(".search-hint").on("click","p",function(){
        if(relay != "") clearTimeout(relay);
        var c = $(this).text();
        $("#trysearch").val(c);
        $(".search_result").load("/lib/searchphp.php",{seek: c});
        //$(".search-hint").hide();
    });

    //hide search hint
    $("body").click(function(){
        $(".search-hint").hide();
    });

    $("#gosearch").click(function(){
     if(relay != "") clearTimeout(relay);
      search();
    });

  $("#trysearch").on("keyup",function(e){
      if(e.keyCode == 13) {
         if(relay != "") clearTimeout(relay);
         search();
         $(".search-hint").hide();
       }
      else {
         searchhint($("#trysearch").val());
       }
   });

});




function searchhint(elem) {
   $(".search-hint").load("/lib/searchhint.php",{hintby:elem},function(txt,sta,xhr){
     if(txt.length>8) $(".search-hint").show();
     else $(".search-hint").hide();
   });

}

