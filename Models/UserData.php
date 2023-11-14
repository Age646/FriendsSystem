<?php

class UserData implements JsonSerializable
{
    protected $_userID, $_name, $_username, $_email, $_password, $_picture, $_lat, $_lng;

    public function __construct($dbRow){
        $this->_userID = $dbRow['userID'];
        $this->_name = $dbRow['name'];
        $this->_username = $dbRow['username'];
        $this->_email = $dbRow['email'];
        $this->_password = $dbRow['password'];
        $this->_picture = $dbRow['picture'];
        $this->_lat = $dbRow['lat'];
        $this->_lng = $dbRow['lng'];

    }

    public function jsonSerialize()
    {
        return [
            '_userID' => $this->_userID,
            '_name' => $this->_name,
            '_username' => $this->_username,
            '_email' => $this->_email,
            '_password' => $this->_password,
            '_picture' => $this->_picture,
            '_lat' => $this->_lat,
            '_lng' => $this->_lng
        ];
    }

    /**
     * @return mixed
     */
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
}