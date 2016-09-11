<?php
require_once 'src/connection.php';
require_once 'src/init.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';
require_once 'src/Comment.php';

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
    $tweet = Tweet::loadTweetById($conn, $id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['text'])) {

        $newComment = new Comment();
        $newComment->setText($_POST['text']);
        $newComment->setCreationDate(date('Y-m-d H:i:s'));
        $newComment->setAuthorId($loggedUserId);
        $newComment->setTweetId($id);

        $newComment->saveToDB($conn);
    }

    $comments = Comment::loadAllCommentsByTweetId($conn, $id);
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

        <a href="main.php">Wróć do strony głównej</a>
        <table>


            <?php
            $userId = $tweet->getUserId();
            $username = User::loadUserById($conn, $userId)->getUsername();
            $creationDate = $tweet->getCreationDate();
            $text = $tweet->getText();

            echo "<tr class='tweetInfo'>
                    <td><a href='userInfo.php?id=$userId'>$username</a></td>
                    <td>$creationDate</td>
                    </tr>";
            echo "<tr class='tweetText'>
                    <td colspan='2'>$text</td>
                    </tr>";
            echo "<tr><td class='border'></td></tr>";
            echo "<tr id='comments'><td colspan='2'><strong>Komentarze</strong></td></tr>";
            echo "<tr><td class='border'></td></tr>";
            ?>
            <form action="#" method="POST">
                <tr>
                    <td colspan="3">
                        <input class="text" type="textarea" name="text">
                    </td>
                </tr>
                <tr>
                    <td class="tableButton" colspan="2">
                        <input class="tableButton" type="submit" value="Dodaj komentarz">
                    </td>
                </tr>
                <tr><td class='border'></td></tr>
            </form>
            <?php
            foreach ($comments as $comment) {

                $commenterId = $comment->getAuthorId();
                $username = User::loadUserById($conn, $commenterId)->getUsername();
                $creationDate = $comment->getCreationDate();
                $text = $comment->getText();

                echo "<tr class='tweetInfo'>
                    <td><a href='userInfo.php?id=$commenterId'>$username</a></td>
                    <td>$creationDate</td>
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
