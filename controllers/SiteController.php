<?php

class SiteController 
{
    protected $dbh;
    protected $viewsPath = __DIR__ . '/../views';

    public function __construct($action)
    {
        if (method_exists($this, $action)) {
            return $this->{$action}();
        } 

        return $this->error404();
    }

    public function requireView($view) 
    {
        require_once $this->viewsPath . '/' . $view . '.php';
    }

    public function auth() 
    {
        if (isset($_POST['password'], $_POST['email'])) {
            $user = User::findBy('email', $_POST['email']);
            if (password_verify($_POST['password'], $user->password_hash)) {
                $_SESSION['user'] = $user->id;
                return $this->cash();

            } else {
                setFlash('error', 'Пользователь не найден!');
            }
        }

        $this->requireView('auth');
    }

    public function cash() 
    {
        $user = User::findBy('id', $_SESSION['user']);

        if (isset($_POST['cash']) && is_integer($_POST['cash'])) {
            try {
                $user->withdraw($_POST['cash']);
            } catch (Exception $e) {
                var_dump($e);
                setFlash('error', $e->getMessage());
            }
        }

        $this->requireView('cash');
    }

    public function error404()
    {
        $this->requireView('error404');
    }

}
