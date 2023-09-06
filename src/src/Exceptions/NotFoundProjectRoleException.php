<?php

declare(strict_types=1);

namespace App\Exceptions;

use LogicException;

class NotFoundProjectRoleException extends LogicException
{
    protected $message = 'Не найдена пользователь Gitlab с необходимой ролью';
}
