<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Model\CustomProduct;

use Arendarenko\CustomCatalog\Api\CustomProductRepositoryInterface;
use Arendarenko\CustomCatalog\Api\Data\CustomProductInterface;
use Psr\Log\LoggerInterface;

/**
 * Class UpdateConsumer
 * @package Arendarenko\CustomCatalog\Model\CustomProduct
 */
class UpdateConsumer
{
    /**
     * @var CustomProductRepositoryInterface
     */
    private $customProductRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UpdateConsumer constructor.
     * @param CustomProductRepositoryInterface $customProductRepository
     * @param LoggerInterface $logger
     */
    public function __construct(CustomProductRepositoryInterface $customProductRepository, LoggerInterface $logger)
    {
        $this->customProductRepository = $customProductRepository;
        $this->logger = $logger;
    }

    /**
     * @param CustomProductInterface $customProduct
     */
    public function process(CustomProductInterface $customProduct): void
    {
        try {
            $this->customProductRepository->save($customProduct);
        } catch (\Exception $e) {
            $this->logger->warning((string)$e,
                ['module' => 'Arendarenko_CustomCatalog', 'method' => 'UpdateConsumer::process']);
        }
    }
}
