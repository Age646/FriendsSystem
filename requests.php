<?php
require_once ('Models/UserDataSet.php');


$friendsData = new UserDataSet();

if (isset($_POST['addFriend'])) {

    $checkFriends = $friendsData->checkFriends($_POST['currentUID'], $_POST['requestID']);

    if ($checkFriends == true) {
        $friendsData->updateFriendship($_POST['currentUID'], $_POST['requestID']);
        header("Location: index.php");
    } else {
        $friendsData->createNewFriendship($_POST['currentUID'], $_POST['requestID']);
        header("Location: index.php");
    }
}


elseif (isset($_POST['acceptFriend']))
{
    $friendsData->acceptFriendship($_POST['friendRequest1'], $_POST['friendRequest2']);
    header("Location: friends.php");
}
elseif (isset($_POST['declineFriend'])) {
    $friendsData->deleteFriendship($_POST['friendRequest1'], $_POST['friendRequest2']);
    header("Location: requestsPage.php");
}


elseif (isset($_POST['deleteFriend2']))
{
    $friendsData->deleteFriendship($_POST['currentUID'], $_POST['requestID']);
    header("Location: friend.php");
}
elseif (isset($_POST['deleteFriend'])) {
    $friendsData->deleteFriendship($_POST['currentUID'], $_POST['requestID']);
    header("Location: index.php");
}


elseif (isset($_POST['cancelRequest'])) {
    $friendsData->deleteFriendship($_POST['currentUID'], $_POST['requestID']);
    header("Location: requestsPage.php");
}

elseif (isset($_POST['cancelRequest2'])) {
    $friendsData->deleteFriendship($_POST['currentUID'], $_POST['requestID']);
    header("Location: index.php");
}
