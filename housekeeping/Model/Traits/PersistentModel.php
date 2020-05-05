<?php

namespace Housekeeping\Model\Traits;

use Housekeeping\Model\Interfaces\PersistentModel as InterfacesPersistentModel;
use Housekeeping\Model\Repository\Repository;
use ReflectionClass;

trait PersistentModel
{
    private ?Int $id;

    abstract public static function getRepository(): Repository;
    public static function getRelations(): array
    {
        return [];
    }

    public function setID(Int $id)
    {
        $this->id = $id;
    }

    public function getID()
    {
        return $this->id;
    }

    /**
     * @return $this
     */
    public static function from($attr)
    {
        $class = self::class;
        $class_vars = get_class_vars($class);
        $model = new $class;
        $relations = self::getRelations();
        foreach ($class_vars as $key => $val) {
            if (array_key_exists($key, $relations)) {
                // Attempt to create object
                $relation_class = $relations[$key];
                $reflection = new ReflectionClass($relation_class);
                if ($reflection->implementsInterface(InterfacesPersistentModel::class)) {
                    $model->$key = $relation_class::find($attr[$key]);
                }
            } else {
                $model->$key = $attr[$key] ?? $val;
            }
        }
        return $model;
    }

    /**
     * @return $this
     */
    public static function find($id)
    {
        return self::getRepository()->find($id);
    }

    /**
     * @return $this
     */
    public static function exists($id)
    {
        return self::getRepository()->exists($id);
    }

    /**
     * @return $this[]
     */
    public static function findAll()
    {
        return self::getRepository()->findAll();
    }

    public function save()
    {
        return self::getRepository()->save($this);
    }

    public function delete()
    {
        return self::getRepository()->delete($this);
    }
}
