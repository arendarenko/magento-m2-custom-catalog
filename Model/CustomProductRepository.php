<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Model;

use Arendarenko\CustomCatalog\Api\CustomProductRepositoryInterface;
use Arendarenko\CustomCatalog\Api\Data\CustomProductInterface;
use Arendarenko\CustomCatalog\Api\Data\CustomProductInterfaceFactory;
use Arendarenko\CustomCatalog\Model\CustomProduct\UpdatePublisher;
use Arendarenko\CustomCatalog\Model\ResourceModel\CustomProduct as CustomProductResource;
use Arendarenko\CustomCatalog\Model\ResourceModel\CustomProduct\Collection;
use Arendarenko\CustomCatalog\Model\ResourceModel\CustomProduct\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class CustomProductRepository
 * @package Arendarenko\CustomCatalog\Model
 */
class CustomProductRepository implements CustomProductRepositoryInterface
{
    /**
     * @var CustomProductInterfaceFactory
     */
    private $customProductFactory;

    /**
     * @var CustomProductResource
     */
    private $customProductResource;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var UpdatePublisher
     */
    private $customProductUpdatePublisher;

    /**
     * CustomProductRepository constructor.
     * @param CustomProductInterfaceFactory $customProductFactory
     * @param CustomProductResource $customProductResource
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param StoreManagerInterface $storeManager
     * @param UpdatePublisher $customProductUpdatePublisher
     */
    public function __construct(
        CustomProductInterfaceFactory $customProductFactory,
        CustomProductResource $customProductResource,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultsFactory,
        StoreManagerInterface $storeManager,
        UpdatePublisher $customProductUpdatePublisher
    ) {
        $this->customProductFactory = $customProductFactory;
        $this->customProductResource = $customProductResource;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->storeManager = $storeManager;
        $this->customProductUpdatePublisher = $customProductUpdatePublisher;
    }

    /**
     * @inheritDoc
     */
    public function save(CustomProductInterface $customProduct): CustomProductInterface
    {
        try {
            $this->customProductResource->save($customProduct);
        } catch (AlreadyExistsException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __(
                    'Could not save custom product: %1',
                    $e->getMessage()
                ),
                $e
            );
        }

        return $customProduct;
    }

    /**
     * @inheritDoc
     */
    public function get(int $id, $storeId = null): CustomProductInterface
    {
        $customProduct = $this->customProductFactory->create();

        if (null !== $storeId) {
            $customProduct->setStoreId((int)$storeId);
        }

        $this->customProductResource->load($customProduct, $id);
        if (!$customProduct->getId()) {
            throw new NoSuchEntityException(
                __(
                    'No such entity with %fieldName = %fieldValue',
                    [
                        'fieldName' => 'id',
                        'fieldValue' => $id
                    ]
                )
            );
        }

        return $customProduct;
    }

    /**
     * @inheritDoc
     */
    public function getByVPN(string $vpn): CustomProductInterface
    {
        return $this->getByAttribute(CustomProductInterface::VPN, $vpn);
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null): SearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('*');
        if ($searchCriteria) {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        /** @var SearchResultsInterface $searchResult */
        $searchResult = $this->searchResultsFactory->create();
        if ($searchCriteria) {
            $searchResult->setSearchCriteria($searchCriteria);
        }
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * @inheritDoc
     */
    public function delete(CustomProductInterface $customProduct): bool
    {
        $customProductId = $customProduct->getId();

        try {
            $this->customProductResource->delete($customProduct);
        } catch (\Exception $e) {
            throw new StateException(
                __(
                    'Cannot delete product with id %1',
                    $customProductId
                ),
                $e
            );
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $entityId): bool
    {
        $customProduct = $this->get($entityId);
        return $this->delete($customProduct);
    }

    /**
     * @inheritDoc
     */
    public function asyncUpdate(CustomProductInterface $customProduct): array
    {
        $entityId = (int)$customProduct->getId();

        if (!$entityId) {
            throw new LocalizedException(__('Product ID is not specified'));
        }

        if (!$this->customProductResource->isProductExist($entityId)) {
            throw new LocalizedException(__('Product with ID = %1 does not exist', $entityId));
        }

        try {
            $this->customProductUpdatePublisher->publish($customProduct);

            return [
                [
                    'message' => 'Message is added to queue. Product will be updated soon'
                ]
            ];
        } catch (\Exception $e) {
            throw new LocalizedException($e->getMessage());
        }
    }

    /**
     * Used to get entities by different attribute ids.
     *
     * @param string $attributeName
     * @param $attributeValue
     * @return CustomProductInterface
     * @throws NoSuchEntityException
     */
    private function getByAttribute(string $attributeName, $attributeValue): CustomProductInterface
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addFieldToFilter($attributeName, $attributeValue);
        $collection->setPageSize(1);

        /** @var CustomProductInterface $customProduct */
        $customProduct = $collection->getFirstItem();

        if (!$customProduct->getId()) {
            throw new NoSuchEntityException(
                __(
                    'No such entity with %fieldName = %fieldValue',
                    [
                        'fieldName' => $attributeName,
                        'fieldValue' => $attributeValue
                    ]
                )
            );
        }

        return $customProduct;
    }
}
