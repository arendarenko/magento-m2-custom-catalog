<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Controller\Adminhtml\Product;

use Arendarenko\CustomCatalog\Api\CustomProductRepositoryInterface;
use Arendarenko\CustomCatalog\Api\Data\CustomProductInterface;
use Arendarenko\CustomCatalog\Api\Data\CustomProductInterfaceFactory;
use Arendarenko\CustomCatalog\Controller\Adminhtml\Product as CustomProductController;
use Arendarenko\CustomCatalog\Helper\Data;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Index
 *
 */
class Save extends CustomProductController
{
    /**
     * @var CustomProductInterfaceFactory
     */
    private $customProductFactory;

    /**
     * @var CustomProductRepositoryInterface
     */
    private $customProductRepository;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Save constructor.
     * @param CustomProductInterfaceFactory $customProductInterfaceFactory
     * @param CustomProductRepositoryInterface $customProductRepository
     * @param DataPersistorInterface $dataPersistor
     * @param Action\Context $context
     */
    public function __construct(
        CustomProductInterfaceFactory $customProductInterfaceFactory,
        CustomProductRepositoryInterface $customProductRepository,
        DataPersistorInterface $dataPersistor,
        Action\Context $context
    ) {
        parent::__construct($context);

        $this->customProductFactory = $customProductInterfaceFactory;
        $this->customProductRepository = $customProductRepository;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if (is_array($data) && !empty($data)) {
            $entity = null;

            $entityId = (int)($data['entity_id'] ?? null);
            $storeId = $data['store_id'] ?? 0;

            $isSaved = false;

            try {
                $entity = $this->getProductEntity($entityId);

                $entity->setVpn($data['vpn'] ?? '')
                    ->setSku($data['sku'] ?? '')
                    ->setCopyWriteInfo($data['copywrite_info'] ?? '')
                    ->setStoreId($storeId);

                $this->validateCustomProduct($entity);

                $this->customProductRepository->save($entity);

                $this->dataPersistor->clear(Data::CUSTOM_PRODUCT_DATA_PERSISTOR_KEY);
                $isSaved = true;

                if (!$entityId) {
                    $successMessage = __('Product was successfully added');
                } else {
                    $successMessage = __('Product was successfully saved');
                }

                $this->messageManager->addSuccessMessage($successMessage);
            } catch (AlreadyExistsException $e) {
                $this->messageManager->addErrorMessage(__('SKU and VPN should be unique'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong during saving product: %1', $e->getMessage()));
            } finally {
                if (!$isSaved) {
                    $this->dataPersistor->set(Data::CUSTOM_PRODUCT_DATA_PERSISTOR_KEY, $data);
                }
                return $this->processRedirect($entity, $storeId);
            }
        }

        return $this->processRedirect();
    }

    /**
     * Provides basic data validation for CustomProduct entity.
     *
     * @param CustomProductInterface $customProduct
     */
    private function validateCustomProduct(CustomProductInterface $customProduct): void
    {
        if ((string)$customProduct->getSku() === '') {
            throw new \InvalidArgumentException(__('SKU should not be empty'));
        }

        if ((string)$customProduct->getVpn() === '') {
            throw new \InvalidArgumentException(__('VPN should not be empty'));
        }
    }

    /**
     * Loads an existing CustomProduct entity if entityId is provided, otherwise creates a new entity.
     *
     * @param int $entityId
     * @return CustomProductInterface
     * @throws NoSuchEntityException
     */
    private function getProductEntity(int $entityId = 0): CustomProductInterface
    {
        return $entityId ? $this->customProductRepository->get($entityId) : $this->customProductFactory->create();
    }
}
