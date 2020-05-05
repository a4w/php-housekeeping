<?php

namespace App\Model;

use App\Model\Repository\ToiletRepository;
use Housekeeping\Model\Traits\Arrayalize;
use Housekeeping\Model\Interfaces\PersistentModel;
use Housekeeping\Model\Repository\Repository;
use Housekeeping\Model\Traits\PersistentModel as TraitsPersistentModel;
use Tightenco\Collect\Contracts\Support\Arrayable;

class Toilet implements PersistentModel, Arrayable
{
    use TraitsPersistentModel;
    use Arrayalize;

    public static function getRepository(): Repository
    {
        return new ToiletRepository();
    }

    private $name;
    private $price;

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }
}
