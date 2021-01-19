<?php
include('userstorage.php');
include('datestorage.php');
include('auth.php');
include('helper.php');

session_start();
$auth = new Auth(new UserStorage());
if (!$auth->is_authenticated()) {
  redirect('login.php');
}

function validate($post, &$data, &$errors) {
    if ($post['date'] === "") {
        $errors['date'] = 'Dátum megadása kötelező!';
    } else if (!(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$post['date']))) {
        $errors['date'] = 'Dátum formátuma helytelen!';
    } 
    if (count($errors) === 0) {
        $startDate = strtotime("2021-01-10");
        $postDate = strtotime($post['date']);
        if($postDate < $startDate) {
            $errors['date'] = "Az oltás dátuma nem lehet 2021.01.10 előtt!";
        }
    }

    if (!isset($post['time']) || trim($post['time']) === '') {
        $errors['time'] = 'Időpont megadása kötelező!';
    } else if(!(preg_match("/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/", $post['time']))) {
        $errors['time'] = 'Időpont formátuma helytelen!';
    }
    if (!isset($post['capicity']) || trim($post['capicity']) === '') {
        $errors['capicity'] = 'Maximális férőhely megadása kötelező!';
    } else if($post['capicity'] <= 0) {
        $errors['capicity'] = 'Férőhelyek száma nem lehet 0 vagy negatív!';
    } else if(!is_numeric($post['capicity'])) {
        $errors['capicity'] = 'Férőhelyek száma csak szám lehet!';
    }
    if (count($errors) === 0) {
        $data = $post;
    }
  
    return count($errors) === 0;
}

$data = [];
$errors = [];
if (count($_POST) > 0) {
  if (validate($_POST, $data, $errors)) {
    $auth_user = $auth->authenticated_user();
    $data['applicants'] = [];
    $dateStorage = new DateStorage();
    $dateStorage->add($data);
    redirect("successful.php?id=newDateDone");
  }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Időpont hozzáadása</title>
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
        <h1>Új dátum hozzáadása</h1>

        <?php if (isset($errors['global'])) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $errors['global'] ?>
            </div>
        <?php endif; ?>

        <form method="post" style="width:50%;" novalidate>

            <div class="form-group">
                <label for="date">Dátum</label>
                <input type="date" class="form-control"  id="date" name="date" value="<?= $_POST['date'] ?? "" ?>">
                <?php if (isset($errors['date'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $errors['date'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="appt">Időpont</label>
                <input type="time" class="form-control" id="time" name="time" value="<?= $_POST['time'] ?? "" ?>">
                <?php if (isset($errors['time'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $errors['time'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="number">Maximális férőhely</label>
                <input type="number" class="form-control" placeholder="Maximális férőhely" id="capicity" name="capicity" value="<?= $_POST['capicity'] ?? "" ?>" min="1">
                <?php if (isset($errors['capicity'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $errors['capicity'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Új időpont hozzáadása</button>
        </form>
    </div>

<body>
</html>

