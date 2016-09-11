<?php

class User {
    private $id;
    private $username;
    private $hashedPassword;
    private $email;
    
    public function __construct() {
        $this->id = -1;
        $this->username = "";
        $this->hashedPassword = "";
        $this->email = "";
    }
    
    public function setUsername($newUsername) {
        $this->username = $newUsername;
    }  
    
    public function setPassword($newPassword) {
        $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        $this->hashedPassword = $newHashedPassword;
    }
    
    public function setEmail($newEmail) {
        $this->email = $newEmail;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getUsername() {
        return $this->username;
    }
    
    public function getHashedPassword() {
        return $this->hashedPassword;
    } 

    public function getEmail() {
        return $this->email;
    }
    
    public function saveToDB(mysqli $connection) { //mysqli mówi nam, jakiej klasy obiektu się tu spodziewać

        if($this->id == -1) {
            
            //dodajemy użytkownika do bazy:
            
            $sql = "INSERT INTO User(username, email, hashed_password)
                    VALUES ('$this->username', '$this->email', '$this->hashedPassword')";
            
            $result = $connection->query($sql);
            
            if ($result) {
                $this->id = $connection->insert_id;
                return true;
            }
            
        } else {
            
            $sql = "UPDATE User SET username='$this->username',
                                     email='$this->email',
                                     hashed_password='$this->hashedPassword'
                    WHERE id=$this->id";
            
            $result = $connection->query($sql);
            
            if($result) {
                return true;
            }
                   
        }
        
        return false;
    }
    
    public function delete(mysqli $connection) {
        
        if($this->id != -1) {
            
            $sql = "DELETE FROM User WHERE id=$this->id";
            
            $result = $connection->query($sql);
            
            if ($result) {
                
                $this->id = -1;
                return true;                
            }
            
            return false; //jeśli nie udało się usunąć użytkownika z bazy
            
        } else {
            
            return true; //jeśli użytkownika nie było wcześniej w bazie to go nie usuwamy
                         //i zwracamy true;       
        }
        
    }
    
    
    static public function loadUserById(mysqli $connection, $id) {
        
        $sql = "SELECT * FROM User WHERE id=$id";
        
        $result = $connection->query($sql);
        
        if($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            $loadedUser= new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashedPassword = $row['hashed_password'];
            $loadedUser->email = $row['email'];
            
            return $loadedUser;
        }
        
        return null;
        
    }
    
    static public function loadUserByEmail(mysqli $connection, $email) {
        
        $sql = "SELECT * FROM User WHERE email='$email'";
        
        $result = $connection->query($sql);
        
        if($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            $loadedUser= new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashedPassword = $row['hashed_password'];
            $loadedUser->email = $row['email'];
            
            return $loadedUser;
        }
        
        return null;
        
    }
    
    
    
    static public function loadAllUsers(mysqli $connection) {
        
        $sql= "SELECT * FROM User";
        $users = []; //tworzymy tablicę, do której będziemy zapisywać stworzone obiekty (użytkowników)
        
        $result = $connection->query($sql);
        
        if($result && $result->num_rows != 0) {
            
            foreach ($result as $row) {
                
                $loadedUser= new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashedPassword = $row['hashed_password'];
                $loadedUser->email = $row['email'];
                
                $users[] = $loadedUser;

            }
            
        } else {
            
            echo "Błąd podczas pobierania użytkowników z bazy.";
            
        }
        
        return $users;
        
    }
    
    
}