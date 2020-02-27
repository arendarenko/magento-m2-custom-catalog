<?php
/**
 * @author  Alexey Arendarenko <alexeya@ven.com>
 * @author VEN Development Team <info@ven.com>
 * @copyright Copyright (c) 2019 VEN Commerce Ltd. (http://www.ven.com)
 */
declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Ui\Component\Listing\Column;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Action
 *
 * @package Ven\TranslateCustomOptions\Ui\Component\Banner\Listing
 */
class Actions extends Column
{
    /** @var UrlInterface */
    private $url;

    /**
     * Action constructor.
     *
     * @param UrlInterface       $url
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        UrlInterface $url,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->url = $url;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        $editUrlPath        = $this->getData('config/editUrlPath') ?: '#';
        $deleteUrlPath        = $this->getData('config/deleteUrlPath') ?: '#';
        $urlEntityParamName = $this->getData('config/urlEntityParamName') ?: 'entity_id';

        if (isset($dataSource['data']['items'])) {
            $itemName = $this->getData('name');

            foreach ($dataSource['data']['items'] as &$item) {
                $entityId = $item['entity_id'] ?? 0;
                if(!$entityId) {
                    continue;
                }

                $item[$itemName]['edit'] = [
                    'href'  => $this->url->getUrl(
                        $editUrlPath,
                        [$urlEntityParamName => $item['entity_id']]
                    ),
                    'label' => __('Edit')
                ];

                $item[$itemName]['delete'] = [
                    'href'  => $this->url->getUrl(
                        $deleteUrlPath,
                        [$urlEntityParamName => $item['entity_id']]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete product'),
                        'message' => __('Are you sure you wan\'t to delete product with ID: ${ $.$data.entity_id }?')
                    ]
                ];
            }
        }

        return $dataSource;
    }
}
