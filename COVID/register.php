<?php
include_once("userstorage.php");
include_once("auth.php");
include_once("helper.php");

function validate($post, &$data, &$errors) {
    if (!isset($post['name']) || trim($post['name']) === '' || !(substr_count($post['name'], ' ') > 0)) {
        $errors['name'] = 'A teljes név megadása kötelező!';
    }
    if(!isset($post['taj']) || trim($post['taj']) === '' || !is_numeric($post['taj']) || !(strlen($post['taj']) === 9) || $post['taj'] < 0) {
        $errors['taj'] = 'Hibás TAJ-szám!';
    }
    if(!isset($post['email']) || trim($post['email']) === '' || !filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Hibás e-mail cím!';
    }
    if($post['pwd'] === "") {
        $errors['pwd'] = "Jelszó nem lett megadva!";
    }
    if($post['pwdAgain'] === "" || $post['pwdAgain'] != $post['pwd'] || $post['pwd'] === "") {
        $errors['pwdAgain'] = "Két jelszó nem egyezik!";
    }

    if(count($errors) === 0) {
        $data = $post;
    }
  
    return count($errors) === 0;
}

$user_storage = new UserStorage();
$auth = new Auth($user_storage);
$errors = [];
$data = [];

if (count($_POST) > 0) {
    if (validate($_POST, $data, $errors)) {
        if ($auth->user_exists($data['name'])) {
            $errors['global'] = "Ezen a néven felhasználót már regisztráltak!";
        } else {
            $data['vaccinationID'] = "0";
            $auth->register($data);
            redirect('login.php');
        } 
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
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
        <h1>Regisztráció</h1>
        <?php if (isset($errors['global'])) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $errors['global'] ?>
            </div>
        <?php endif; ?>
        <form method="post" style="width:50%;" novalidate>

            <div class="form-group">
                <label for="text">Teljes név</label>
                <input type="text" class="form-control" placeholder="Vezetéknév Keresztnév" id="name" name="name" value="<?= $_POST['name'] ?? "" ?>">
                <?php if (isset($errors['name'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $errors['name'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="number">TAJ szám</label>
                <input type="number" class="form-control" placeholder="TAJ-szám (123456789)" id="taj" name="taj" value="<?= $_POST['taj'] ?? "" ?>">
                <?php if (isset($errors['taj'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $errors['taj'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">Értesítési cím:</label>
                <input type="email" class="form-control" placeholder="E-mail cím" id="email" name="email" value="<?= $_POST['email'] ?? "" ?>">
                <?php if (isset($errors['email'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $errors['email'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="pwd">Jelszó:</label>
                <input type="password" class="form-control" placeholder="Jelszó" id="pwd" name="pwd">
                <?php if (isset($errors['pwd'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $errors['pwd'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="pwd">Jelszó megerősítése:</label>
                <input type="password" class="form-control" placeholder="Jelszó megerősítése" id="pwdAgain" name="pwdAgain">
                <?php if (isset($errors['pwdAgain'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $errors['pwdAgain'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Regisztráció</button>
        </form>
    </div>

</body>
</html>