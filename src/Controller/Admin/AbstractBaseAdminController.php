<?php
/**
 * @author <julienrajerison5@gmail.com>
 */

namespace App\Controller\Admin;

use App\Services\EntityServices;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @param EntityServices $entityServices
     * @param FileUploader   $fileUploader
     */
    public function __construct(EntityServices  $entityServices, FileUploader $fileUploader)
    {
        $this->entityServices = $entityServices;
        $this->fileUploader = $fileUploader;
    }
}
