<?php

namespace Auth;

use Auth\Session;

class Login
{
    public function __construct()
    {
    }

    public static function authBasic()
    {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Texto enviado caso o usuário clique no botão Cancelar';
        exit;
    }

    public function auth()
    {
        $flag = $this->sign();
        if($flag) {
            echo 'Logado!';
        }
    }

    /**
     * @param string $email
     * @param string $senha
     * @return string
     */
    public function sign()
    {
        $email = $_SERVER['PHP_AUTH_USER'];
        $senha = $_SERVER['PHP_AUTH_PW'];
        if($email === 'g@email.com' && $senha === '102030') {
            Session::createSession();
            Session::set('email', $email);
            Session::unsetParam('DATE_TRY');
            Session::unsetParam('try');
            return true;
        } else {
            $msg = '';
            Session::start();
            isset($_SESSION['try']) ? Session::set('try', Session::get('try') + 1) : Session::set('try', 0);
            if(Session::get('try') >= 3) {
                Session::set('try', 0);
                Session::set('DATE_TRY', time());
                $msg = 'Você tentou mais de três vezes, espere 10 segundos';
            } else {
                $msg = 'Email ou senha inválidos';
            }
            $this->logout(401, $msg);
            return false;
        }
    }

    public function logout($code, $msg)
    {
        http_response_code($code);
        $arr = [
          'msg' => $msg
        ];
        echo json_encode($arr);
    }
}