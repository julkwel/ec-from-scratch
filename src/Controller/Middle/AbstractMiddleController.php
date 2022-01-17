<?php
/**
 * @author <julienrajerison5@gmail.com>
 */

namespace App\Controller\Middle;

use App\Services\EntityServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AbstractMiddleController.
 */
abstract class AbstractMiddleController extends AbstractController
{
    /**
     * @var EntityServices
     */
    public $entityServices;

    public function __construct(EntityServices $entityServices)
    {
        $this->entityServices = $entityServices;
    }
}
