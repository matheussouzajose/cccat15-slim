<?php

declare(strict_types=1);

namespace Application\Contracts;

interface MailerGatewayInterface
{
    public function send(string $subject, string $recipient, string $message): void;
}
