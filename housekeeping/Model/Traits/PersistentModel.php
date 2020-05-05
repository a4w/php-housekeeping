<?php

namespace Housekeeping\Models\Traits;

use Housekeeping\Database\Repository;

trait PersistentModel
{
    private ?Int $id;

    abstract public static function getRepository(): Repository;

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
        foreach ($class_vars as $key => $val) {
            $model->$key = $attr[$key] ?? $val;
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
