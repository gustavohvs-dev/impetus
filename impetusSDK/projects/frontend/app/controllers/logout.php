<?php

namespace app\controllers;

include_once "app/middlewares/Auth.php";
use app\middlewares\Auth;

Auth::destroySession();