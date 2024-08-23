<?php
require 'config/connection.php' ;
session_destroy();

session_start();
$_SESSION['log'] = '1' ;
header('location: login')
?>