<?php
require_once('loginPage.php');
require_once('Views/header.phtml');
require_once ('Models/UserDataSet.php');


$currentUser = $user->userIDLogged();
$dataset = new UserDataSet();

if (isset($_POST["search"])){
    $data = $dataset->fetchSearchedFriends($currentUser,$_POST["searchText"]);
}
else{
    $data = $dataset->fetchUserFriends($currentUser);
}

require_once('Views/requests.phtml');
require_once('Views/footer.phtml');
require_once('requests.php');