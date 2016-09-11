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

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['text']) &&
        isset($_POST['senderId']) &&
        isset($_POST['receiverId'])) {
    
    $newMessage = new Message();
    $newMessage->setText($_POST['text']);
    $newMessage->setCreationDate(date('Y-m-d H:i:s'));
    $newMessage->setSenderId($_POST['senderId']);
    $newMessage->setReceiverId($_POST['receiverId']);

    $result = $newMessage->saveToDB($conn);
    
}

$sentMessages = Message::loadAllMessagesBySenderId($conn, $loggedUserId);
$receivedMessages = Message::loadAllMessagesByReceiverId($conn, $loggedUserId);
?>

<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <title></title>
    </head>
    <body>

        <table class="outer-table">
            <tr>
                <td><h3>Odebrane</h3></td>
                <td><h3>Wysłane</h3></td>
            </tr>
            <tr>
                <td>
            <table class="received">


                <?php
                foreach ($receivedMessages as $message) {

                    $messageId = $message->getId();
                    $senderId = $message->getSenderId();
                    $sender = User::loadUserById($conn, $senderId)->getUsername();
                    $creationDate = $message->getCreationDate();
                    $text = $message->getText();


                    echo "<tr class='tweetInfo'>
                            <td>
                                Od: <a href='userInfo.php?id=$senderId'>$sender</a>
                            </td>
                            <td>
                                $creationDate
                            </td>
                        </tr>";
                    echo "<tr class='tweetText'>
                            <td colspan='2'>$text <a href='readMessage.php?id=$messageId'>Pokaż całość</a></td>
                            
                        </tr>";
                    echo "<tr><td colspan='2' class='border'></td></tr>";
                }
                ?>
            </table>
                </td>
                <td>
            <table class="sent">


                <?php
                foreach ($sentMessages as $message) {

                    $messageId = $message->getId();
                    $receiverId = $message->getReceiverId();
                    $receiver = User::loadUserById($conn, $receiverId)->getUsername();
                    $creationDate = $message->getCreationDate();
                    $text = $message->getText();


                    echo "<tr class='tweetInfo'>
                            <td>
                                Do: <a href='userInfo.php?id=$receiverId'>$receiver</a>
                            </td>
                            <td>
                                $creationDate
                            </td>
                        </tr>";
                    
                    echo "<tr class='tweetText'>
                            <td colspan='2'>$text <a href='readMessage.php?id=$messageId'>Pokaż całość</a></td>
                        </tr>";
                    
                    echo "<tr><td colspan='2' class='border'></td></tr>";
                }
                ?>
            </table>
                </td>
            </tr>
        </table>
        </div>
</div>
    </body>
</html>
