<?php

class SiteController 
{
    protected $dbh;
    protected $viewsPath = __DIR__ . '/../views';

    public function __construct($action)
    {
        $this->dbh = (new Db())->getConnection();

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
            $sth = $this->dbh->prepare('SELECT * FROM users WHERE email = ?');
            $sth->execute([$_POST['email']]);
            $result = $sth->fetchAll();

            if (password_verify($_POST['password'], $result[0]['password_hash'])) {
                $_SESSION['user'] = $result[0]['id'];

                return $this->cash();
            }
        }

        $this->requireView('auth');
    }

    public function cash() 
    {
        $this->requireView('cash');
    }

    public function error404()
    {
        $this->requireView('error404');
    }

}
