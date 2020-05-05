<?php

namespace Housekeeping\Model\Interfaces;

use Housekeeping\Model\Repository\Repository;

interface PersistentModel
{
    public static function getRepository(): Repository;
    public static function find($id);
    public static function findAll();
    public function save();
    public function delete();
}
