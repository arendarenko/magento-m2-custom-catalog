<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Model\CustomProduct;

use Arendarenko\CustomCatalog\Api\Data\CustomProductInterface;
use Magento\Framework\Registry;

/**
 * Class RegistryLocator
 * @package Arendarenko\CustomCatalog\Model
 */
class RegistryLocator
{
    public const CUSTOM_PRODUCT_REGISTRY_KEY = 'custom_catalog_custom_product';

    /**
     * @var Registry
     */
    private $registry;

    /**
     * CustomProductLocator constructor.
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Locates CustomProduct entity in registry and returns it, otherwise returns null
     *
     * @return CustomProductInterface|null
     */
    public function locate(): ?CustomProductInterface {
        $entity = $this->registry->registry(self::CUSTOM_PRODUCT_REGISTRY_KEY);

        //Additional check in order to prevent unexpected value
        return $entity instanceof CustomProductInterface ? $entity : null;
    }
}
