<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Ui\Component\CustomProduct;

use Arendarenko\CustomCatalog\Api\Data\CustomProductInterface;
use Arendarenko\CustomCatalog\Api\Data\CustomProductInterfaceFactory;
use Arendarenko\CustomCatalog\Model\CustomProduct\RegistryLocator;
use Arendarenko\CustomCatalog\Helper\Data;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as BaseDataProvider;

/**
 * Class DataProvider
 *
 *
 */
class DataProvider extends BaseDataProvider
{
    /**
     * @var RegistryLocator
     */
    private $customProductRegistryLocator;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var CustomProductInterfaceFactory
     */
    private $customProductFactory;

    /**
     * DataProvider constructor.
     * @param RegistryLocator $customProductRegistryLocator
     * @param DataPersistorInterface $dataPersistor
     * @param CustomProductInterfaceFactory $customProductFactory
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        RegistryLocator $customProductRegistryLocator,
        DataPersistorInterface $dataPersistor,
        CustomProductInterfaceFactory $customProductFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );

        $this->customProductRegistryLocator = $customProductRegistryLocator;
        $this->dataPersistor = $dataPersistor;
        $this->customProductFactory = $customProductFactory;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $data = [];

        $entity = $this->customProductRegistryLocator->locate();
        if (!$entity instanceof CustomProductInterface) {
            $entity = $this->customProductFactory->create();
        }

        $this->loadPersistedData($entity);
        $entityData = $entity->getData();

        if (!empty($entityData)) {
            $data[$entity->getId()] = $entity->getData();
        }

        return $data;
    }

    /**
     * Checks for persisted data and fills custom product entity with it, then cleans persisted data
     * @param CustomProductInterface $customProduct
     */
    private function loadPersistedData(CustomProductInterface $customProduct): void
    {
        $persistedData = $this->dataPersistor->get(Data::CUSTOM_PRODUCT_DATA_PERSISTOR_KEY);
        if (!empty($persistedData)) {
            $customProduct->setData($persistedData);
            $this->dataPersistor->clear(Data::CUSTOM_PRODUCT_DATA_PERSISTOR_KEY);
        }
    }
}
