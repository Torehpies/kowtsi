<?php

	// DBMS connection code -> hostname, username, password, database name
	$db = mysqli_connect('localhost', 'root', '', 'kowtsi_db');

    //Kinukuha yung result sa database
    $result = mysqli_query($db, "SELECT * FROM quotes");

    //Kinukuha ung bawat row at nireresult based dun sa nakuha
    while ($row = mysqli_fetch_assoc($result))
    {
        echo "<div class = 'test'>";
        echo "<h2 class = 'author'>" . $row['userID'] . $row['dateAndTime'] . "</h2>";
        echo "<h2>" . $row['text'] . "</h2>";
        echo "<p>" . "Like " . $row['upvote'] . " Dislike " . $row['downvote'] . "</p>";
        echo "</div>";
    }
?>


	