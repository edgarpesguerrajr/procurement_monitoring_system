<?php
include 'db_connect.php';
// expects POST project_id
if(!isset($_POST['project_id'])){
    echo json_encode([]);
    exit;
}
$project_id = intval($_POST['project_id']);
$data = [];
$qry = $conn->query("SELECT c.*, concat(u.firstname,' ',u.lastname) as user FROM comments c LEFT JOIN users u ON u.id = c.user_id WHERE c.project_id = $project_id ORDER BY c.id ASC");
while($row = $qry->fetch_assoc()){
    $row['date_created'] = date("M d, Y H:i", strtotime($row['date_created']));
    $data[] = $row;
}
echo json_encode($data);
exit;
