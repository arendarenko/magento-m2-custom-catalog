<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Model\ResourceModel;

use Magento\Eav\Model\Entity\AbstractEntity;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Eav\Model\Entity\Attribute\UniqueValidationInterface;
use Magento\Eav\Model\Entity\Context;
use Magento\Framework\DataObject;
use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class AbstractResource
 * @package Arendarenko\CustomCatalog\Model\ResourceModel
 */
abstract class AbstractResource extends AbstractEntity
{
    /** @var StoreManagerInterface */
    private $storeManager;

    /**
     * @param Context                        $context
     * @param StoreManagerInterface          $storeManager
     * @param array                          $data
     * @param UniqueValidationInterface|null $uniqueValidator
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        $data = [],
        UniqueValidationInterface $uniqueValidator = null
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context, $data, $uniqueValidator);
    }

    /**
     * Returns default Store ID
     *
     * @return int
     */
    public function getDefaultStoreId(): int
    {
        return Store::DEFAULT_STORE_ID;
    }

    /**
     * Retrieve select object for loading entity attributes values
     *
     * Join attribute store value
     *
     * @param DataObject $object
     * @param string     $table
     * @return Select
     * @throws NoSuchEntityException
     */
    protected function _getLoadAttributesSelect($object, $table): Select
    {
        /**
         * This condition is applicable for all cases when we was work in not single
         * store mode, customize some value per specific store view and than back
         * to single store mode. We should load correct values
         */
        if ($this->storeManager->hasSingleStore()) {
            $storeId = (int)$this->storeManager->getStore(true)->getId();
        } else {
            $storeId = (int)$object->getStoreId();
        }

        $storeIds = [$this->getDefaultStoreId()];
        if ($storeId !== $this->getDefaultStoreId()) {
            $storeIds[] = $storeId;
        }

        return $this->getConnection()
            ->select()
            ->from(['attr_table' => $table], [])
            ->where("attr_table.{$this->getLinkField()} = ?", $object->getData($this->getLinkField()))
            ->where('attr_table.store_id IN (?)', $storeIds);
    }

    /**
     * Prepare select object for loading entity attributes values
     *
     * @param array $selects
     * @return Select
     */
    protected function _prepareLoadSelect(array $selects): Select
    {
        $select = parent::_prepareLoadSelect($selects);
        $select->order('store_id');

        return $select;
    }

    /**
     * Insert or Update attribute data
     *
     * @param AbstractModel     $object
     * @param AbstractAttribute $attribute
     * @param mixed             $value
     * @return $this
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function _saveAttributeValue($object, $attribute, $value): self
    {
        $connection = $this->getConnection();
        $hasSingleStore = $this->storeManager->hasSingleStore();

        if($hasSingleStore || !$object->getStoreId()) {
            $storeId = $this->getDefaultStoreId();
        } else {
            $storeId = (int)$this->storeManager->getStore($object->getStoreId())->getId();
        }

        $table = $attribute->getBackend()->getTable();

        /**
         * If we work in single store mode all values should be saved just
         * for default store id
         * In this case we clear all not default values
         */
        $entityIdField = $this->getLinkField();
        $conditions = [
            'attribute_id = ?'     => $attribute->getAttributeId(),
            "{$entityIdField} = ?" => $object->getData($entityIdField),
            'store_id <> ?'        => $storeId
        ];
        if ($hasSingleStore
            && !$object->isObjectNew()
            && $this->isAttributePresentForNonDefaultStore($attribute, $conditions)
        ) {
            $connection->delete(
                $table,
                $conditions
            );
        }

        $data = new DataObject(
            [
                'attribute_id' => $attribute->getAttributeId(),
                'store_id'     => $storeId,
                $entityIdField => $object->getData($entityIdField),
                'value'        => $this->_prepareValueForSave($value, $attribute),
            ]
        );
        $bind = $this->_prepareDataForTable($data, $table);

        $this->_attributeValuesToSave[$table][] = $bind;

