<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';
require_once 'src/Comment.php';
require_once 'src/Message.php';

if (!isset($_SESSION['loggedUserId'])) {

    header('Location: login.php');
} else {

    $loggedUserId = $_SESSION['loggedUserId'];

    $loggedUser = User::loadUserById($conn, $loggedUserId);

    echo "Zalogowano jako <strong>" . $loggedUser->getUsername() . "</strong><br>";
    echo "<a href='logout.php'>Wyloguj</a><br>";
}

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $message = Message::loadMessageById($conn, $id);
    
} else {

    header('Location: main.php');
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

        <a href="messages.php">Wróć do strony wiadomości</a>
        <a href="main.php">Wróć do strony głównej</a>
        <table>

            <?php
            $senderId = $message->getSenderId();
            $receiverId = $message->getReceiverId();
            $sender = User::loadUserById($conn, $senderId)->getUsername();
            $receiver = User::loadUserById($conn, $receiverId)->getUsername();
            $creationDate = $message->getCreationDate();
            $text = $message->getText();

            echo "<tr class='tweetInfo'>
                    <td>Od: <a href='userInfo.php?id=$senderId'>$sender</a></td>
                    <td>Do: <a href='userInfo.php?id=$receiverId'>$receiver</a></td>
                    <td>$creationDate</td>
                    </tr>";
            echo "<tr class='tweetText'>
                    <td colspan='3'>$text</td>
                    </tr>";
            echo "<tr><td class='border'></td></tr>";

            ?>
        </table>

    </body>
</html>
