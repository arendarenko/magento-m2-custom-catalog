<?php
/**
 * @author  Alexey Arendarenko <alexeya@ven.com>
 * @author VEN Development Team <info@ven.com>
 * @copyright Copyright (c) 2019 VEN Commerce Ltd. (http://www.ven.com)
 */
declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Block\Adminhtml\CustomProduct\Form\Button;

use Arendarenko\CustomCatalog\Model\CustomProduct\RegistryLocator;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\UrlInterface;

/**
 * Class GenericButton
 *
 * @package Arendarenko\CustomCatalog\Block\Adminhtml\CustomProduct\Form\Button
 */
class GenericButton
{
    /**
     * Url Builder
     *
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var RegistryLocator
     */
    private $customProductRegistryLocator;

    /**
     * Constructor
     *
     * @param RegistryLocator $customProductRegistryLocator
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        RegistryLocator $customProductRegistryLocator,
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->customProductRegistryLocator = $customProductRegistryLocator;
    }

    /**
     * Locates and returns custom product entity id in order to use it in backend buttons.
     *
     * @return int|null
     */
    public function getEntityId(): ?int
    {
        $entity = $this->customProductRegistryLocator->locate();

        return $entity !== null ? (int)$entity->getId() : null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array  $params
     * @return  string
     */
    public function getUrl($route = '', array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
