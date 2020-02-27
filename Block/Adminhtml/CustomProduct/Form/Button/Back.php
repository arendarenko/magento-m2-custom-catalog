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
 * Class Back
 *
 * @package Arendarenko\CustomCatalog\Block\Adminhtml\CustomProduct\Form\Button
 */
class Back extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label'      => __('Back'),
            'on_click'   => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class'      => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    private function getBackUrl(): string
    {
        return $this->getUrl('*/*/');
    }
}
