<?php
session_start();
?>
  <?php include 'mysql_connect.php';?>
  <?php
    //try to get userid
  	$stmt = $conn->prepare("select * from user where username = ?");
  	$stmt->bind_param("s",$username);
  	$username = $_SESSION['username'];
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $userid = $row['id'];

    //try to insert the post into database
    $stmt = $conn->prepare("insert into post(content, creator, title) values (?, ?, ?)");
    $stmt->bind_param("sis", $content,$creator,$title);
    $content = $_GET['content'];
    $creator = $userid;
    $title = $_GET['title'];
    $stmt->execute();

      //retrive all the post from database
    $stmt = $conn->prepare("select * from post");
    $stmt->execute();
    $result = $stmt->get_result();

    $places = array();
    while ($row = $result->fetch_assoc()) {
      $userid= $row['creator'];
      $stmt2 = $conn->prepare("select username from user where id = ?");
      $stmt2->bind_param("i",$userid);
      $stmt2->execute();
      $result2 = $stmt2->get_result();
      $row2 = $result2->fetch_assoc();
      array_push($places, $row['title'],$row['content'],$row2['username']);
    }
    print(json_encode($places));
 

  

