
<body>
  <?php include 'menu.html';?>
  <?php include 'mysql_connect.php';?>
  <p>
  <?php
    //verify if username already exist or not
    $stmt = $conn->prepare("select * from user where username=?");
    $stmt->bind_param("s", $username);
    $username = $_POST["username"];
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
      print("Account already exist! please try again");
    }
    //if do not exit, create this username.
    else{
      $stmt = $conn->prepare("insert into user(username, password) values (?, ?)");
      $stmt->bind_param("ss", $username, $hashedPassword);
      $username = $_POST["username"];
      $password = $_POST['password'];
      //encode the password in database
      $hashedPassword = password_hash($password,PASSWORD_DEFAULT);
      if($username == ''){
      	print('Username could not be empty! please try again');
      }
      else if($password == ''){
       print('password could not be empty! please try again');
      } 
      else{
        $stmt->execute();
        print('Signup successful');
      }
    }
  ?>
  </p>
</body>