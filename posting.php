<?php

	// DBMS connection code -> hostname, username, password, database name
	$db = mysqli_connect('localhost', 'root', '', 'kowtsi_db');

    //Kinukuha yung result sa database
    $result = mysqli_query($db, "SELECT quotes.postID, quotes.text, quotes.userID, quotes. dateAndTime, COUNT(CASE WHEN liketable.like_status = 'like' THEN 1 END) AS like_count, COUNT(CASE WHEN liketable.like_status = 'dislike' THEN 1 END) AS dislike_count
      FROM quotes
      LEFT JOIN `liketable` ON quotes.postID = liketable.postID
      GROUP BY quotes.postID, quotes.text, quotes.userID, quotes. dateAndTime
      ORDER BY dateAndTime DESC;");

    //Kinukuha ung bawat row at nireresult based dun sa nakuha
    while ($row = mysqli_fetch_assoc($result))
    {
        echo "<div class = 'test'>";
        echo "<div class = 'athr_contain'>";
        echo "<h2 class = 'author'>Anonymous". "</h2>";
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


	
