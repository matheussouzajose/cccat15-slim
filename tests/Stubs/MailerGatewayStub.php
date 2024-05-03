<?php

declare(strict_types=1);

namespace Tests\Stubs;

use Application\Contracts\MailerGatewayInterface;

class MailerGatewayStub implements MailerGatewayInterface
{
    public function send(string $subject, string $recipient, string $message): void
    {
        // TODO: Implement send() method.
    }
}
