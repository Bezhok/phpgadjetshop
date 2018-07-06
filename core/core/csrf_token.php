<?php
namespace core\csrf_token;

function csrf_token()
{
    $token = md5(SECRET_KEY . session_id());
    $csrf_token = "<input type='hidden' name='csrf_token' value='$token'>";
    $_SESSION['csrf_token'] = $token;

    return $csrf_token; 
}