<?php

class Comment {

    private $id;
    private $authorId;
    private $tweetId;
    private $creationDate;
    private $text;

    public function __construct() {
        $this->id = -1;
        $this->authorId = 0;
        $this->tweetId = 0;
        $this->creationDate = 0;
        $this->text = 0;
    }

    public function setAuthorId($newAuthorId) {
        $this->authorId = $newAuthorId;
    }

    public function setTweetId($newTweetId) {
        $this->tweetId = $newTweetId;
    }

    public function setText($newText) {
        $this->text = $newText;
    }

    public function setCreationDate($newDate) {
        $this->creationDate = $newDate;
    }

    public function getId() {
        return $this->id;
    }

    public function getAuthorId() {
        return $this->authorId;
    }

    public function getTweetId() {
        return $this->tweetId;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getText() {
        return $this->text;
    }

    public function saveToDB(mysqli $connection) {

        if ($this->id == -1) {

            $sql = "INSERT INTO Comment(author_id, tweet_id, creation_date, text)
                    VALUES ($this->authorId, $this->tweetId, '$this->creationDate', '$this->text')";

            $result = $connection->query($sql);

            if ($result) {
                $this->id = $connection->insert_id;
                return true;
            }
        }

        return false;
    }

    static public function loadCommentById(mysqli $connection, $id) {

        $sql = "SELECT * FROM Comment WHERE id=$id";

        $result = $connection->query($sql);

        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->authorId = $row['author_id'];
            $loadedComment->tweetId = $row['tweet_id'];
            $loadedComment->creationDate = $row['creation_date'];
            $loadedComment->text = $row['text'];

            return $loadedComment;
        }
    }

    static public function loadAllCommentsByTweetId(mysqli $connection, $tweetId) {

        $sql = "SELECT * FROM Comment
                WHERE tweet_id = $tweetId
                ORDER BY creation_date DESC";

        $result = $connection->query($sql);

        $ret = [];

        if ($result && $result->num_rows != 0) {

            foreach ($result as $row) {

                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->authorId = $row['author_id'];
                $loadedComment->tweetId = $row['tweet_id'];
                $loadedComment->creationDate = $row['creation_date'];
                $loadedComment->text = $row['text'];

                $ret[] = $loadedComment;
            }
        }

        return $ret;
    }

}
