<?php
include_once('Dogs.php');

class Client
{
    function __construct()
    {
        $dogs = new Dogs();
    }
}
$worker = new Client();
