<?php

class FriendData extends UserData
{
    protected $_userID, $_name, $_username, $_email, $_password, $_picture, $_lat, $_lng;
    protected $_friendsID, $_friend1, $_friend2, $_status;

    public function __construct($dbRow)
    {
        $this->_userID = $dbRow['userID'];
        $this->_name = $dbRow['name'];
        $this->_username = $dbRow['username'];
        $this->_email = $dbRow['email'];
        $this->_password = $dbRow['password'];
        $this->_picture = $dbRow['picture'];
        $this->_lat = $dbRow['lat'];
        $this->_lng = $dbRow['lng'];
        $this->_friendsID = $dbRow['friendsID'];
        $this->_friend1 = $dbRow['friend1'];
        $this->_friend2 = $dbRow['friend2'];
        $this->_status = $dbRow['status'];
    }

    public function getUserID()
    {
        return $this->_userID;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->_picture;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->_lat;
    }

    /**
     * @return mixed
     */
    public function getLng()
    {
        return $this->_lng;
    }

    public function getFriendID()
    {
        return $this->_friendsID;
    }

    public function getFriend1()
    {
        return $this->_friend1;
    }

    public function getFriend2()
    {
        return $this->_friend2;
    }

    public function getStatus()
    {
        return $this->_status;
    }
}