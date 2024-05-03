<?php

declare(strict_types=1);

namespace Application\Auth\Command;

use Application\Contracts\MailerGatewayInterface;
use Domain\Account\Contracts\AccountRepositoryInterface;
use Domain\Account\Entity\Account;
use Domain\Account\Exception\AccountException;

class SignUpHandler
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly MailerGatewayInterface $mailerGateway
    ) {
    }

    public function __invoke(SignUp $command): object
    {
        if ($this->accountRepository->existEmail(email: $command->getEmail())) {
            throw AccountException::alreadyExist();
        }
        $account = Account::create(
            name: $command->getName(),
            email: $command->getEmail(),
            cpf: preg_replace('/\D/', '', $command->getCpf()),
            isPassenger: $command->isPassenger(),
            isDriver: $command->isDriver(),
            carPlate: $command->getCarPlate()
        );
        $output = $this->accountRepository->create(account: $account);
        $this->sendMail(email: $account->email());
        return (object)['accountId' => $output->accountId()];
    }

    private function sendMail(string $email): void
    {
        $this->mailerGateway->send(
            subject: 'Welcome',
            recipient: $email,
            message: 'Use this link to confirm your account'
        );
    }
}
