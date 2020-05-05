<?php

namespace App\Controller;

use Housekeeping\Controller\Interfaces\ValidatableController;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator;
use Symfony\Component\HttpFoundation\Request;

class WelcomeController implements ValidatableController
{
    public function validateRequest(Request $request): Validation
    {
        $validator = new Validator();
        $validation = $validator->make($request->query->all(), [
            'name' => ['required', 'min:4', 'max:20']
        ]);
        $validation->validate();
        return $validation;
    }

    public function greet(Request $request)
    {
        // Get name, We know it's here due to validation
        $name = $request->query->get('name');
        // Escape
        $name = htmlentities($name);
        // Greet user
        return 'Hello <b>' . $name . '</b>';
    }

    public function greetAdmin(Request $request)
    {
        return 'This is super secret, Your name is ' . $request->query->get('name');
    }
}
