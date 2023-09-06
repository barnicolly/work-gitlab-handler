<?php

declare(strict_types=1);

namespace App\Exceptions;

use LogicException;

class NotFoundProjectRoleException extends LogicException
{
    /** @var string $message */
    protected $message = 'Не найдена пользователь Gitlab с необходимой ролью';
}
