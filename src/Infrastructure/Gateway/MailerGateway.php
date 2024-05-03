<?php

declare(strict_types=1);

namespace Infrastructure\Gateway;

use Application\Contracts\MailerGatewayInterface;

class MailerGateway implements MailerGatewayInterface
{
    public function send(string $subject, string $recipient, string $message): void
    {
        // TODO: Implement send() method.
    }
}
