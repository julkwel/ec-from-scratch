<?php
/**
 * @author <julienrajerison5@gmail.com>
 */

namespace App\Controller\Front;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxonRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AbstractBaseFrontController.
 */
abstract class AbstractBaseFrontController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    public $productRepository;
    /**
     * @var TaxonRepository
     */
    public $taxonRepository;
    /**
     * @var UserRepository
     */
    public $userRepository;
    /**
     * @var OrderRepository
     */
    public $orderRepository;
    /**
     * @var PaginatorInterface
     */
    public $paginator;

    public function __construct(ProductRepository $productRepository, TaxonRepository $taxonRepository, UserRepository $userRepository, OrderRepository $orderRepository, PaginatorInterface $paginator)
    {
        $this->productRepository = $productRepository;
        $this->taxonRepository = $taxonRepository;
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
        $this->paginator = $paginator;
    }
}
