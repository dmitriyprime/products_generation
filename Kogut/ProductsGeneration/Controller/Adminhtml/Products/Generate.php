<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Controller\Adminhtml\Products;

use Kogut\ProductsGeneration\Model\Service\CreateCategory;
use Kogut\ProductsGeneration\Model\Service\CreateProduct;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Catalog\Api\CategoryListInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Kogut\ProductsGeneration\Model\Config\Settings;

class Generate extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Kogut_ProductsGeneration::config';

    /**
     * @var CategoryListInterface
     */
    private $categoryList;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var Settings
     */
    private $config;

    /**
     * @var CreateProduct
     */
    private $createProductService;

    /**
     * @var CreateCategory
     */
    private $createCategoryService;

    /**
     * @param Context $context
     * @param RedirectFactory $resultRedirectFactory
     * @param CreateProduct $createProductService
     * @param CreateCategory $createCategoryService
     * @param CategoryListInterface $categoryList
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param Settings $config
     */
    public function __construct(
        Context $context,
        RedirectFactory $resultRedirectFactory,
        CreateProduct $createProductService,
        CreateCategory $createCategoryService,
        CategoryListInterface $categoryList,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        Settings $config
    ) {
        parent::__construct($context);
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->categoryList = $categoryList;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->config = $config;
        $this->createProductService = $createProductService;
        $this->createCategoryService = $createCategoryService;
    }

    /**
     * Generates products
     * @return Redirect
     */
    public function execute()
    {
        $categoryName = $this->config->getCategoryName();
        $productsQtyToGenerate = $this->config->getProductsQtyToGenerate();

        $filterByName = $this->filterBuilder
            ->setField('name')
            ->setValue($categoryName)
            ->setConditionType('eq')
            ->create();
        $this->searchCriteriaBuilder->addFilters([$filterByName]);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $categories = $this->categoryList->getList($searchCriteria)->getItems();

        if($categories) {
            $categoryId = $categories[0]->getId();
        } else {
            $this->createCategoryService->createCategory($categoryName);
            $categories = $this->categoryList->getList($searchCriteria)->getItems();
            $categoryId = $categories[0]->getId();
        }

        for($i = 0; $i < $productsQtyToGenerate; $i++) {
            $this->createProductService->createSimpleProduct($categoryId);
        }
        $this->messageManager->addSuccessMessage(__("$productsQtyToGenerate new products are generated"));

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('catalog/product/index');
        return $resultRedirect;
    }
}
