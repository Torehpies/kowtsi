<?php
    session_start();

    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    //Prepare SQL statement
    $dbname = "root";
    $dbuser = "localhost";
    $dbpassword = "";
    $dbtable = "kowtsi_db";

    //Connect to database
    $conn = mysqli_connect($dbuser, $dbname, $dbpassword, $dbtable);

    //Check connection
    if ($conn -> connect_error) 
    {
        die("\nConnection failed!: " . $conn->connect_error);
    }

    //Check if the email already exists within the database
    $stmt = $conn -> prepare("SELECT * FROM `user_credentials` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt_result = $stmt->get_result();

    if ($stmt_result -> num_rows > 0)
    {
        echo "Email already exists!";
        exit;
        
    }

    //Check if the password and confirm password are the same
    if (strcmp($password, $confirm_password) != 0) 
    {
        //Pag hindi same yung password mag eexecute to
        header('Location: register.html');
        echo '<script>alert("Password does not match. Retry again")</script>';
        exit;
    } 

    else 
    {
        //Pop up message
        echo '<script>alert("Registration successful")</script>';
        $stmt = $conn -> prepare("INSERT INTO `user_credentials` (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();

        //Redirect to login page
        header('Location: login.html');
        exit;
    }

    $conn -> close();
?>
