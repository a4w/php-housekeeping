<?php

namespace App\Model\Repository;

use App\Model\Toilet;
use Housekeeping\Model\Repository\Repository;
use Housekeeping\Model\Repository\Traits\MySqlCheckExistence;
use Housekeeping\Model\Repository\Traits\MySqlDelete;
use Housekeeping\Model\Repository\Traits\MySqlFind;
use Housekeeping\Model\Repository\Traits\MySqlSave;
use PDOStatement;

class ToiletRepository implements Repository
{
    use MySqlFind;
    use MySqlDelete;
    use MySqlCheckExistence;
    use MySqlSave;

    public static function getColumns()
    {
        return ['id', 'name', 'price'];
    }

    public static function getTable()
    {
        return 'toilets';
    }

    public static function getModel(): string
    {
        return Toilet::class;
    }

    /**
     * @param Toilet $toilet
     */
    public static function bindModel(PDOStatement $stmt, $toilet)
    {
        $stmt->bindValue(':id', $toilet->getID());
        $stmt->bindValue(':name', $toilet->getName());
        $stmt->bindValue(':price', $toilet->getPrice());
    }
}
