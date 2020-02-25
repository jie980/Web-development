<?php
session_start();
?>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <style>
    div.eachpost{
      border: 2px solid #000;
      padding-left: 10px
     
    }
    div.empty{
      height: 20px;
    }
  </style>
</head>
<body>
  
  <?php include 'mysql_connect.php';?><br/>
  <?php include 'menu.html';?>
  <h1> Posts </h1>
  <div>
  	<?php
    // check if $_SESSION['student_id'] is declared
    // if it is, then the user is logged in
    if (isset($_SESSION['username'])) {
   
      echo'<b>Create a post as '.$_SESSION['username']."</b>";
      echo'
        <p id="post"></p>
        <p id="success"></p>


        <h>Title</h><input type="text" id="title" ><br/>
        Content<br/>
        <textarea id="comment" rows="20" cols="80"></textarea><br/>
        <p id="empty"></p>
        <button onclick="post()">submit</button>
        ';
    }
    else {
      echo'<p id="post"></p>';
      print('You need to log in make a post');
    }
    ?>
  </div>

  <script>
  function post(){
    var newtitle =$("#title").val();
    var newcontent =$("#comment").val();
    var user =$("#username").val();
    if(newtitle==null || newtitle==''){
      $("#empty").html("Title cannot be empty").css("color","red");
    }
    else if(newcontent==null || newcontent==''){
      $("#empty").html("Content cannot be empty").css("color","red");
    }
    else{
      $("#empty").html("");
      $.ajax({
        method:"GET",
        url: "do_post.php",
        dataType: "json",
        //async : false,
        data: 
        {
          title: newtitle,
          content: newcontent,
        },
      }).done(function(data){
        var str= '';
        if(data.length==0)
        {
          $("#post").html("No Posts Available");
        }
        else
       {
          for (var i=0;i< data.length;i+=3) {
          str += '<div class="eachpost">' +
            '<h3>Title:'+data[i]+'</h2><br/>' +
            '<p>By: '+data[i+2]+'</p><br/>'+
            '<p> '+data[i+1]+'</p><br/>' +
            '</div>'+
            '<div class="empty"></div>';
        
          }
          $("#post").html(str);     
        }
      })
    } 
  }
  //when page is loaded, load all the posts
  window.onload = function(){
    $.ajax({
      method:"GET",
      url: "convert.php",
      dataType: 'json',
      //async : false,
 
    }).done(
    function(data){
      var str= '';
      if(data.length==0)
      {
        $("#post").html("No Posts Available");
      }
      else
      {
        for (var i=0;i< data.length;i+=3) {
          str += '<div class="eachpost">' +
            '<h3>Title:'+data[i]+'</h2><br/>' +
            '<p>By: '+data[i+2]+'</p><br/>'+
            '<p> '+data[i+1]+'</p><br/>' +
            '</div>'+
            '<div class="empty"></div>';
        
        }
        $("#post").html(str);     
      }
    })
  }
  </script>
</body>

