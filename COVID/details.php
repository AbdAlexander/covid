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
if($authenticated_user['roles']['0'] !== "admin") {
    print_r($authenticated_user['roles']['0']);
    die('Hozzáférés megtagadva!');
    redirect('login.php');
} 

$id = $_GET['id'];
$dateStorage = new DateStorage();
$date = $dateStorage->findById($id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oltás részletei</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <div class="collapse navbar-collapse" id="navbarText">
            <h4><a class="nav-link" href="index.php" style="color:rgb(217, 217, 217);">Nemzeti Koronavírus Depó</a></h4>
            <ul class="navbar-nav mr-auto"></ul>
            <a class="nav-link" href="index.php" style="color:white;">Vissza a főoldalra</a>
        </div>
    </nav>

    <div class="container-fluid">
        <h1>Az oltás részletes adatai</h1>
        <ul>
            <li><strong>Oltás dátuma:</strong> <?= $date['date'] ?></li>
            <li><strong>Oltás időpontja:</strong> <?= $date['time'] ?></li>
            <li><strong>Maximális férőhely:</strong> <?= $date['capicity'] ?></li>
            <li><strong>Jelentkezett emberek adatai:</strong></li>
        </ul>
        <table class="table table-dark">
                <tr>
                    <th scope="col">Név</th>
                    <th scope="col">TAJ szám</th>
                    <th scope="col">E-mail cím</th>
                </tr>
                <?php foreach($date['applicants'] as $applicants => $id) : ?>
                    <tr>
                        <?php $applicant = $user_storage->findById($id); ?>

                        <td><?= $applicant['name'] ?> </td>
                        <td><?= $applicant['taj'] ?> </td>
                        <td><?= $applicant['email'] ?></td>
                    </tr>
                <?php endforeach ?>
        </table>
    <div>
</body>
</html>