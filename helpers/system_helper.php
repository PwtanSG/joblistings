<?php

//redirect to page
function redirect($page = FALSE, $message = NULL, $message_type = NULL)
{
    if (is_string($page)) {
        $location = $page;
    } else {
        $location = $_SERVER['SCRIPT_NAME'];
    }

    //handle redirect message with session
    if ($message != NULL) {
        $_SESSION['message'] = $message;
    }
    if ($message_type != NULL) {
        $_SESSION['message_type'] = $message_type;
    }

    //Redirect page
    header('Location: ' . $location);
    exit;
}

//Display message
function displaySessionMessage()
{
    //display message if msg exist
    if (!empty($_SESSION['message'])) {
        $message = $_SESSION['message'];
        if (!empty($_SESSION['message_type'])) {
            $message_type = $_SESSION['message_type'];
            if ($message_type == 'error') {
                echo '<div class="alert alert-danger">' . $message . '</div>';
            } else {
                echo '<div class="alert alert-success">' . $message . '</div>';
            }
        }
        //clear the message after displayed
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    } else {
        echo '';
    }
}
