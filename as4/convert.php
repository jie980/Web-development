<?php include 'mysql_connect.php';?>
<?php
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
?>