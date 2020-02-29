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
 *
 */
class CustomProduct extends AbstractResource
{
    protected function _construct()
    {
        $this->setType('arendarenko_customproduct_entity');
    }

    /**
     * Checks if product with provided id is exists.
     *
     * Using this approach we won't affect whole entity, but just one column in main table.
     *
     * @param int $entityId
     * @return bool
     * @throws \Exception
     */
    public function isProductExist(int $entityId): bool
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getEntityTable(), 'entity_id')->where('entity_id = :entity_id');
        $result = $connection->fetchOne($select, [':entity_id' => $entityId]);

        return (bool)$result;
    }
}
