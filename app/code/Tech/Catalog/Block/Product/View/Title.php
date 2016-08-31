<?php

namespace Tech\Catalog\Block\Product\View;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;


class Title extends Template {

    public $_registry;

    public function __construct(
        Registry $registry,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout() {

        $this->setTitle($this->getCategory() ? $this->getCategory()->getName():'');
        $this->setProductName($this->getProduct() ? $this->getProduct()->getName():'');
        $this->setCategoryColor($this->getCategory() ? '#'.$this->getCategory()->getCatColor():'');
    }

    protected function getCategory()
    {
        return $this->_registry->registry('current_category');
    }

    protected function getProduct()
    {
        return $this->_registry->registry('current_product');
    }


}