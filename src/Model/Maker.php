<?php

namespace App\Model;

use App\Model\Repository\MakerRepository;
use Housekeeping\Model\Traits\Arrayalize;
use Housekeeping\Model\Interfaces\PersistentModel;
use Housekeeping\Model\Repository\Repository;
use Housekeeping\Model\Traits\PersistentModel as TraitsPersistentModel;
use Tightenco\Collect\Contracts\Support\Arrayable;

class Maker implements PersistentModel, Arrayable
{
    use TraitsPersistentModel;
    use Arrayalize;

    public static function getRepository(): Repository
    {
        return new MakerRepository();
    }

    private $name;

    public function getName()
    {
        return $this->name;
    }
}
