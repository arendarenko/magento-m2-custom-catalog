<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Controller\Adminhtml;

use Arendarenko\CustomCatalog\Api\Data\CustomProductInterface;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;

abstract class Product extends Action
{
    //@codingStandardsIgnoreLine The use of public non-interface method in ACTION is discouraged.
    public const ADMIN_RESOURCE = 'Arendarenko_CustomCatalog::manage_products';

    /**
     * Provides redirect depending on request params and entity state (if passed)
     *
     * @param CustomProductInterface $customProduct
     * @param null $storeId
     * @return Redirect
     */
    public function processRedirect(CustomProductInterface $customProduct = null, $storeId = null): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if ($customProduct && $this->getRequest()->getParam('back')) {
            if ($customProduct->getId()) {
                return $resultRedirect->setPath('*/*/edit',
                    ['entity_id' => (int)$customProduct->getId(), 'store' => $storeId]);
            } else {
                return $resultRedirect->setPath('*/*/create', ['store' => $storeId]);
            }
        } else {
            return $resultRedirect->setPath('*/*/');
        }
    }

    /**
     * Initialize backend title
     * @param Page $page
     * @param array $additionalTitles
     */
    public function initMenuAndTitle(Page $page, array $additionalTitles = []): void
    {
        $page->setActiveMenu('Arendarenko_CustomCatalog::arendarenko_custom_catalog_products');
        $page->getConfig()->getTitle()->prepend(__('Custom Catalog'));
        if (!empty($additionalTitles)) {
            foreach ($additionalTitles as $title) {
                $page->getConfig()->getTitle()->prepend(__($title));
            }
        }
    }
}
