<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';


if (!isset($_SESSION['loggedUserId'])) {

    header('Location: login.php');
} else {

    $loggedUserId = $_SESSION['loggedUserId'];

    $loggedUser = User::loadUserById($conn, $loggedUserId);

    echo "Zalogowano jako <strong>" . $loggedUser->getUsername() . "</strong><br>";
    echo "<a href='logout.php'>Wyloguj</a><br>";
    echo "<a href='editUser.php?id=$loggedUserId'>Edytuj swoje dane</a><br>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['text'])) {

    $newTweet = new Tweet();
    $newTweet->setText($_POST['text']);
    $newTweet->setCreationDate(date('Y-m-d H:i:s'));
    $newTweet->setUserId($loggedUserId);

    $newTweet->saveToDB($conn);
}

$allTweets = Tweet::loadAllTweets($conn);
?>

<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
              <title></title>
    </head>
    <body>
        
        <table>

            <form action="#" method="POST">
                <tr>
                    <td colspan="2">
                        <input class="text" type="textarea" maxlength="140" name="text">
                    </td>
                </tr>
                <tr>
                    <td class="tableButton" colspan="2">
                        <input class="tableButton" type="submit" value="Dodaj nowy tweet">
                    </td>
                </tr>
                <tr><td class='border'></td></tr>
            </form>
<?php
foreach ($allTweets as $tweet) {

    $tweetId = $tweet->getId();
    $userId = $tweet->getUserId();
    $username = User::loadUserById($conn, $userId)->getUsername();
    $creationDate = $tweet->getCreationDate();
    $text = $tweet->getText();
    

    echo "<tr class='tweetInfo'>
                <td>
                    <a href='userInfo.php?id=$userId'>$username</a>
                </td>
                <td>
                    <a href='tweetInfo.php?id=$tweetId'>$creationDate</a>
                </td>
            </tr>";
    echo "<tr class='tweetText'>
                <td colspan='2'>$text</td>
            </tr>";
    echo "<tr><td class='border'></td></tr>";
}
?>
        </table>

    </body>
</html>
