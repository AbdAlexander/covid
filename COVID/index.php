<?php
include('userstorage.php');
include('datestorage.php');
include('auth.php');
include('helper.php');

session_start();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);
$isUser = $auth->is_authenticated();
if ($isUser) {
    $authenticated_user = $auth->authenticated_user();
}
$dateStorage = new DateStorage();
$dates = $dateStorage->findAll();

?>

<!DOCTYPE html>
<html lang="hu">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listaoldal</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <div class="collapse navbar-collapse" id="navbarText">
            <h4><a class="nav-link" href="index.php" style="color:rgb(217, 217, 217);">Nemzeti Koronavírus Depó</a></h4>
            <ul class="navbar-nav mr-auto"></ul>
            <?php if (!$isUser) : ?>
                <?= '<a class="nav-link" href="register.php" style="color:white;">Regisztráció</a>' ?>
                <?= '<a class="nav-link" href="login.php" style="color:white;">Bejelentkezés</a>' ?>
            <?php else : ?>
                <?= '<a class="nav-link" href="logout.php" style="color:white;">Kijelentkezés</a>' ?>
            <?php endif; ?>
        </div>
    </nav>


    <div class="container-fluid">
        <?php if($isUser) : ?>
            <h1>Üdvözöljük <?= $authenticated_user['name'] ?>!</h1>
            
            <?php $user = $user_storage->findById($authenticated_user['id']); 
            if($user['roles'][0] === "admin") {
                print('<h2 style="color:red"> Te Admin vagy ezen az oldalon. Kellemes tesztelést és beadandó ellenőrzést kívánok! :) </h2>');
            }
            ?>
        
            <?php if($user['vaccinationID'] == 0) : ?>
                <h3>Ön még nem jelentkezett oltásra! Tegye meg minél előbb!</h3>
            <?php else : ?>
                <?php $appointment = $dateStorage->findById($user['vaccinationID']); ?>
                <h3>Ön már jelentkezett az oltásra!</h3>
                <h4>Oltás adatai</h4>
                <ul>
                    <li><strong>Oltás dátuma:</strong> <?= $appointment['date'] ?> </li>
                    <li><strong>Oltás időpontja:</strong> <?= $appointment['time'] ?> </li>
                </ul>
                <a href="delete.php?userID=<?=$authenticated_user['id']?>&vaccinationID=<?=$user['vaccinationID']?>"><button type="button" class="btn btn-secondary">Lemondás</button></a>
                <hr>
            <?php endif ?>
        <?php endif ?>
        <h2>Regisztráció az oltásra</h2>
        <h3>Általános információk</h3>
        <span> 
            Az oltás önkéntes és ingyenes. Most Önnek is lehetősége van, hogy jelezze oltás iránti igényét. 
            Ehhez, kérjük válasszon egy időpontot. Ha regisztrál, megadja személyes adatait, lehetősége adataik
            jelentkezni a felkínált oltási időpontok egyikére. Ezt követően az oldal átirányítja magát, ahol
            ellenőrizheti az oltás adatait, a saját adatait, ha mindet helyesnek talált, el kell fogadnia az oltás
            általános feltételeit, ezt követően jelentkezhet az oltásra. 
            Jelentkezést követően általános információkat láthat a főoldalon, hogy melyik oltásra jelentkezett. 
            Jelentkezését bármikor lemondhatja, de legfeljebb 24 órával az oltás kezdete előtt dönthet úgy, hogy lemondja 
            az időpontját. 
            További információkért, keresse <strong>Hortváth Győző adminunkat</strong>. 
        </span> <br> <br>
        
        <h4>Jelentkezés az oltásra</h4>
        <?php if($isUser) { 
            $user = $user_storage->findById($authenticated_user['id']);
            if($user['roles'][0] === "admin") {
                print('<a href="new.php"><button type="button" class="btn btn-secondary">Új időpont meghirdetése</button></a> <br>');
            }
        }
        ?>
        <br> 
        <div id="dates-table">
            <table class="table table-dark">
                <tr>
                    <th scope="col">Dátum</th>
                    <th scope="col">Időpont</th>
                    <th scope="col">Jelentkezettek száma</th>
                    <th scope="col">Összes férőhely</th>
                    <th scope="col">Jelentkezés</th>
                    <?php if($isUser && $user['roles'][0] === "admin") {
                        print('<th scope="col">Időpontok részletei</th>');
                    }
                    ?>
                </tr>
                <?php foreach($dates as $date) : ?>
                    <tr>
                        <td><?= $date['date'] ?> </td>
                        <td><?= $date['time'] ?> </td>
                        <td><?= count($date['applicants']) ?> </td>
                        <td><?= $date['capicity'] ?></td>

                        <?php if($isUser) : ?>
                            <?php if($user['vaccinationID'] == 0) : ?>
                                <?php if(count($date['applicants']) < $date['capicity']) : ?>
                                    <td style="color:green"><a href="apply.php?id=<?= $date['id'] ?>"> <?= "Jelenkezés" ?></a></td>
                                <?php else : ?>
                                    <td style="color:red">Nincs több férőhely!</td>
                                <?php endif ?>
                            <?php else : ?>
                                <td>Önnek már van időpontja!</td>
                            <?php endif ?>
                        <?php else : ?>
                            <?php if(count($date['applicants']) < $date['capicity']) : ?>
                                <td style="color:green"><a href="apply.php?id=<?= $date['id'] ?>"> <?= "Jelenkezés" ?></a></td>
                            <?php else : ?>
                                <td style="color:red">Nincs több férőhely!</td>
                            <?php endif ?>
                        <?php endif ?>
                        <?php if($isUser && $user['roles'][0] === "admin") : ?>
                            <td> <a href="details.php?id=<?= $date['id']?>"> <?= "Részletek" ?></a></td>
                        <?php endif ?>
                    </tr>
                <?php endforeach ?>
            </table>
            <button id="btn" type="button" class="btn btn-secondary">Előző hónap</button>
            <button id="btn2" type="button" class="btn btn-secondary">Következő hónap</button>
        </div>

    </div>
    
    <script>
        const btn = document.querySelector('#btn');
        const btn2 = document.querySelector('#btn2');
        btn.addEventListener('click', function() {
            alert("Sajnos nem tudtam rájönni, hogyan lehetne szűrni dátumokra, így ezt a két feladatot nem sikerült megoldani. :(((");
        });
        btn2.addEventListener('click', function() {
            alert("Sajnos nem tudtam rájönni, hogyan lehetne szűrni dátumokra, így ezt a két feladatot nem sikerült megoldani. :(((");
        });
    </script>                    
</body>
</html>