<?php
include('userstorage.php');
include('auth.php');
include('helper.php');

// functions
function validate($post, &$data, &$errors) {
    if(!isset($post['email']) || trim($post['email']) === '') {
        $errors['email'] = 'E-mail cím nem lett megadva!';
    }
    else if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'E-mail formátuma helytelen!';
    }
    if($post['pwd'] === "") {
        $errors['pwd'] = "Jelszó nem lett megadva!";
    }
    if(count($errors) === 0) {
        $data = $post;
    }

    return count($errors) === 0;
}

// main
session_start();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);
$data = [];
$errors = [];
if ($_POST) {
  if (validate($_POST, $data, $errors)) {
    $auth_user = $auth->authenticate($data['email'], $data['pwd']);
    if (!$auth_user) {
      $errors['global'] = "Téves az email cím vagy a jelszó!";
    } else {
      $auth->login($auth_user);
      redirect('index.php');
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
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


    <div class="container-fluid" style="margin:50px auto; width:600px; max-width:100%;">
        <h1>Bejelentkezés</h1>
        <?php if (isset($errors['global'])) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $errors['global'] ?>
            </div>
        <?php endif; ?>

        <form method="post" action="" novalidate>

            <div class="form-group">
                <label for="email">E-mail cím:</label>
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

            <button type="submit" class="btn btn-primary">Bejelentkezés</button>
        </form>
        <a href="register.php">Ha még regisztrált, erre a linkre kattintva megteheti</a>
    </div>

</body>
</html>