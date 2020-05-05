<?php

namespace Housekeeping\Database\Traits;

use Housekeeping\Database\Database;
use PDO;

trait MySqlDelete
{
    use MySqlPreprocessing;
    public function delete($model)
    {
        $stmt = Database::connection()->prepare("DELETE FROM `{$this->table}` WHERE `id` = :id");
        $stmt->bindValue(':id', $model->getID(), PDO::PARAM_INT);
        $stmt->execute();
    }
}
