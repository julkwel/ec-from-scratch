<?php
/**
 * @author <julienrajerison5@gmail.com>
 */

namespace App\Controller\Admin;

use App\Services\EntityServices;
use App\Services\FileUploader;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractBaseAdminController extends AbstractController
{
    /**
     * @var EntityServices
     */
    public $entityServices;
    /**
     * @var FileUploader
     */
    public $fileUploader;
    /**
     * @var UserPasswordHasherInterface
     */
    public $userPasswordHasher;
    /**
     * @var PaginatorInterface
     */
    public $pagination;

    /**
     * @param EntityServices              $entityServices
     * @param FileUploader                $fileUploader
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(EntityServices  $entityServices, FileUploader $fileUploader, UserPasswordHasherInterface $userPasswordHasher, PaginatorInterface $pagination)
    {
        $this->entityServices = $entityServices;
        $this->fileUploader = $fileUploader;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->pagination = $pagination;
    }
}
