<?php
require_once ('Models/UserDataSet.php');
require_once ('Models/User.php');


$view = new stdClass();
$view->pageTitle = 'Sign in/Sign up';
$user = new User();


if (isset($_POST["loginBtn"])) {

//    $user = new User();
    $verify = $user->Login($_POST['username'], $_POST['password']);
    if ($verify == true) {
        header("Location: index.php");

    } else {
        echo 'Username or password incorrect';
    }

}
if (isset($_POST["signupBtn"])) {
    {
        //var_dump($_POST);
        $userDataSet = new UserDataSet();
        $newUser = $userDataSet->registerUser($_POST["name"], $_POST["username"], $_POST["email"], $_POST["password"]);
//        header("Location: index.php");

        $view->dbMessage = "$newUser new user added";
    }

}


if (isset($_POST["logoutBtn"])){

    $user->logout();
    header("Location: index.php");
}