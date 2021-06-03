<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Model\Service;

use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\CategoryLinkManagementInterface;

class CreateProduct
{
    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CategoryLinkManagementInterface
     */
    private $linkManagement;

    /**
     * CreateProduct constructor.
     * @param ProductFactory $productFactory
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryLinkManagementInterface $linkManagement
     */
    public function __construct(
        ProductFactory $productFactory,
        ProductRepositoryInterface $productRepository,
        CategoryLinkManagementInterface $linkManagement
    ) {
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->linkManagement = $linkManagement;
    }

    /**
     * Create test simple product service
     * @param $categoryId
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function createSimpleProduct($categoryId)
    {
        $product = $this->productFactory->create();
        $productSku = 'testSku' . microtime();
        $product->setSku($productSku);
        $product->setName('testName' . microtime());
        $product->setAttributeSetId(4);
        $product->setStatus(1);
        $product->setWeight(10);
        $product->setVisibility(4);
        $product->setTaxClassId(0);
        $product->setTypeId('simple');
        $product->setPrice(9.99);
        $product->setStockData(
            [
                'use_config_manage_stock' => 0,
                'manage_stock' => 1,
                'is_in_stock' => 1,
                'qty' => 1000
            ]
        );
        $this->productRepository->save($product);
        $this->linkManagement->assignProductToCategories($productSku, [$categoryId]);
    }
}
