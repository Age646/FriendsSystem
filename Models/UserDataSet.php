<?php

require_once ('Models/Database.php');
require_once ('Models/UserData.php');
require_once ('Models/FriendData.php');
class UserDataSet
{
    protected $_dbHandle, $_dbInstance;

    public function __construct(){
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function fetchAllUsers($currentUser) {
        $sqlQuery = "SELECT * FROM users WHERE username != ?";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(1,$currentUser);

        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }


    public function fetchSomeUsers($searchText){
        $sqlQuery = "SELECT * FROM users WHERE name LIKE ? LIMIT 0,10";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $searchText ="%".$searchText."%";
        $statement->bindParam(1,$searchText);
        $statement->execute();

        $sqlQuery = 'SELECT * FROM users WHERE username LIKE ?';
        $statement2 = $this->_dbHandle->prepare($sqlQuery);
        $searchText ="%".$searchText."%";
        $statement2->bindParam(1,$searchText);
        $statement2->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;

        while ($row = $statement2->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    public function fetchLoggedSearchedUsers($search)
    {
        $sqlQuery = "SELECT * FROM users WHERE name LIKE ?;"; //searching using name
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $text = "%".$search."%";
        $statement->bindParam(1,$text);
        $statement->execute(); // execute the PDO statement

        $sqlQuery2 = "SELECT * FROM users WHERE username = ?"; //searching using username
        $statement2 = $this->_dbHandle->prepare($sqlQuery2);
        $statement2->bindParam(1,$search);
        $statement2->execute();

        $sqlQuery3 = "SELECT * FROM users WHERE email = ?"; //searching using email
        $statement3 = $this->_dbHandle->prepare($sqlQuery3);
        $statement3->bindParam(1,$search);
        $statement3->execute();

        if ($statement->rowCount() > 0) {
            $dataSet = [];
            while ($row = $statement->fetch()) {
                $dataSet[] = new UserData($row);
            }
            return $dataSet;
        }

        elseif ($statement2->rowCount() > 0) {
            $dataSet = [];
            while ($row = $statement2->fetch()) {
                $dataSet[] = new UserData($row);
            }
            return $dataSet;
        }

        elseif ($statement3->rowCount() > 0) {
            $dataSet = [];
            while ($row = $statement3->fetch()) {
                $dataSet[] = new UserData($row);
            }
            return $dataSet;
        }
    }

    public function registerUser($name, $username, $email, $password)
    {
        $picture = $username. ".png";//stores profile image name for the user
        $lat = 2.9282;
        $lng = 10.9392;
        $encrypted_pwd = md5($password);
        $sqlQuery = "INSERT into users (name, username, email, password, picture, latitude, longitude) VALUES (?,?,?,?,?,?,?)";
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindParam(1, $name);
        $statement->bindParam(2, $username);
        $statement->bindParam(3, $email);
        $statement->bindParam(4, $encrypted_pwd);
        $statement->bindParam(5, $picture);
        $statement->bindParam(6, $lat);
        $statement->bindParam(7, $lng);


        return $statement->execute();
    }

    /**
     * @return PDO
     */
    public function Authenticate($username, $password)
    {
        $encrypted_pwd = md5($password);
        $sqlQuery = 'SELECT * FROM users WHERE username=? AND password=?';
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindParam(1, $username);
        $statement->bindParam(2, $encrypted_pwd);

        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    public function createNewFriendship($currentUser, $friend2)
    {
        $sqlQuery = "INSERT into friends(friendsID,friend1,friend2,status) VALUES (NULL,'$currentUser','$friend2',2)";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }

    public function acceptFriendship($friend2,$friend1)
    {
        $sqlQuery = "UPDATE friends SET status =4 WHERE friend1 = ? AND friend2 = ?;";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1,$friend2);
        $statement->bindParam(2,$friend1);
        $statement->execute();
    }

    public function alreadyFriends($currentUser, $friend2)
    {
        try {
            $sqlQuery = "SELECT * FROM friends WHERE friend1 = '$currentUser' AND friend2 = '$friend2' AND status =4";
            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->execute();
            $dataset = $statement->fetch();

            $sqlQuery2 = "SELECT * FROM friends WHERE friend1 = '$currentUser' AND friend2 = '$friend2' 
                        AND status =2";
            $statement2 = $this->_dbHandle->prepare($sqlQuery2);
            $statement2->execute();
            $dataset2 = $statement2->fetch();

            if(!empty($dataset))
            {
                return 4;
            }
            elseif(!empty($dataset2))
            {
                return 2;
            }
            else
            {
                return 1;
            }
        }
        catch (PDOException $e)
        {
            die($e->getMessage());
        }
    }

    public function deleteFriendship($currentUser,$friend2)
    {
        $sqlQuery = "UPDATE friends SET status =1 WHERE friend1 = ? AND friend2 = ?";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(1,$currentUser);
        $statement->bindParam(2,$friend2);
        $statement->execute();
    }

    public function updateFriendship($currentUser,$friend2)
    {
        $sqlQuery = "UPDATE friends SET status = 2 WHERE friend1='$currentUser' AND friend2 = '$friend2'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }

    public function fetchUserFriends($userID)
    {
        $sqlQuery = "SELECT * FROM (
        SELECT * FROM users WHERE users.userID in (
            SELECT friend1 as friend FROM friends WHERE (friends.friend1 = $userID or friends.friend2 = $userID)
            union
            SELECT friend2 as friend FROM friends WHERE (friends.friend1 = $userID or friends.friend2 = $userID)
            ) and users.userID != $userID
        ) ping inner join friends WHERE ((friend1=ping.userID and friend2=$userID)or(friend1=$userID and friend2=ping.userID))";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new FriendData($row);
        }
        return $dataSet;
    }

    public function fetchSearchedFriends($userID, $FriendName)
    {
        $sqlQuery = "SELECT * FROM (
            SELECT * FROM users WHERE name LIKE '%$FriendName%'  AND users.userID in (
                SELECT friend1 as friend
                FROM friends
                WHERE (friends.friend1 = $userID or friends.friend2 = $userID)
                union 
                SELECT friend2 as friend FROM friends WHERE (friends.friend1 = $userID or friends.friend2 = $userID)
                ) and users.userID != $userID
            ) ping inner join friends WHERE ((friend1=ping.userID and friend2=$userID)or(friend1=$userID and friend2=ping.userID))";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new FriendData($row);
        }
        return $dataSet;
    }

    public function checkFriends($currentUser, $friend2)
    {
        $sqlQuery = "SELECT * FROM friends WHERE friend1 = '$currentUser' AND friend2 = '$friend2'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new FriendData($row);
        }
        return $dataSet;
    }


    public function locationUpdate($userID, $lat, $lng)
    {
        $sqlQuery = "Update users set lat=$lat, lng=$lng WHERE username=$userID";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }

    public function fetchAllUsersNotFriends($userID)
    {
        $sqlQuery = "SELECT * FROM (
        SELECT * FROM users WHERE users.id NOT IN (
            SELECT friend1 as friend FROM friends WHERE (friends.friend1 = $userID or friends.friend2 = $userID)
            union
            SELECT friend2 as friend FROM friends WHERE (friends.friend1 = $userID or friends.friend2 = $userID)
            ) and users.id != $userID
        ) ping inner join friends WHERE ((friend1=ping.id and friend2=$userID)or(friend1=$userID and friend2=ping.id))";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new FriendData($row);
        }
        return $dataSet;
    }
}