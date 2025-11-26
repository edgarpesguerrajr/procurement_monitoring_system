<?php 

$conn = new mysqli('localhost', 'root', '', 'pms_db') or die("Could not connect to mysql" . mysqli_error($con));
// ensure connection uses utf8mb4 so special characters are preserved
if ($conn && !$conn->set_charset('utf8mb4')) {
	// fallback: try utf8
	$conn->set_charset('utf8');
}
