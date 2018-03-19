<?php

class SiteController 
{
    protected $dbh;
    protected $viewsPath = __DIR__ . '/../views';
    protected $content;

    public function __construct($action)
    {
        if (method_exists($this, $action)) {
            $this->content = $this->{$action}();
        } else {
            $this->content = $this->error404();
        }
    }

    public function getContent()
    {
        return $this->content;
    }

    public function requireView($view, $params = []) 
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        try {
            require $this->viewsPath . '/' . $view . '.php';
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
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

        return $this->requireView('auth');
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

        return $this->requireView('cash', ['user' => $user]);
    }

    public function error404()
    {
        return $this->requireView('error404');
    }

}
