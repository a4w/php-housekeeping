<?php

namespace Housekeeping\Model\Repository\Traits;

use Housekeeping\Database\Database;
use Housekeeping\Model\Repository\Traits\MySqlCheckExistence;
use PDOStatement;

trait MySqlSave
{
    use MySqlCheckExistence;

    abstract static function bindModel(PDOStatement $stmt, $model);
    abstract public static function getModel();

    // TODO: Handle errors
    public function store($model): Void
    {
        $stmt = Database::connection()->prepare("INSERT INTO `{$this->getTable()}` ({$this->columns_str}) VALUES ({$this->bind_str})");
        self::bindModel($stmt, $model);
        $stmt->execute();
        $model->setID(Database::connection()->lastInsertId());
    }

    public function update($model): Void
    {
        $stmt = Database::connection()->prepare("UPDATE `{$this->getTable()}` SET {$this->update_bind_str} WHERE `id` = :id");
        self::bindModel($stmt, $model);
        $stmt->execute();
    }

    public function save($model): Void
    {
        if ($this->exists($model->getID())) {
            $this->update($model);
        } else {
            $this->store($model);
        }
    }
}
