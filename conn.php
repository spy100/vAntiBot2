<?php
//start edit

//START Connect to database
$link = mysqli_connect("localhost", "user", "passw", "vAntibot");
//stop edit

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

//set charset
if (!mysqli_set_charset($link, "utf8")) {
      printf("Error loading character set utf8: %s\n", mysqli_error($link));
      exit();
}



?>
