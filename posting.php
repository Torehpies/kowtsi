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

        echo '<form action = "server.php" method = "post" id = "Upvote/Downvote">';
        echo '<button type = "submit" name =' . $row['postID'] . 'upvote' . 'id = "upvote">' . '<span class = "material-symbols-outlined">' . 'thumb_up' . '</button>' . '</span>' . $row['upvote'];
        echo '<button type = "submit" name =' . $row['postID'] . 'downvote' . 'id = "upvote">' . '<span class = "material-symbols-outlined">' . 'thumb_down' . '</button>' . '</span>' . $row['downvote'];
        echo '</form>';

        echo "</div>";
    }
?>


	