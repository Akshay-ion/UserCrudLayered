<?php

namespace App\DTO;

class UserDTO
{
    public $name;
    public $email;

    public function __construct($name,$email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public static function fromRequest($request)
    {
        return new self(
            $request->name,
            $request->email,
        );
    }
}
