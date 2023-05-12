<?php 
	session_start(); // starting the session, necessary for using session variables

	// declaring and hoisting the variables
	$username = "";
	$email    = "";
	$text = "";
	$errors = array(); 
	$post_date = "";
	$_SESSION['success'] = "";

	// DBMS connection code -> hostname, username, password, database name
	$db = mysqli_connect('localhost', 'root', '', 'kowtsi_db');

	// registration code
	if (isset($_POST['reg_user'])) {

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
				$query = "INSERT INTO user_credentials(username, email, password, register_date) 
						VALUES('$username', '$email', '$password', '$register_date')"; //inserting data into table
				mysqli_query($db, $query);

				$make_table = "CREATE TABLE $username (
					postID VARCHAR(30) NOT NULL,
					text VARCHAR(1000) NOT NULL,
					dateAndTime VARCHAR(50) NOT NULL,
					upvote INT(6) UNSIGNED DEFAULT 0,
					downvotevote INT(6) UNSIGNED DEFAULT 0
					)";

				$query = mysqli_query($db, $make_table);

				//storing username of the logged in user, in the session variable
				$_SESSION['username'] = $username;
				$_SESSION['success'] = "You have logged in"; //welcome message
				header('location: homepage.php'); 
				//page on which the user will be redirected after logging in
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
				$_SESSION['username'] = $username; //storing username in session variable
				$_SESSION['success'] = "You have logged in!"; //welcome message
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
		
		$username = $_SESSION['username'];
		$query = "INSERT INTO quotes (userID, text, dateAndTime) VALUES ('$username', '$text', '$post_date')";
		mysqli_query($db, $query);

		//Query uli para dun sa table natin sa user
		$table_query = "INSERT INTO $username (postID, text, dateAndTime) VALUES ('$username', '$text', '$post_date')";
		mysqli_query($db, $table_query);
		

		header('location: homepage.php'); 
	}

	if (isset($_SESSION['success']))
	{
		// DBMS connection code -> hostname, username, password, database name
		$db = mysqli_connect('localhost', 'root', '', 'kowtsi_db');

		//Kinukuha yung result sa database
		$result = mysqli_query($db, "SELECT * FROM quotes");

		$posts = array();

		

		while ($row = mysqli_fetch_array($result)) {
			$posts[] = $row['postID'];
		}

		for ($i = 0; $i <= sizeof($posts); $i++)
		{
			if (isset($_POST[$posts[$i] . 'id']))
			{
				$query = "UPDATE quotes SET upvote = upvote + 1 WHERE postID = '$posts[$i]'";
				mysqli_query($db, $query);
				header('location: homepage.php');
			}
		}
	}

	else
	{
		header('location: login.php');
	}

	
    
	

?>
