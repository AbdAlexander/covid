<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siker</title>
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

        <?php 
            if($_GET['id'] === "applyDone") {
                print('<h1>Sikeresen jelentkezett az oltásra!</h1>');
            } else if($_GET['id'] === "deleteDone") {
                print('<h1>Sikeresen lemondta az időpontját!</h1>');
            } else if($_GET['id'] === "newDateDone") {
                print('<h1>Sikeresen hozzáadott egy új időpontot!</h1>');
            } else {
                print('<h1>Ismeretlen hiba!</h1>');
            }
            
        ?>
        <a class="nav-link" href="index.php">Vissza a főoldalra</a>
    </div>
</body>
</html>