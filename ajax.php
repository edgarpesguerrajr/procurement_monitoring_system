<?php
ob_start();
date_default_timezone_set("Asia/Manila");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
    $login = $crud->login();
    if($login)
        echo $login;
}
if($action == 'login2'){
    $login = $crud->login2();
    if($login)
        echo $login;
}
if($action == 'logout'){
    $logout = $crud->logout();
    if($logout)
        echo $logout;
}
if($action == 'signup'){
    $save = $crud->signup();
    if($save)
        echo $save;
}
if($action == 'save_user'){
    $save = $crud->save_user();
    if($save)
        echo $save;
}
if($action == 'update_user'){
    $save = $crud->update_user();
    if($save)
        echo $save;
}
if($action == 'delete_user'){
    $save = $crud->delete_user();
    if($save)
        echo $save;
}
if($action == 'save_project'){
    $save = $crud->save_project();
    // Always echo the response so the client receives error strings or 0 on failure
    echo $save;
}
if($action == 'delete_project'){
    $save = $crud->delete_project();
    echo $save;
}
if($action == 'save_progress'){
    $save = $crud->save_progress();
    if($save)
        echo $save;
}
if($action == 'delete_progress'){
    $save = $crud->delete_progress();
    if($save)
        echo $save;
}
if($action == 'save_comment'){
    $save = $crud->save_comment();
    echo $save;
}
if($action == 'delete_comment'){
    $save = $crud->delete_comment();
    echo $save;
}
if($action == 'mark_notifications_read'){
    $save = $crud->mark_notifications_read();
    if($save) echo $save;
}
if($action == 'get_report'){
    $get = $crud->get_report();
    if($get)
        echo $get;
}
ob_end_flush();
?>
