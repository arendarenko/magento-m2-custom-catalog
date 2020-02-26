<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Api\Data;

/**
 * Interface CustomProductInterface
 * @package Arendarenko\CustomCatalog\Api\Data
 */
interface CustomProductInterface
{
    public const ENTITY_ID = 'entity_id';

    public const VPN = 'vpn';

    public const SKU = 'sku';

    public const COPYWRITE_INFO = 'copywrite_info';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'updated_at';

    /**
     * @return int|null
     */
    public function getEntityId(): ?string;

    /**
     * @param $entityId
     * @return CustomProductInterface
     */
    public function setEntityId($entityId): self;

    /**
     * @return string|null
     */
    public function getCopyWriteInfo(): ?string;

    /**
     * @param string $value
     * @return CustomProductInterface
     */
    public function setCopyWriteInfo(string $value): self;

    /**
     * @return string|null
     */
    public function getVpn(): ?string;

    /**
     * @param string $value
     * @return CustomProductInterface
     */
    public function setVpn(string $value): self;

    /**
     * @return string|null
     */
    public function getSku(): ?string;

    /**
     * @param string $value
     * @return CustomProductInterface
     */
    public function setSku(string $value): self;

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * @param string $value
     * @return CustomProductInterface
     */
    public function setCreatedAt(string $value): self;

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * @param string $value
     * @return CustomProductInterface
     */
    public function setUpdatedAt(string $value): self;
}
