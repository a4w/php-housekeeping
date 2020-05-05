<?php

namespace Housekeeping\Model\Traits;

trait Arrayalize
{
    private function getHidden()
    {
        return [];
    }
    public function toArray()
    {
        $array = [];
        $class_vars = get_object_vars($this);
        $hidden = $this->getHidden();
        foreach ($class_vars as $key => $val) {
            if (in_array($key, $hidden))
                continue;
            $array[$key] = $val;
        }
        return $array;
    }
    public function toArrayIgnoreHidden()
    {
        $array = [];
        $class_vars = get_object_vars($this);
        foreach ($class_vars as $key => $val) {
            $array[$key] = $val;
        }
        return $array;
    }
}
