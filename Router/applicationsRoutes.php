<?php

return $data = [
    "/applications" => "users@showApplications",
    "/applications/accept/{id}" => "managers@acceptApplication",
    "/applications/reject/{id}" => "agencies@destroy",
    "/applications/cancel/{id}" => "managers@cancelSubscription",
];
