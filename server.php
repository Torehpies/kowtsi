<?php 
	session_start(); // starting the session, necessary for using session variables

	// declaring and hoisting the variables
	$username = "";
    $userID = ""; 
    $email    = "";
    $register_date = "";
	$text = "";
	$errors = array(); 
    $post_date = "";
    $row = "";

	// DBMS connection code -> hostname, username, password, database name
	$db = mysqli_connect('localhost', 'root', '', 'kowtsi_db');

	// registration code
	if (isset($_POST['reg_user'])) {

		$file = $_FILES['picture'];
		$fileName = $_FILES['picture']['name'];
		$fileError = $_FILES['picture']['error'];
		$fileSize = $_FILES['picture']['size'];
		$fileTmpName = $_FILES['picture']['tmp_name'];

		$fileExt = explode('.' , $fileName);
		$fileActualExt = strtolower(end($fileExt));
		$allowed = array('jpg', 'jpeg', 'png');

		//Only allow the allowed
		if (in_array($fileActualExt, $allowed))
		{
			if ($fileError === 0)
			{
				if ($fileSize < 1000000)
				{
					$fileNameNew = uniqid('', true).".".$fileActualExt;
					$fileDestination = 'pictures/'.$fileName;
					move_uploaded_file($fileTmpName, $fileDestination);
				}

				else
				{
					array_push($errors, "Your file is too big!");
				}
			}

		}

		else
		{
			array_push($errors, "Invalid file type!");
		}



		// receiving the values entered and storing in the variables
		//data sanitization is done to prevent SQL injections
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
		$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

		// ensuring that the user has not left any input field blank
		// error messages will be displayed for every blank input
		if (empty($username)) { array_push($errors, "Username is required"); }
		if (empty($email)) { array_push($errors, "Email is required"); }
		if (empty($password_1)) { array_push($errors, "Password is required"); }
		if (empty($_POST['password_2'])) { array_push($errors, "Confirm Password is required"); }

		if ($password_1 != $password_2) {
			array_push($errors, "The two passwords do not match");				
			//checking if the passwords match
		}

		//Check niya yung database natin gamit yung email na variable sa itaas
		//Tapos mag-pprepare ng query para sa database para icheck
		$sql = "SELECT * FROM user_credentials WHERE email = '$email'";
		$result = $db -> query($sql);

		$check_username = "SELECT * FROM user_credentials WHERE username = '$username'";
        $result2 = $db -> query($check_username);

		if ($result -> num_rows > 0)
		{
			//Mag eexecute to pag nag eexist na yung email within the database
			//Go back to login page
			array_push($errors, "Email already exists!");
		} 
		
		elseif ($result2 -> num_rows > 0) 
        {
            array_push($errors, "Username already exists!");
        }

		else
		{
			// if the form is error free, then register the user
          if (count($errors) == 0) {
                $register_date = date("Y-m-d");
				$password = md5($password_1);//password encryption to increase data security
				
				$query = "INSERT INTO user_credentials(username, email, password, register_date, photo) 
						VALUES('$username', '$email', '$password', '$register_date', '$fileName')"; //inserting data into table
				mysqli_query($db, $query);
                
				//storing username of the logged in user, in the session variable
				$_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                
                //Getting user data to store in session variables
                $userDataQuery = "SELECT * FROM user_credentials WHERE username='$username'";
             	$userDataResults = mysqli_query($db, $userDataQuery);   
                
                while ($row = mysqli_fetch_assoc($userDataResults)) {
                  // Access row data
                  $userID = $row['userID'] ;
                }

                $_SESSION['userID'] = $userID; 

				//page on which the user will be redirected after logging in
  				header('location: homepage.php'); 

			}
		}
	}

	// user login
	if (isset($_POST['login_user'])) {
		//data sanitization to prevent SQL injection
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		//error message if the input field is left blank
		if (empty($username)) {
			array_push($errors, "Username is required");
		}
		if (empty($password)) {
			array_push($errors, "Password is required");
		}

		//checking for the errors
		if (count($errors) == 0) {
			$password = md5($password); //password matching
			$query = "SELECT * FROM user_credentials WHERE username='$username' AND password='$password'";
			$results = mysqli_query($db, $query);

			// $results = 1 means that one user with the entered username exists
			if (mysqli_num_rows($results) == 1) {
              while ($row = mysqli_fetch_assoc($results)) {
              // Access row data
              $userID= $row['userID'] ;
              $email = $row['email'];
              }

              $_SESSION['username'] = $username;
              $_SESSION['userID'] = $userID;
              $_SESSION['email'] = $email;

              header('location: homepage.php'); //page on which the user is sent to after logging in
			}else {
				array_push($errors, "Username or password incorrect"); 
				//if the username and password doesn't match
			}
		}
	}

	if (isset($_POST['post_user'])) {
		$text = mysqli_real_escape_string($db, $_POST['post']);
		date_default_timezone_set('Asia/Manila');
		$post_date = date("Y-m-d h:i A");
		//Dito iniistore yung variable na name sa session
		
        $userID = $_SESSION['userID'];

		$username = $_SESSION['username'];
		$query = "INSERT INTO quotes (userID, text, dateAndTime) VALUES ('$userID', '$text', '$post_date')";
		mysqli_query($db, $query);

		header('location: homepage.php');
    }

	

    

    $result = mysqli_query($db, "SELECT * FROM quotes");
	$posts = array();

	while ($row = mysqli_fetch_array($result)) {
		$posts[] = $row['postID'];
	}
    
	for ($i = 0; $i < count($posts); $i++)
	{
		//Kinukuha yung result sa database
		if (isset($_POST[$posts[$i] . 'upvote'])) 
		{
			if (session_status() == PHP_SESSION_ACTIVE){
				$userID = $_SESSION['userID'];
			  }
			  else{
				$userID = "";
			  }

          $likeDataResult = mysqli_query($db, "SELECT * FROM liketable WHERE postID = '$posts[$i]' AND userID = '$userID' AND like_status = 'like'");
          if (mysqli_num_rows($likeDataResult) == 1) {
            $deleteLike = mysqli_query($db, "DELETE FROM liketable WHERE postID = '$posts[$i]' AND userID = '$userID' AND like_status = 'like'");
            header('location: homepage.php');
          }
          else{
            $query = "INSERT INTO liketable (postID, userID, like_status) VALUES ('$posts[$i]', '$userID', 'like')";
            mysqli_query($db, $query);
            header('location: homepage.php');
          }
        }

		if (isset($_POST[$posts[$i] . 'downvote']))
		{
			if (session_status() == PHP_SESSION_ACTIVE){
				$userID = $_SESSION['userID'];
			  }
			  else{
				$userID = "";
			  }
			  
          $likeDataResult = mysqli_query($db, "SELECT * FROM liketable WHERE postID = '$posts[$i]' AND userID = '$userID' AND like_status = 'dislike'");
          if (mysqli_num_rows($likeDataResult) == 1) {
            $deleteLike = mysqli_query($db, "DELETE FROM liketable WHERE postID = '$posts[$i]' AND userID = '$userID' AND like_status = 'dislike'");
            header('location: homepage.php');
          }
          else{
            $query = "INSERT INTO liketable (postID, userID, like_status) VALUES ('$posts[$i]', '$userID', 'dislike')";
            mysqli_query($db, $query);
            header('location: homepage.php');
          }
		}
      }
?>
