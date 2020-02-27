<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Api;

use Arendarenko\CustomCatalog\Api\Data\CustomProductInterface;
use Arendarenko\CustomCatalog\Api\Data\CustomProductSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;

/**
 * Interface CustomProductRepositoryInterface
 *
 * @package Arendarenko\CustomCatalog\Api
 */
interface CustomProductRepositoryInterface
{
    /**
     * Save CustomProduct
     * @param CustomProductInterface $customProduct
     * @return CustomProductInterface
     * @throws CouldNotSaveException
     * @throws AlreadyExistsException
     */
    public function save(
        CustomProductInterface $customProduct
    ): CustomProductInterface;

    /**
     * Anync update CustomProduct using Message Queue
     *
     * @param \Arendarenko\CustomCatalog\Api\Data\CustomProductInterface $customProduct
     * @return array
     */
    public function asyncUpdate(
        \Arendarenko\CustomCatalog\Api\Data\CustomProductInterface $customProduct
    ): array;

    /**
     * Retrieve CustomProduct
     *
     * @param int $id
     * @return CustomProductInterface
     * @throws NoSuchEntityException
     */
    public function get(int $id): CustomProductInterface;

    /**
     * @param string $vpn
     * @return \Arendarenko\CustomCatalog\Api\Data\CustomProductInterface
     */
    public function getByVPN(string $vpn): CustomProductInterface;

    /**
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null): SearchResultsInterface;

    /**
     * Delete CustomProduct
     * @param CustomProductInterface $customProduct
     * @return bool true on success
     * @throws StateException
     */
    public function delete(CustomProductInterface $customProduct): bool;

    /**
     * Delete CustomProduct by ID
     * @param int $entityId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws StateException
     */
    public function deleteById(int $entityId): bool;
}
