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

$id = $_GET['id'];
$authenticated_user = $auth->authenticated_user();
$dateStorage = new DateStorage();
$date = $dateStorage->findById($id);

if(count($_POST) > 0) {
    $user = $user_storage->findById($authenticated_user['id']);
    if($user['vaccinationID'] == 0) {
        array_push($date['applicants'], $authenticated_user['id']);
        $dateStorage->update($id, $date);
    
        $user['vaccinationID'] = $id;
        $user_storage->update($authenticated_user['id'], $user);
    
        redirect("successful.php?id=applyDone");
    } else {
        $errors['alreadyApplied'] = "Ön már jelentkezett egy oltásra! Ha egy másik időpontra szeretne jelentkezni, előbb törölje az előző jelentkezését!";
    }
} else {
    $errors['cond'] = "Jelentkezési feltételek elfogadása kötelező!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelentkezés</title>
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
        <h1>Jelentkezés az oltásra</h1>

        <?php if (isset($errors['cond'])) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $errors['cond'] ?>
            </div>
        <?php endif; ?>
        <?php if (isset($errors['alreadyApplied'])) : ?>
            <p><span class="error"><?= $errors['alreadyApplied'] ?></span></p>
        <?php endif; ?>

        <form method="post" style="width:50%;" novalidate>
            <h4>Oltás adatai</h4>
            <ul>
                <li><strong>Oltás dátuma:</strong> <?= $date['date'] ?> </li>
                <li><strong>Oltás időpontja:</strong> <?= $date['time'] ?> </li>
            </ul> <hr>
            <h4>Az Ön adatai</h4>
            <ul>
                <li><strong>Teljes név:</strong> <?= $authenticated_user['name'] ?> </li>
                <li><strong>TAJ szám:</strong> <?= $authenticated_user['taj'] ?> </li>
            </ul>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="checkbox">
                <label id="cond" class="form-check-label" for="checkbox">Elfogadom a <a href="conditions.php" target="_blank">jelentkezési feltételeket</a></label>
            </div>

            <br>
            <button type="submit" class="btn btn-primary">Jelentkezés az oltásra</button>
        </form> <br>
        <?php if($authenticated_user['roles']['0'] === "admin") : ?>
            <hr>
            <h4>Az időpontra jelentkezett emberek</h4>
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
        <?php endif ?>
    </div>
</body>
</html>