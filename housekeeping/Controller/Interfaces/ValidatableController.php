<?php

namespace Housekeeping\Controller\Interfaces;

use Rakit\Validation\Validation;
use Symfony\Component\HttpFoundation\Request;

interface ValidatableController
{
    public function validateRequest(Request $request): Validation;
}