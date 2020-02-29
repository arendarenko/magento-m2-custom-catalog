<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Model\ResourceModel\CustomProduct;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;

/**
 * Class CustomProduct
 *
 */
class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Arendarenko\CustomCatalog\Model\CustomProduct::class,
            \Arendarenko\CustomCatalog\Model\ResourceModel\CustomProduct::class
        );
    }
}
