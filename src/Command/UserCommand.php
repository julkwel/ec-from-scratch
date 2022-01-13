<?php

namespace App\Command;

use App\Entity\User;
use App\Services\EntityServices;
use Couchbase\PasswordAuthenticator;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\PasswordHasherEncoder;

class UserCommand extends Command
{
    protected static $defaultName = 'vokatsoa:user';
    protected static $defaultDescription = 'Add a short description for your command';
    /**
     * @var EntityServices
     */
    private $entityServices;
    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    public function __construct(EntityServices $entityServices, UserPasswordHasherInterface $userPasswordHasher, string $name = null)
    {
        parent::__construct($name);
        $this->entityServices = $entityServices;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    protected function configure(): void
    {
        $this
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Generate fake admin user')
            ->addOption('enable', null, InputOption::VALUE_REQUIRED, 'User state')
            ->addOption('client', null, InputOption::VALUE_NONE, 'Generate fake client user');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $isClient = $input->getOption('client');
        $isAdmin = $input->getOption('admin');

        if ($isAdmin) {
            $user = $this->entityServices->getEntityManager()->getRepository(User::class)->findOneBy(['username' => 'admin']);

            $user = $user ?? new User();
            $user->setUsername('admin');
            $user->setPassword($this->userPasswordHasher->hashPassword($user, '123456'));
            $user->setRoles(['ROLE_ADMIN']);
            $user->setIsEnabled(true);

            if ('false' === $input->getOption('enable')) {
                $user->setIsEnabled(false);
            }

            $this->entityServices->save($user);
        }

        if ($isClient) {
            $user = $this->entityServices->getEntityManager()->getRepository(User::class)->findOneBy(['username' => 'client']);

            $user = $user ?? new User();
            $user->setUsername('client');
            $user->setPassword($this->userPasswordHasher->hashPassword($user, '123456'));
            $user->setRoles(['ROLE_CLIENT']);
            if ('false' === $input->getOption('enable')) {
                $user->setIsEnabled(false);
            }

            $this->entityServices->save($user);
        }

        $io->success('save user.');

        return Command::SUCCESS;
    }
}
