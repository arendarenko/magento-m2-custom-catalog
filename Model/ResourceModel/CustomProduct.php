<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Model\ResourceModel;

use Magento\Eav\Model\Entity\AbstractEntity;

/**
 * Class CustomProduct
 * @package Arendarenko\CustomCatalog\Model\ResourceModel
 */
class CustomProduct extends AbstractEntity
{
    protected function _construct()
    {
        $this->setType('arendarenko_customproduct_entity');
    }
}
