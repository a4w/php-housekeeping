<?php

namespace Housekeeping\Model\Repository\Traits;

use Housekeeping\Database\Database;
use PDO;

trait MySqlDelete
{
    use MySqlPreprocessing;
    public function delete($model): Void
    {
        $stmt = Database::connection()->prepare("DELETE FROM `{$this->getTable()}` WHERE `id` = :id");
        $stmt->bindValue(':id', $model->getID(), PDO::PARAM_INT);
        $stmt->execute();
    }
}
