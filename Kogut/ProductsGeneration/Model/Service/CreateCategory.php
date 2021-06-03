<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Model\Service;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;

class CreateCategory
{
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        CategoryFactory $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        ManagerInterface $messageManager
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->messageManager = $messageManager;
    }

    public function createCategory($categoryName)
    {
        $category = $this->categoryFactory->create();
        $category->setName($categoryName);
        $category->setParentId(2);
        $category->setIsActive(true);
        $category->setIncludeInMenu(true);
        $this->categoryRepository->save($category);
        $this->messageManager->addSuccessMessage(__('Category ' . $category->getName() . ' is created.'));
    }
}
