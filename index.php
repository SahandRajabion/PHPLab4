<?php

require_once("View/HTMLView.php");
require_once("View/LoginView.php");
require_once('Controller/LoginController.php');

session_start();

$loginController = new LoginController();
$loginController->doControll();
