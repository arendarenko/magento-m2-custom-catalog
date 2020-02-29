<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Plugin\Magento\Backend\Block\Store;

use Magento\Backend\Block\Store\Switcher as OriginalSwitcher;
use Magento\Framework\App\RequestInterface;

/**
 * Class Switcher
 *
 * This plugin is designed to hide store switcher block when creating new custom product.
 *
 * @package Arendarenko\CustomCatalog\Plugin\Magento\Backend\Block\Store
 */
class Switcher
{
    private const CUSTOMCATALOG_EDIT_ACTION_NAME = 'arendarenko_customcatalog_product_edit';

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Store constructor.
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param OriginalSwitcher $subject
     * @param $result
     * @return mixed
     */
    public function afterIsShow(
        OriginalSwitcher $subject,
        $result
    ) {
        if(!(bool)$result) {
            return $result;
        }

        $fullActionName = $this->request->getFullActionName();
        if($fullActionName !== self::CUSTOMCATALOG_EDIT_ACTION_NAME) {
            return $result;
        }

        $entityId = (int)$this->request->getParam('entity_id');
        if(!$entityId) {
            $result = false;
        }

        return $result;
    }
}
