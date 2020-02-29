<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Controller\Adminhtml\Product;

use Arendarenko\CustomCatalog\Api\CustomProductRepositoryInterface;
use Arendarenko\CustomCatalog\Controller\Adminhtml\Product as CustomProductController;
use Magento\Backend\App\Action;

/**
 * Class Index
 */
class Delete extends CustomProductController
{
    /**
     * @var CustomProductRepositoryInterface
     */
    private $customProductRepository;

    /**
     * Edit constructor.
     * @param CustomProductRepositoryInterface $customProductRepository
     * @param Action\Context $context
     */
    public function __construct(
        CustomProductRepositoryInterface $customProductRepository,
        Action\Context $context
    ) {
        parent::__construct($context);
        $this->customProductRepository = $customProductRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('entity_id');

        try {
            $this->customProductRepository->deleteById($id);

            $this->messageManager->addSuccessMessage('Product was sucessfully deleted');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->processRedirect();
    }
}
