<?php
// info
$ftp_server = "172.25.1.101"; //FTP server addressen
$ftp_user = "ftpuser"; // Spesifiser brukenavnet til FTP server
$ftp_pass = "IMKuben1337!"; // Spesifiserer password til FTP server
$file = "path/to/WolfensteinEnemyTerritory"; //placeholder
// Set up a connection
$conn_id = ftp_connect($ftp_server);

// Login
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    // Change to the correct directory
    ftp_chdir($conn_id, dirname($file));

    // Download the file
    $temp = tmpfile();
    if (ftp_fget($conn_id, $temp, basename($file), FTP_BINARY, 0)) {
        // Output the file
        header("Content-Disposition: attachment; filename=\"" . basename($file) . "\""); //filnav skal hit
        header("Content-Type: application/octet-stream");
        header("Content-Length: " . filesize($file));
        fpassthru($temp);
    } else {
        echo "Error: Could not download file";
    }

    // Close the connection
    ftp_close($conn_id);
} else {
    echo "Error: Could not connect to FTP server";
}
?>