<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';

$info = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['email']) &&
        isset($_POST['password'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $loggedUser = User::loadUserByEmail($conn, $email);
    
    if ($loggedUser != null) {

        $hash = $loggedUser->getHashedPassword();

        if (password_verify($password, $hash)) {

            $loggedUserId = $loggedUser->getId();

            $_SESSION['loggedUserId'] = $loggedUserId;

            header('Location: main.php');
        } else {

            $info = "Niepoprawny login lub hasło. Spróbuj ponownie";
        }
    } else {

        $info = "Niepoprawny login lub hasło. Spróbuj ponownie";
    }
}
?>

<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css"
              <title></title>
    </head>
    <body>
        <nav class="nav navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="menu">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="register.php">Załóż konto</a></li>
                        <li><a href="#">Zaloguj</a></li>
                    </ul>                    
                </div>       
            </div>
        </nav>
        <br>

        <div class="container col-md-6 col-md-offset-3 col-sm-12">
            <div class="panel panel-info">
                <form action="#" method="POST">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label" for="email">E-mail:</label>
                            <input class="form-control" type="text" name="email" id="email">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="pass">Hasło:</label>
                            <input class="form-control" type="password" name="password" id="pass">
                        </div>
                    </div>
                    <div class="panel-footer clearfix">
                        <?php echo $info . ' lub ' ?>
                        <a href="register.php">Załóż nowe konto</a>
                        <input class="btn btn-primary pull-right" type="submit" value="Zaloguj">
                    </div>
                </form>
            </div>
        </div>


    </body>
</html>
