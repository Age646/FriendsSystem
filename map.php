<?php
require_once('loginPage.php');
require_once('Models/UserDataSet.php');
$userDataSet = new userDataSet();

$users = $userDataSet->fetchUserFriends($user->userIDLogged());

$mapData = ""; //empty string variable
foreach($users as $data) {//loop through each of the userdata objects
    if($data->getFriend1() == $user->userIDLogged() AND $data->getStatus() == 4){
        if($mapData == "")
        {
            $mapData = "[" . json_encode($data);
        }
        else{
            $mapData .= "," . json_encode($data);
        }}
}
//wrapping the data in the correct format before returning to xml request
if ($mapData != "") $mapData .= "]";
echo $mapData;
