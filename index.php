<?php

require_once('loginPage.php');
require_once('Views/header.phtml');
require_once ('Models/UserDataSet.php');


$view = new stdClass();
$view->pageTitle = 'Index';

$userDataSet = new UserDataSet();

$data = "";

if (isset($_POST['search'])) //this checks if the user has used the search facility
{
    if ($user->isLoggedIn() == true) //if user is logged in and used the search bar it will display the following code
    {
        //this allows the user to search with different metadata
        $data = $userDataSet->fetchLoggedSearchedUsers($_POST['searchText']);}
    else
    {
        //this fetches the user depending on the name typed
        $data = $userDataSet->fetchSomeUsers($_GET["id"]);
    }
}
else {
    //this fetches all users for the homepage when the search bar is not used
    $data = $userDataSet->fetchAllUsers($user->usernameLogged());
}

require_once('Views/index.phtml');
require_once('Views/footer.phtml');