<?php
session_start();
?>
<body>
  <?php include 'menu.html';?>
  <?php include 'mysql_connect.php';?>
  <p>
  	<?php
    //try to retrive data from database
  	$stmt = $conn->prepare("select * from user where username=?");
  	$stmt->bind_param('s',$username);
  	$username= $_POST["username"];
  	$stmt->execute();
  	$result = $stmt->get_result();
    //see if any account was found
  	if($result->num_rows == 0){
  	  print('No account found');
  	}
  	else{
  	  $row = $result->fetch_assoc();
      //verify password
  	  if(password_verify($_POST['password'], $row['password'])){
  	  	print('Hi '.$row['username'].', you are logged in.');
        $_SESSION['username'] = $row['username'];
  	  }
  	  else{
  	  	print("Password is not correct!");
  	  }
  	}
  	?>
  </p>

</body>