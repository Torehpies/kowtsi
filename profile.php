<?php 
	session_start(); //starting the session, to use and store data in session variable

	//if the session variable is empty, this means the user is yet to login
	//user will be sent to 'login.php' page to allow the user to login
	if (!isset($_SESSION['username'])) {
		$_SESSION['msg'] = "You have to log in first";
		header('location: login.php');
	}

	// logout button will destroy the session, and will unset the session variables
	//user will be headed to 'login.php' after loggin out
	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
		header("location: login.php");
	}

?>
<!DOCTYPE html>
<html lang = "en">
<head>
    <title>Kowtsi | Profile</title>
    <link rel = "stylesheet" href = "profile.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="shortcut icon" href="img/kowtsi_logo.ico" />
</head>

<body>
  <header>
    <button id = "navbutton" onclick = "shata()">
      <span class="material-symbols-outlined">menu</span> 
    </button>
    <img id = "logo" src = ".\img\kowtsi_logo.ico">
  </header>
  <div class="content">
    <nav id = "urnav">
      <a href = "homepage.php"><span class="material-symbols-outlined">home</span>Home</a>
      <a href = "profile.php"><span class="material-symbols-outlined">account_circle</span>Profile</a>
      <a href = "Notif"><span class="material-symbols-outlined">notifications</span>Notifications</a>
      <a href = "homepage.php?logout='1'"><span class="material-symbols-outlined">logout</span>Logout</a>
    </nav>

    <div id = profsection>
      <div id = "dp"></div>
      <div id = "profText">
        <div id = "name"><p><?php echo $_SESSION['username']; ?></p></div>
        <div id = "aboutuser"></div>
      </div>
      
    </div>
      <div id = postsection>
      <div id = "posting"></div>
    </div>

    <script src = "Profpg.js"></script>
  </div>
</body>

</html>
