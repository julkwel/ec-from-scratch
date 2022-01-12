<?php
/**
 * @author <julienrajerison5@gmail.com>
 */

namespace App\Controller\Admin;

use App\Services\EntityServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractBaseAdminController extends AbstractController
{
    /**
     * @var EntityServices
     */
    public $entityServices;

    /**
     * @param EntityServices $entityServices
     */
    public function __construct(EntityServices  $entityServices)
    {
        $this->entityServices = $entityServices;
    }
}
