<?php

class NetTeam
{
    private static $instance;

    private $user;

    private function __construct()
    {
        session_start();

        if (isset($_SESSION['user'])) {
            $this->user = $_SESSION['user'];
        }
    }

    /**
     * @return NetTeam
     */
    public static function init()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function run()
    {
        if (!isset($_GET['a'])) {
            return $this->index();
        }

        switch ($_GET['a']) {
            case 'login':
                return $this->login();
            case 'logout':
                return $this->logout();
            default:
                return $this->index();
        }
    }

    public function index()
    {
        if (null === $this->user) {
            echo "You are not logged in";
            return;
        }

        echo sprintf('Hello %s (%s %s)', $this->user['login'], $this->user['name'], $this->user['surname']);
    }

    public function login()
    {
        $_SESSION['user'] = array(
            'login'   => 'test',
            'name'    => 'Test',
            'surname' => 'User'
        );

        echo 'Logged in';
    }

    public function logout()
    {
        unset($_SESSION['user']);

        echo 'Logged out';
    }
}