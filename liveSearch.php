<?php
require_once ('Models/UserDataSet.php');



    $userDataSet = new UserDataSet();

    $q = $_GET["q"];
    $search = $userDataSet->fetchSomeUsers($q);

    echo json_encode($search);


