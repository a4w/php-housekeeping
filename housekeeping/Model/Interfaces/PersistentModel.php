<?php

namespace Housekeeping\Models\Interfaces;

use Housekeeping\Database\Repository;

interface PersistentModel
{
    public static function getRepository(): Repository;
    public static function find($id);
    public static function findAll();
    public function save();
    public function delete();
}
