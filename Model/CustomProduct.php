<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Model;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\AbstractModel;
use Arendarenko\CustomCatalog\Api\Data\CustomProductInterface;
use Arendarenko\CustomCatalog\Model\ResourceModel\CustomProduct as CustomProductResource;
use Arendarenko\CustomCatalog\Model\ResourceModel\CustomProduct\Collection;

/**
 * Class CustomProduct
 *
 */
class CustomProduct extends AbstractModel implements CustomProductInterface
{
    public const ENTITY = 'arendarenko_customproduct_entity';

    protected $_eventPrefix = 'arendarenko_customproduct_entity';

    /**
     * CustomProduct constructor.
     * @param Context $context
     * @param Registry $registry
     * @param CustomProductResource $resource
     * @param Collection $resourceCollection
     * @param array $data
     */
    //@codingStandardsIgnoreLine Possible useless method overriding detected
    public function __construct(
        Context $context,
        Registry $registry,
        CustomProductResource $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @inheritDoc
     */
    public function getId(): ?string
    {
        return $this->_getData(self::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setId($entityId): CustomProductInterface
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function getCopyWriteInfo(): ?string
    {
        return $this->_getData(self::COPYWRITE_INFO);
    }

    /**
     * @inheritDoc
     */
    public function setCopyWriteInfo(string $value): CustomProductInterface
    {
        return $this->setData(self::COPYWRITE_INFO, $value);
    }

    /**
     * @inheritDoc
     */
    public function getVpn(): ?string
    {
        return $this->_getData(self::VPN);
    }

    /**
     * @inheritDoc
     */
    public function setVpn(string $value): CustomProductInterface
    {
        return $this->setData(self::VPN, $value);
    }

    /**
     * @inheritDoc
     */
    public function getSku(): ?string
    {
        return $this->_getData(self::SKU);
    }

    /**
     * @inheritDoc
     */
    public function setSku(string $value): CustomProductInterface
    {
        return $this->setData(self::SKU, $value);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->_getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $value): CustomProductInterface
    {
        return $this->setData(self::CREATED_AT, $value);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): ?string
    {
        return $this->_getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(string $value): CustomProductInterface
    {
        return $this->setData(self::UPDATED_AT, $value);
    }
}
