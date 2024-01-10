<?php

return $data = [
    "/properties" => "Properties@index",
    "/properties/detail/{id}" => "Properties@show",
    "/properties/edit/{id}" => "Properties@edit",
    "/properties/store" => "Properties@store",
    "/properties/update/{id}" => "Properties@update",
    "/properties/delete/{id}" => "Properties@destroy",
    "/myproperties" => "Properties@myProperties",
    "/properties/agency/{id}" => "Properties@fetchByAgencyId",
    "/properties/deleteimg/{id}" => "Properties@deleteImg",
];
