<?php
session_destroy();
session_start();
$_SESSION['flash_message'] = ["message" => "Successfully disconnected", "type" => "success"];
redirect("");
