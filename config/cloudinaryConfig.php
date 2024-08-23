<?php
require "cloudinary/vendor/autoload.php";
require "cloudinary/config-cloud.php";

function getURL($file)
{
    $cloudUpload = \Cloudinary\Uploader::upload($file, array("timeout" => "60000"));
    $address = $cloudUpload['secure_url'];
    return $address;
}
