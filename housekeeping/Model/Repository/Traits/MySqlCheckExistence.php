<?php

namespace Housekeeping\Model\Repository\Traits;

use Housekeeping\Database\Database;
use PDO;

trait MySqlCheckExistence
{

    use MySqlPreprocessing;
    public function exists($id): Bool
    {
        $stmt = Database::connection()->prepare("SELECT `id` FROM `{$this->getTable()}` WHERE `id` = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return count($result) === 1;
    }
}
