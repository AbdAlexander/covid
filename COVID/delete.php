<?php 
include('userstorage.php');
include('datestorage.php');
include('auth.php');
include('helper.php');

session_start();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);
if (!$auth->is_authenticated()) {
  redirect('login.php');
}
$authenticated_user = $auth->authenticated_user();
$dateStorage = new DateStorage();

$date = $dateStorage->findById($_GET['vaccinationID']);
$user = $user_storage->findById($_GET['userID']);

if (($key = array_search($_GET['userID'], $date['applicants'])) !== false) {
    unset($date['applicants'][$key]);
}

$user['vaccinationID'] = 0;

$dateStorage->update($_GET['vaccinationID'], $date);
$user_storage->update($_GET['userID'], $user);

redirect('successful.php?id=deleteDone');



?>