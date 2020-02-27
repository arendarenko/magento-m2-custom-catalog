<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Arendarenko\CustomCatalog\Helper
 */
class Data extends AbstractHelper
{
    private const MODULE_CONFIG_PATH = 'arendarenko_custom_catalog/';

    public const CUSTOM_PRODUCT_DATA_PERSISTOR_KEY = 'custom_catalog_custom_product';

    /**
     * @param null $storeId
     * @return bool
     */
    public function isEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::MODULE_CONFIG_PATH . 'general/is_enabled',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
