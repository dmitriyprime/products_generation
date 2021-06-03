<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Ui\Component\Listing;

use Kogut\ProductsGeneration\Model\Config\Settings;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;

/**
 * Generate Products Button
 */
class GenerateProductsButton implements ButtonProviderInterface
{
    const XML_PATH_TO_PRODUCT_GENERATION_CONTROLLER = 'generate_products/products/generate';

    /**
     * URL builder
     *
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Settings
     */
    private $config;

    /**
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Settings $config
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function getButtonData()
    {
        $message = sprintf(
            __("Are you sure you want to generate %s products?")->render(),
            $this->config->getProductsQtyToGenerate()
        );
        return [
            'label' => __('Generate products'),
            'sort_order' => '5',
            'class' => 'generate_products primary',
            'on_click' => sprintf(
                "confirmSetLocation('%s', '%s')",
                $message,
                $this->urlBuilder->getUrl(self::XML_PATH_TO_PRODUCT_GENERATION_CONTROLLER)
            ),
        ];
    }
}
