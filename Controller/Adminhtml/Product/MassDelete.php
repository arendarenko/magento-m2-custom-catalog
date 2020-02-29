<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Controller\Adminhtml\Product;

use Arendarenko\CustomCatalog\Api\CustomProductRepositoryInterface;
use Arendarenko\CustomCatalog\Controller\Adminhtml\Product as CustomProductController;
use Magento\Ui\Component\MassAction\Filter;
use Arendarenko\CustomCatalog\Model\ResourceModel\CustomProduct\Collection;
use Arendarenko\CustomCatalog\Model\ResourceModel\CustomProduct\CollectionFactory;
use Magento\Backend\App\Action;

/**
 * Class Index
 *
 */
class MassDelete extends CustomProductController
{
    /**
     * @var CustomProductRepositoryInterface
     */
    private $customProductRepository;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * Edit constructor.
     * @param CustomProductRepositoryInterface $customProductRepository
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     * @param Action\Context $context
     */
    public function __construct(
        CustomProductRepositoryInterface $customProductRepository,
        CollectionFactory $collectionFactory,
        Filter $filter,
        Action\Context $context
    ) {
        parent::__construct($context);

        $this->customProductRepository = $customProductRepository;
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Collection $collection */
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        try {
            foreach ($collection as $customProduct) {
                $this->customProductRepository->delete($customProduct);
            }

            $this->messageManager->addSuccessMessage(__(
                'A total of %1 product(s) have been successfully deleted',
                $collectionSize
            ));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->processRedirect();
    }
}
