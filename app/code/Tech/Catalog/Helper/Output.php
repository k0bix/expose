<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tech\Catalog\Helper;


use Magento\Catalog\Model\Product as ModelProduct;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Output extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var TimezoneInterface
     */
    protected $localeDate;
    protected $_stockItemRepository;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        TimezoneInterface $localeDate,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
    ) {
        $this->_stockItemRepository = $stockItemRepository;
        $this->localeDate = $localeDate;
        parent::__construct($context);
    }

    public function isProductNew(ModelProduct $product)
    {
        $newsFromDate = $product->getNewsFromDate();
        $newsToDate = $product->getNewsToDate();
        if (!$newsFromDate && !$newsToDate) {
            return false;
        }

        return $this->localeDate->isScopeDateInInterval(
            $product->getStore(),
            $newsFromDate,
            $newsToDate
        );
    }

    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId);
    }
}
