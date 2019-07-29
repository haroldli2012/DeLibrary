$("[href='index.php']").addClass("active");

  var elem = $("#trysearch").val();

      $(".search_result").load("/lib/searchphp.php",
       {
        seek:elem
       }
      );

