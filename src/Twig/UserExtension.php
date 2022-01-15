<?php

namespace App\Twig;

use App\Repository\UserRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getFunctions()
    {
        return [
          new TwigFunction('count_all_clients', [$this, 'countAllClients'])
        ];
    }

    public function countAllClients()
    {
        return $this->userRepository->getCountForAllClients();
    }
}