        return $this;
    }

    /**
     * Update entity attribute value
     *
     * @param AbstractModel     $object
     * @param AbstractAttribute $attribute
     * @param mixed             $valueId
     * @param mixed             $value
     * @return AbstractResource
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _updateAttribute($object, $attribute, $valueId, $value): self
    {
        return $this->_saveAttributeValue($object, $attribute, $value);
    }

    /**
     * Check if attribute present for non default Store View.
     *
     * Prevent "delete" query locking in a case when nothing to delete
     *
     * @param AbstractAttribute $attribute
     * @param array             $conditions
     *
     * @return boolean
     * @throws LocalizedException
     */
    private function isAttributePresentForNonDefaultStore($attribute, $conditions): bool
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($attribute->getBackend()->getTable());
        foreach ($conditions as $condition => $conditionValue) {
            $select->where($condition, $conditionValue);
        }
        $select->limit(1);

        return !empty($connection->fetchRow($select));
    }

    /**
     * Insert entity attribute value
     *
     * @param DataObject        $object
     * @param AbstractAttribute $attribute
     * @param mixed             $value
     * @return $this
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function _insertAttribute($object, $attribute, $value): self
    {
        /**
         * save required attributes in global scope every time if store id different from default
         */
        $storeId = (int)$this->storeManager->getStore($object->getStoreId())->getId();
        if ($this->getDefaultStoreId() !== $storeId) {
            if ($attribute->getIsRequired() || $attribute->getIsRequiredInAdminStore()) {
                $table = $attribute->getBackend()->getTable();

                $select = $this->getConnection()->select()
                    ->from($table)
                    ->where('attribute_id = ?', $attribute->getAttributeId())
                    ->where('store_id = ?', $this->getDefaultStoreId())
                    ->where($this->getLinkField() . ' = ?', $object->getData($this->getLinkField()));
                $row = $this->getConnection()->fetchOne($select);

                if (!$row) {
                    $data = new DataObject(
                        [
                            'attribute_id'        => $attribute->getAttributeId(),
                            'store_id'            => $this->getDefaultStoreId(),
                            $this->getLinkField() => $object->getData($this->getLinkField()),
                            'value'               => $this->_prepareValueForSave($value, $attribute),
                        ]
                    );
                    $bind = $this->_prepareDataForTable($data, $table);
                    $this->getConnection()->insertOnDuplicate($table, $bind, ['value']);
                }
            }
        }

        return $this->_saveAttributeValue($object, $attribute, $value);
    }

    /**
     * Delete entity attribute values
     *
     * @param DataObject $object
     * @param string     $table
     * @param array      $info
     * @return $this
     */
    protected function _deleteAttributes($object, $table, $info): self
    {
        $connection = $this->getConnection();
        $entityIdField = $this->getLinkField();
        $storeAttributes = [];

        /**
         * Separate attributes by scope
         */
        foreach ($info as $itemData) {
            $storeAttributes[] = (int)$itemData['attribute_id'];
        }

        $condition = [
            $entityIdField . ' = ?' => $object->getId(),
        ];

        /**
         * Delete store scope attributes
         */
        if (!empty($storeAttributes)) {
            $delCondition = $condition;
            $delCondition['attribute_id IN(?)'] = $storeAttributes;
            $delCondition['store_id = ?'] = (int)$object->getStoreId();

            $connection->delete($table, $delCondition);
        }

        return $this;
    }

    /**
     * Return if attribute exists in original data array.
     * Checks also attribute's store scope:
     * We should insert on duplicate key update values if we unchecked 'STORE VIEW' checkbox in store view.
     *
     * @param AbstractAttribute $attribute
     * @param mixed             $value New value of the attribute.
     * @param array &           $origData
     * @return bool
     */
    protected function _canUpdateAttribute(AbstractAttribute $attribute, $value, array &$origData): bool
    {
        $result = parent::_canUpdateAttribute($attribute, $value, $origData);
        if ($result
            && isset($origData['store_id'])
            && $origData['store_id'] !== $this->getDefaultStoreId()
            && !$this->_isAttributeValueEmpty($attribute, $value)
            && $value === $origData[$attribute->getAttributeCode()]
        ) {
            return false;
        }

        return $result;
    }
}
