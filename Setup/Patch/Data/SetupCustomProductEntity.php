<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Setup\Patch\Data;

use Arendarenko\CustomCatalog\Setup\CustomProductSetup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Arendarenko\CustomCatalog\Setup\CustomProductSetupFactory;

/**
 * Class SetupCustomProductEntity
 * @package Arendarenko\CustomCatalog\Setup\Patch\Data
 */
class SetupCustomProductEntity implements DataPatchInterface
{
    /**
     * @var CustomProductSetupFactory
     */
    private $customProductSetupFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * SetupCustomProductEntity constructor.
     * @param CustomProductSetupFactory $customProductSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        CustomProductSetupFactory $customProductSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->customProductSetupFactory = $customProductSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();

        /** @var CustomProductSetup $customProductSetup */
        $customProductSetup = $this->customProductSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customProductSetup->installEntities();

        $this->moduleDataSetup->endSetup();
    }
}
