<?php

  session_start();
  // Grab the input from the HTML form
  $email = $_POST['email'];
  $password = $_POST['password'];

  //Prepare SQL statement
  $dbname = "root";
  $dbuser = "localhost";
  $dbpassword = "";
  $dbtable = "user";

  //Connect to database
  $conn = mysqli_connect($dbuser, $dbname, $dbpassword, $dbtable);

  //Check connection
  if ($conn-> connect_error) 
  {
    die("\nConnection failed!: " . $conn->connect_error);
  }

  else
  {
    $stmt = $conn -> prepare("SELECT * FROM `data` WHERE email = ?");
    //Yung ? sa taas ay dito ata ginagamit sa $email
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt_result = $stmt->get_result();

    if ($stmt_result -> num_rows > 0)
    {
      $data = $stmt_result -> fetch_assoc();

      //Check if the password on the database is the same on MySQL
      if ($data['Password'] === $password)
      {
        header('Location: index.html');
        exit;
      }

      else
      {
        //Possible na hindi mathc yung Email peor hindi yung password
        echo "<h2>Invalid Email or Password</h2>";
      }
    }

    else
    {
      //Pag hindi talaga nag eexist nag rarun
      echo "<h2>Account does not exist</h2>";
    }
  }

  $conn -> close();

  



?>