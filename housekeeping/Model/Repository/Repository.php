<?php

namespace Housekeeping\Model\Repository;


interface Repository
{
    public function find($id);
    public function exists($id): Bool;
    public function findAll();
    public function store($model): Void;
    public function update($model): Void;
    public function save($model): Void;
    public function delete($model): Void;
}
