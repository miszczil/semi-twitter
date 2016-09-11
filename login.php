<?php 

require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' &&
   isset($_POST['email']) &&
   isset($_POST['password'])) {
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $loggedUser = User::loadUserByEmail($conn, $email);
    
    if($loggedUser != null) {
        
        $hash = $loggedUser->getHashedPassword();
        
        if(password_verify($password, $hash)) {
            
            $loggedUserId = $loggedUser->getId();
            
            $_SESSION['loggedUserId'] = $loggedUserId;
        
            header('Location: main.php');
            
        } else {
            
            echo "Niepoprawny login lub hasło. Spróbuj ponownie.";
            
        }

    } else {
        
        echo "Niepoprawny login lub hasło. Spróbuj ponownie.";
        
    }
    
    
        
    
    
}

?>

<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css"
              <title></title>
    </head>
    <body>

        <table>

            <form action="#" method="POST">
                <tr>
                    <td>
                        E-mail:
                    </td>
                    <td colspan="2">

                        <input class="login" type="text" name="email">

                    </td>
                </tr>
                <tr>
                    <td>Hasło:</td>
                    <td colspan="2">

                        <input class="login" type="password" name="password">
                    </td>
                </tr>
                <tr>
                    <td colspan="3">

                        <input class="tableButton" type="submit" value="Zaloguj">
                    </td>
                </tr>
            </form>
            <tr>
                <td colspan="3"><a href="register.php">Załóż konto</a></td>
            </tr>
        </table>



    </body>
</html>
