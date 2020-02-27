<?php
/**
 * @author  Alexey Arendarenko <alexeya@ven.com>
 * @author VEN Development Team <info@ven.com>
 * @copyright Copyright (c) 2019 VEN Commerce Ltd. (http://www.ven.com)
 */
declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Block\Adminhtml\CustomProduct\Form\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

/**
 * Class Save
 *
 * @package Arendarenko\CustomCatalog\Block\Adminhtml\CustomProduct\Form\Button
 */
class Save extends GenericButton implements ButtonProviderInterface
{
    private const TARGET_NAME = 'arendarenko_customcatalog_product_form.arendarenko_customcatalog_product_form';

    /**
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => self::TARGET_NAME,
                                'actionName' => 'save',
                                'params' => [
                                    false
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'class_name' => Container::SPLIT_BUTTON,
            'options' => $this->getOptions(),
            'sort_order' => 90,
        ];
    }

    /**
     * Retrieve options
     *
     * @return array
     */
    private function getOptions(): array
    {
        return [
            [
                'id_hard' => 'save_and_close',
                'label' => __('Save & Close'),
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => self::TARGET_NAME,
                                    'actionName' => 'save',
                                    'params' => [
                                        true
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        ];
    }
}
