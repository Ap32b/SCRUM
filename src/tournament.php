<?php
session_start();
include("DB_connect.php");

// Check if user is logged in
if(!isset($_SESSION['userID'])) {
    echo "You must be logged in to join the LAN party.";
    exit;
}

// Check if form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['join'])) {
        // User wants to join the LAN party
        $query = "UPDATE users SET enter = 1 WHERE userID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $_SESSION['userID']);
        $stmt->execute();

         // Set a session variable to show that the user is participating
         $_SESSION['participating'] = true;
        } elseif(isset($_POST['leave'])) {
            // User wants to leave the LAN party
            $query = "UPDATE users SET enter = 0 WHERE userID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $_SESSION['userID']);
            $stmt->execute();
    
            // Set a session variable to show that the user is not participating
            $_SESSION['notParticipating'] = true;
    }
}


// Check if user is participating
if(isset($_SESSION['participating'])) {
    echo "You have joined the Wolfenstein LAN.";
    // Unset the session variable so the message is only shown once
    unset($_SESSION['participating']);
}

if(isset($_SESSION['notParticipating'])) {
    echo "You are not joining the LAN.";
    // Unset the session variable so the message is only shown once
    unset($_SESSION['notParticipating']);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Wolfenstein LAN</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
    </head>
<body>
    <h1>Wolfenstein LAN Party</h1>
    <?php
    // Check if user is admin
    $query = "SELECT admin FROM users WHERE userID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_SESSION['userID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if($user['admin'] == 1) {
        // User is admin, show all users who are participating
        $query = "SELECT username FROM users WHERE enter = 1";
        $result = $conn->query($query);
        echo "Users participating in the LAN party:<br>";
        while($row = $result->fetch_assoc()) {
            echo $row['username'] . " is participating.<br>";
        }
    }
    ?>
    <p>Join the LAN party by clicking the button below.</p>
    <form method="post">
        <input type="submit" name="join" value="Join LAN Party">
        <input type="submit" name="leave" value="Leave LAN Party">
    </form>
    <p> Download Wolfenstein enemy territory here: <a href="download.php">Download</a></p>
    <a id="logOut" href="log_out.php">Log Out</a>       
</body>
</html>