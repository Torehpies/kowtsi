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
    <title>Kowtsi |  Home</title>
    <link rel = "stylesheet" href = "homepage.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="shortcut icon" href="img/kowtsi_logo.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
</head>

<body>
  <header>
    <?php  if (isset($_SESSION['username'])) : ?>
    <button id = "navbutton" onclick = "shata()">
      <span class="material-symbols-outlined">menu</span> 
    </button>
    <img id = "logo" src = ".\img\kowtsi_logo.ico">
    <!-- Dito yung display name -->
    <div class="name"><p><?php echo $_SESSION['username']; ?> </p></div>
    <?php endif ?>
  </header>
  <div class="content">
    <nav id = "urnav">
      <a href = "homepage.php"><span class="material-symbols-outlined">home</span>Home</a>
      <a href = "profile.php"><span class="material-symbols-outlined">account_circle</span>Profile</a>
      <a href = "homepage.php?logout='1'"><span class="material-symbols-outlined">logout</span>Logout</a>
    </nav>

    <div id = uploadSection>
      <form action = "server.php" method = "post" id = "postForm">
        <input id = "postText" name = "post" type = "text" placeholder = "Write some quotes" required>
        <button type = "submit" name = "post_user" id = "postButton">Post</button>
      </form>
    </div>

    <div id = postSection>
      <div id = "posting">
        <?php include('posting.php'); ?>
      </div>
    </div>

    <script src = "Profpg.js"></script>
  </div>
</body>

</html>
