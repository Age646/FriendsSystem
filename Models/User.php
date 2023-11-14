<?php

require_once ('UserDataSet.php');
class User
{
    protected $_username, $_loggedIn, $_userID;

    public function __construct()
    {
        session_start();

        $this->_username = "No user";
        $this->_loggedIn = false;
        $this->_userID = "0";

        if (isset($_SESSION["login"]))
        {
            $this->_username = $_SESSION["login"];
            $this->_userID = $_SESSION["uid"];
            $this->_loggedIn = true;
        }
    }

    public function Login($username,$password)
    {
        $users = new UserDataSet();
        $userDataset = $users->Authenticate($username,$password);

        if (count($userDataset) > 0)
        {
            $_SESSION["login"] = $username;
            $_SESSION["uid"] = $userDataset[0]->getUserID();
            $this->_loggedIn = true;
            $this->_username = $username;
            $this->_userID = $userDataset[0]->getUserID();
            return true;
        }
        else
        {
            $this->_loggedIn = false;
            return false;
        }
    }

    public function logout()
    {
        unset($_SESSION["login"]);
        unset($_SESSION["uid"]);
        $this->_loggedIn = false;
        $this->_username = "No user";
        $this->_userID = "0";
        session_destroy();
    }

    public function isLoggedIn()
    {
        return $this->_loggedIn;
    }

    public function usernameLogged()
    {
        return $this->_username;
    }

    public function userIDLogged()
    {
        return $this->_userID;
    }
}