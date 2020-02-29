<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Controller\Adminhtml\Product;

use Arendarenko\CustomCatalog\Api\CustomProductRepositoryInterface;
use Arendarenko\CustomCatalog\Api\Data\CustomProductInterface;
use Arendarenko\CustomCatalog\Controller\Adminhtml\Product as CustomProductController;
use Arendarenko\CustomCatalog\Model\CustomProduct\RegistryLocator;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;

/**
 * Class Index
 */
class Edit extends CustomProductController
{
    /**
     * @var CustomProductRepositoryInterface
     */
    private $customProductRepository;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * Edit constructor.
     * @param CustomProductRepositoryInterface $customProductRepository
     * @param Registry $registry
     * @param Action\Context $context
     */
    public function __construct(
        CustomProductRepositoryInterface $customProductRepository,
        Registry $registry,
        Action\Context $context
    ) {
        parent::__construct($context);
        $this->customProductRepository = $customProductRepository;
        $this->registry = $registry;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        try {
            $entity = $this->detectProduct();

            $title = 'Add product';

            if ($entity instanceof CustomProductInterface) {
                $this->registry->register(RegistryLocator::CUSTOM_PRODUCT_REGISTRY_KEY, $entity);
                $title = 'Edit product';
            }

            $this->initMenuAndTitle($page, [$title]);

            return $page;
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->processRedirect();
    }

    /**
     * Detects and load product based on request param values, otherwise returns null.
     *
     * @return LocaleInterface|null
     * @throws NoSuchEntityException
     */
    private function detectProduct(): ?CustomProductInterface
    {
        $id = (int)$this->getRequest()->getParam('entity_id');
        $storeId = $this->getRequest()->getParam('store', null);

        return $id ? $this->customProductRepository->get($id, $storeId) : null;
    }
}
