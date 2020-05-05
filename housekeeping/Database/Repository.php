<?php

namespace Housekeeping\Database;


interface Repository
{
    public static function getModel(): String;
    public function find($id);
    public function exists($id): Bool;
    public function findAll();
    public function save($model): Void;
    public function delete($model): Void;
}
