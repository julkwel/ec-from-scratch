<?php

namespace App\Manager;

use App\Entity\Provider;
use App\Entity\User;
use App\Services\EntityServices;
use DateTime;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager extends AbstractManager
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    public function __construct(EntityServices $entityServices, UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct($entityServices);
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function generateUser(FormInterface $form, User $user)
    {
        $password = $form->get('password')->getData();
        $user->setIsEnabled(true);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        $user->setRoles(['ROLE_CLIENT']);

        return $user;
    }

    public function generateProvider(Request $request, FormInterface $form, User $user, ?Provider $provider)
    {
        $user = $this->generateUser($form, $user)->setRoles(['ROLE_CLIENT', 'ROLE_PROVIDER']);
        $provider = $provider ?? new Provider();
        $provider->setLabel($request->get('label'));
        $provider->setAdresse($request->get('adresse'));
        $provider->setNifStat($request->get('nif_stat'));
        $provider->setContact($request->get('contact'));
        $provider->setIdProvider($this->generateOrderId());
        $user->setProvider($provider);
        $this->entityServices->save($user);

        return $provider;
    }

    public function generateOrderId()
    {
        return (new DateTime('now'))->getTimestamp();
    }
}
