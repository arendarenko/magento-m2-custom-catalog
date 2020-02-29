<?php
/**
 * Copyright © Alexey Arendarenko, 2020.
 * https://github.com/arendarenko
 */

declare(strict_types=1);

namespace Arendarenko\CustomCatalog\Test\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Rest\Request;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\WebapiAbstract;
use Arendarenko\CustomCatalog\Api\Data\CustomProductInterface;
use Arendarenko\CustomCatalog\Api\Data\CustomProductInterfaceFactory;
use Arendarenko\CustomCatalog\Api\CustomProductRepositoryInterface;

/**
 * Class CustomProductRepositoryTest
 *
 * Example functional test for one of web API methods
 */
class CustomProductRepositoryTest extends WebapiAbstract
{
    /**
     * @magentoApiDataFixture getByVpnDataFixture
     * @dataProvider getByVpnExistingDataProvider
     * @param string $vpn
     * @param array $expectedResult
     */
    public function testGetByVpnExisting(string $vpn, array $expectedResult): void
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => '/V1/product/getByVpn/' . $vpn,
                'httpMethod' => Request::HTTP_METHOD_GET,
            ]
        ];

        $actualResult = $this->_webApiCall($serviceInfo);

        //We need to unset these values before asserting because they are dynamic
        unset($actualResult['id'], $actualResult['updated_at']);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @magentoApiDataFixture getByVpnDataFixture
     * @param string $vpn
     */
    public function testGetByVpnNotExisting(string $vpn = '123'): void
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => '/V1/product/getByVpn/' . $vpn,
                'httpMethod' => Request::HTTP_METHOD_GET,
            ]
        ];

        $this->expectException(\Exception::class);

        $this->_webApiCall($serviceInfo);
    }

    /**
     * @return array
     */
    public function getByVpnExistingDataProvider(): array
    {
        return [
            [
                'testVpn1',
                [
                    'sku' => 'testSku1',
                    'vpn' => 'testVpn1',
                    'copy_write_info' => 'Test text1',
                    'created_at' => '2020-02-27 16:42:12'
                ]
            ],
            [
                'testVpn2',
                [
                    'sku' => 'testSku2',
                    'vpn' => 'testVpn2',
                    'copy_write_info' => 'Test text2',
                    'created_at' => '2020-02-28 16:42:12'
                ]
            ],
        ];
    }

    /**
     * @return array
     */
    public function getByVpnNotExistingDataProvider(): array
    {
        return [
            ['testVpn3'],
            ['testVpn4']
        ];
    }

    public static function getByVpnDataFixture(): void
    {
        $objectManager = Bootstrap::getObjectManager();

        /** @var CustomProductInterfaceFactory $customProductFactory */
        $customProductFactory = $objectManager->create(CustomProductInterfaceFactory::class);
        /** @var CustomProductRepositoryInterface $customProductRepository */
        $customProductRepository = $objectManager->create(CustomProductRepositoryInterface::class);

        /** @var CustomProductInterface $testProduct1 */
        $testProduct1 = $customProductFactory->create();

        $testProduct1
            ->setSku('testSku1')
            ->setVpn('testVpn1')
            ->setCopyWriteInfo('Test text1')
            ->setCreatedAt('2020-02-27 16:42:12');

        $customProductRepository->save($testProduct1);

        /** @var CustomProductInterface $testProduct1 */
        $testProduct2 = $customProductFactory->create();

        $testProduct2
            ->setSku('testSku2')
            ->setVpn('testVpn2')
            ->setCopyWriteInfo('Test text2')
            ->setCreatedAt('2020-02-28 16:42:12');

        $customProductRepository->save($testProduct2);
    }

    public static function getByVpnDataFixtureRollback(): void
    {
        $objectManager = Bootstrap::getObjectManager();

        /** @var CustomProductRepositoryInterface $customProductRepository */
        $customProductRepository = $objectManager->create(CustomProductRepositoryInterface::class);

        /** @var CustomProductInterface $testProduct1 */
        $testProduct1 = $customProductRepository->getByVPN('testVpn1');
        $testProduct2 = $customProductRepository->getByVPN('testVpn2');

        $customProductRepository->delete($testProduct1);
        $customProductRepository->delete($testProduct2);
    }
}
