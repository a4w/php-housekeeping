<?php

namespace Housekeeping\Models\Interfaces;

interface ValidatableModel
{
    public static function getValidationState();
}
