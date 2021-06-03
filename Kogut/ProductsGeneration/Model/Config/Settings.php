<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Settings
{
    const XML_PATH_CATEGORY_NAME_TO_LINK = 'products_generation/general/category_name_to_link';
    const XML_PATH_PRODUCTS_QTY_TO_GENERATE = 'products_generation/general/qty_to_generate';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Settings constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get category name to generate
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_CATEGORY_NAME_TO_LINK);
    }

    /**
     * Get products Qty to generate
     * @return int
     */
    public function getProductsQtyToGenerate(): int
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_PRODUCTS_QTY_TO_GENERATE);
    }
}
