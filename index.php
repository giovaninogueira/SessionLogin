<?php
    require_once __DIR__ . '/vendor/autoload.php';
    use Auth\{
        Session, Login
    };
    Session::start();
    if(isset($_SESSION['DATE_TRY']) && (time() - Session::get('DATE_TRY')) <= 10) {
        echo 'Espere 10 segundos';
        die;
    }
    if(Session::get('DATE_CREATED')) {
        Session::validate();
        echo 'menu';
    } else {
        $login = new Login();
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            Login::authBasic();
        } else {
            $login->auth();
        }
    }