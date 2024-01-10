<?php

return $data = [
    "/users/store" => "users@store",
    "/users/storeagent" => "users@storeAgent",
    "/users/assignagent/{id}" => "users@assignAgent",
    "/users/unassignagent/{id}" => "users@unassignAgent",
    "/users/profile/{id}" => "users@show",
    "/users/login" => "users@login",
    "/users/update/{id}" => "users@update",
    "/users" => "users@manageUsers",
    "/users/delete/{id}" => "users@destroy",
];
