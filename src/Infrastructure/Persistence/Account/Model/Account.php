<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Account\Model;

use Infrastructure\Persistence\Model;

class Account extends Model
{
    protected string $table = 'account';
    protected array $protected = ['id', 'created_at', 'updated_at'];
    protected array $required = ['name', 'email', 'cpf'];
}
