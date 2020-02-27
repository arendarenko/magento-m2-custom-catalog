<?php
/**
 * @author  Alexey Arendarenko <alexeya@ven.com>
 * @author VEN Development Team <info@ven.com>
 * @copyright Copyright (c) 2019 VEN Commerce Ltd. (http://www.ven.com)
 */
declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Block\Adminhtml\CustomProduct\Form\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Delete
 *
 * @package Arendarenko\CustomCatalog\Block\Adminhtml\CustomProduct\Form\Button
 */
class Delete extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        $data = [];

        $id = $this->getEntityId();
        if ($id) {
            $deleteConfirm = "deleteConfirm('" . __('Are you sure you want to delete product?') . "'";
            $deleteConfirm .= ", '" . $this->getDeleteUrl($id) . "', {\"data\": {}})";

            $data = [
                'label'      => __('Delete'),
                'class'      => 'delete',
                'on_click'   => $deleteConfirm,
                'sort_order' => 20,
            ];
        }

        return $data;
    }

    /**
     * Url to send delete requests to.
     *
     * @param int $entityId
     * @return string
     */
    private function getDeleteUrl(int $entityId): string
    {
        return $this->getUrl('*/*/delete', ['entity_id' => $entityId]);
    }
}
