<?php

namespace Housekeeping\Database\Traits;

use Housekeeping\Database\Database;
use PDO;

trait MySqlFind
{
    use MySqlPreprocessing;

    abstract public static function getModel();

    public function find($id)
    {
        $stmt = Database::connection()->prepare("SELECT {$this->columns_str} FROM `{$this->table}` WHERE `id` = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (count($result) === 0) {
            throw new \Exception('Object not found'); // TODO Custom exception
        }
        return call_user_func($this->getModel() . '::from', $result[0]);
    }

    public function findAll()
    {
        $stmt = Database::connection()->prepare("SELECT {$this->columns_str} FROM `{$this->table}`");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $arr = [];
        foreach ($result as $obj) {
            $arr[] =  call_user_func($this->getModel() . '::from', $obj);
        }
        return $arr;
    }
}
