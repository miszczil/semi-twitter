<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';
require_once 'src/Message.php';

if (!isset($_SESSION['loggedUserId'])) {

    header('Location: login.php');
} else {

    $loggedUserId = $_SESSION['loggedUserId'];

    $loggedUser = User::loadUserById($conn, $loggedUserId);

    echo "Zalogowano jako <strong>" . $loggedUser->getUsername() . "</strong><br>";
    echo "<a href='logout.php'>Wyloguj</a><br>";
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' &&
        isset($_GET['id'])) {

    $id = $_GET['id'];
    $user = User::loadUserById($conn, $id);
    $username = $user->getUsername();
    
    echo "<a href='userInfo.php?id=$id'>Wróć do profilu $username</a><br>";
    
} else {

    header('Location: main.php');
}
?>
<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <title></title>
    </head>
    <body>

        <a href="main.php">Wróć do strony głównej</a>
        <table>

            <form action="messages.php" method="POST">
                <tr>
                    <td class="border">
                        <h2>Wiadomość do <?php echo $username; ?></h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="text" type="textarea" name="text">
                    </td>
                </tr>
                <tr>
                    <td class="tableButton">
                        <input class="tableButton" type="submit" value="Wyślij wiadomość">
                        <input type="hidden" name="senderId" value="<?php echo $loggedUserId; ?>">
                        <input type="hidden" name="receiverId" value="<?php echo $id; ?>">
                    </td>
                </tr>
                <tr><td class='border'></td></tr>

            </form>

        </table>
    </body>
</html>
