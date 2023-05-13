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
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
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
      <div id = "dp">
        <?php
          // DBMS connection code -> hostname, username, password, database name
          $db = mysqli_connect('localhost', 'root', '', 'kowtsi_db');

          $username = $_SESSION['username'];

          //Kinukuha yung result sa database
          $query = "SELECT * FROM user_credentials WHERE username = '$username'";
          $result = mysqli_query($db, $query);

          $photo = "";

          if ($result -> num_rows > 0)
          {
            while ($row = mysqli_fetch_assoc($result))
            {
              $photo = $row['photo'];
            }
          }

          echo "<img src = pictures/" . $photo. " alt = Profile Picture id = profPic '>";
        ?>

      </div>
      <div id = "profText">
        <div id = "name"><p><?php echo $_SESSION['username']; ?></p></div>

        <div id = "aboutuser">

          <?php
            // DBMS connection code -> hostname, username, password, database name
            $db = mysqli_connect('localhost', 'root', '', 'kowtsi_db');

            $username = $_SESSION['username'];

            //Kinukuha yung result sa database
            $query = "SELECT * FROM user_credentials WHERE username = '$username'";
            $result = mysqli_query($db, $query);
            $email = "";
            $date = "";

            if ($result -> num_rows > 0)
            {
              while ($row = mysqli_fetch_assoc($result))
              {
                $email = $row['email'];
                $date = $row['register_date'];
              }
            }

            echo "<p>" . "Date Joined: " . $date . "</p>";
            echo "<p>" . "Email: " . $email . "</p>";

          ?>


        </div>
      </div>
      
    </div>
      <div id = postsection>
      <div id = "posting">
        <?php
          // DBMS connection code -> hostname, username, password, database name
          $db = mysqli_connect('localhost', 'root', '', 'kowtsi_db');

          

          //Kinukuha yung result sa database
          $result = mysqli_query($db, "SELECT * FROM quotes WHERE userID = '" . $_SESSION['userID'] . "' ORDER BY dateAndTime DESC");

          if ($result -> num_rows == 0)
          {
            echo "<div class = 'test'>";
            echo "<div class = 'athr_contain'>";
            echo "<h2 class = 'author'>" . $_SESSION['username'] . "</h2>";
            echo "<p class = 'datentime'>" . "No posts yet" . "</p>";
            echo "</div>";
            echo "<h2 class = 'text'>" . "No posts yet" . "</h2>";
          }

          $query = "SELECT * FROM quotes WHERE userID = ' $_SESSION['userID'] 'ORDER BY dateAndTime DESC;
                    SELECT quotes.postID, quotes.text, quotes.userID, quotes. dateAndTime, COUNT(CASE WHEN liketable.like_status = 'like' THEN 1 END) 
                    AS like_count, COUNT(CASE WHEN liketable.like_status = 'dislike' THEN 1 END) AS dislike_count;";


          $result1 = mysqli_query($db, "SELECT quotes.postID, quotes.text, quotes.userID, quotes. dateAndTime, COUNT(CASE WHEN liketable.like_status = 'like' THEN 1 END) AS like_count, COUNT(CASE WHEN liketable.like_status = 'dislike' THEN 1 END) AS dislike_count
          FROM quotes
          LEFT JOIN `liketable` ON quotes.postID = liketable.postID
          GROUP BY quotes.postID, quotes.text, quotes.userID, quotes. dateAndTime
          ORDER BY dateAndTime DESC;");

          //Kinukuha ung bawat row at nireresult based dun sa nakuha
          while ($row = mysqli_fetch_assoc($result))
          {
              echo "<div class = 'test'>";
              echo "<div class = 'athr_contain'>";
              echo "<h2 class = 'author'>" . $row['userID'] . "</h2>";
              echo "<p class = 'datentime'>" . $row['dateAndTime'] . "</p>";
              echo "</div>";
              echo "<h2 class = 'text'>" . $row['text'] . "</h2>";

              echo '<div class = "likeanddis_contain">';
              echo '<form action = "server.php" method = "post" id = "Upvote/Downvote">';
              echo $row['like_count'] . '<button type = "submit" name =' . $row['postID'] . 'upvote' . ' class = "up_vote">' . '<span class = "material-symbols-outlined">' . 'thumb_up' . '</button>' . '</span>';
              echo '<button type = "submit" name =' . $row['postID'] . 'downvote' . ' class = "down_vote">' . '<span class = "material-symbols-outlined">' . 'thumb_down' . '</button>' . '</span>' . $row['dislike_count'];
              echo '</form>';
              echo '</div>';
              echo "</div>"; 
          }

        ?>

      </div>
    </div>

    <script src = "Profpg.js"></script>
  </div>
</body>

</html>
