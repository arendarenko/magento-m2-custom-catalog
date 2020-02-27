<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Model\CustomProduct;

use Arendarenko\CustomCatalog\Api\Data\CustomProductInterface;
use Magento\Framework\MessageQueue\PublisherInterface;

/**
 * Class UpdatePublisher
 * @package Arendarenko\CustomCatalog\Model
 */
class UpdatePublisher
{
    private const TOPIC_NAME = 'customcatalog.product.update';

    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * UpdatePublisher constructor.
     * @param PublisherInterface $publisher
     */
    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * Publishes message about custom product update to queue.
     *
     * @param CustomProductInterface $customProduct
     */
    public function publish(CustomProductInterface $customProduct): void {
        $this->publisher->publish(self::TOPIC_NAME, $customProduct);
    }
}
