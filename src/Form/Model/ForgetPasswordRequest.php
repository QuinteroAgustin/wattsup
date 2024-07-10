<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ForgetPasswordRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;
}
