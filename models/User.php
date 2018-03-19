<?php
class User
{
    protected $dbh;
    protected static $tableName = 'users';

    public $id;
    public $email;
    public $password_hash;
    public $cash;

    protected static $fields = [
        'id', 'email', 'password_hash', 'cash'
    ];

    public function __construct()
    {
        $this->dbh = static::getConnection();
    }

    protected static function getConnection()
    {
        return (new Db())->getConnection();
    }

    public static function findBy($attr, $value)
    {
        $dbh = static::getConnection();
        $sth = $dbh->prepare('SELECT * FROM ' . static::$tableName . ' WHERE ' . $attr . ' = ?');
        $sth->execute([$value]);
        $result = $sth->fetchAll();

        if (count($result) > 0) {
            $model = new static();
            foreach (static::$fields as $field) {
                $model->{$field} = $result[0][$field];
            }
            return $model;
        }

        return null;
    }

    protected function update()
    {
        $model = static::findBy('id', $this->id);
        foreach (static::$fields as $field) {
            $this->{$field} = $model->{$field};
        }
    }

    public function withdraw($count)
    {
        if (!filter_var($count, FILTER_VALIDATE_INT, ['options' => [
            'min_range' => 0
        ]])) {
            throw new Exception('Количество выводимых денег должно быть положительным числом!');
        }

        if ($this->cash - $count < 0) {
            throw new Exception('У вас не хватает денег!');
        }

        $this->dbh->beginTransaction();

        $sth = $this->dbh->prepare('UPDATE ' . self::$tableName . '
            SET cash = cash - ?');
        $sth->execute([$count]);
        
        $this->dbh->commit();

        $this->update();

        return true;
    }
}