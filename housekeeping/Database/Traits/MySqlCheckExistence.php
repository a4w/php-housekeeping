<?php

namespace Housekeeping\Database\Traits;

use Housekeeping\Database\Database;
use PDO;

trait MySqlCheckExistence
{

    use MySqlPreprocessing;
    public function exists($id)
    {
        $stmt = Database::connection()->prepare("SELECT `id` FROM `{$this->table}` WHERE `id` = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return count($result) === 1;
    }
}
