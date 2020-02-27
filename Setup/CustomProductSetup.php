<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Setup;

use Arendarenko\CustomCatalog\Model\CustomProduct;
use Arendarenko\CustomCatalog\Model\ResourceModel\CustomProduct as CustomProductResource;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;

/**
 * Class CustomProductSetup
 *
 * @package Arendarenko\CustomCatalog\Setup
 */
class CustomProductSetup extends EavSetup
{
    /**
     * @return array
     */
    public function getDefaultEntities()
    {
        return [
            CustomProduct::ENTITY => [
                'entity_model' => CustomProductResource::class,
                'table' => 'arendarenko_customproduct_entity',
                'attributes' => [
                    'vpn' => [
                        'type' => 'static',
                        'label' => 'VPN',
                        'input' => 'text',
                        'unique' => true,
                        'sort_order' => 10,
                    ],
                    'sku' => [
                        'type' => 'static',
                        'label' => 'SKU',
                        'input' => 'text',
                        'unique' => true,
                        'sort_order' => 20,
                    ],
                    'copywrite_info' => [
                        'type' => 'text',
                        'label' => 'Copy Write Info',
                        'input' => 'textarea',
                        'required' => false,
                        'sort_order' => 30,
                        'global' => ScopedAttributeInterface::SCOPE_STORE
                    ],
                    'created_at' => [
                        'type' => 'static',
                        'label' => 'Created At',
                        'input' => 'date',
                        'required' => false,
                        'visible' => false,
                        'sort_order' => 100,
                    ],
                    'updated_at' => [
                        'type' => 'static',
                        'label' => 'Updated at',
                        'input' => 'date',
                        'required' => false,
                        'visible' => false,
                        'sort_order' => 110,
                    ],
                ]
            ]
        ];
    }
}
