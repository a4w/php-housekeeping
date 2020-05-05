<?php

namespace App\Model\Repository;

use App\Model\Maker;
use Housekeeping\Model\Repository\Repository;
use Housekeeping\Model\Repository\Traits\MySqlCheckExistence;
use Housekeeping\Model\Repository\Traits\MySqlDelete;
use Housekeeping\Model\Repository\Traits\MySqlFind;
use Housekeeping\Model\Repository\Traits\MySqlSave;
use PDOStatement;

class MakerRepository implements Repository
{
    use MySqlFind;
    use MySqlDelete;
    use MySqlCheckExistence;
    use MySqlSave;

    public static function getColumns()
    {
        return ['id', 'name'];
    }

    public static function getTable()
    {
        return 'makers';
    }

    public static function getModel(): string
    {
        return Maker::class;
    }

    /**
     * @param Maker $maker
     */
    public static function bindModel(PDOStatement $stmt, $maker)
    {
        $stmt->bindValue(':id', $maker->getID());
        $stmt->bindValue(':name', $maker->getName());
    }
}
