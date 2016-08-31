<?php

namespace Tech\Catalog\Block;

use Magento\Framework\View\Element\Template;

class ColorScheme extends Template
{

    protected $_coreRegistry = null;
    protected $_categoryInstance = null;

    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_categoryInstance = $categoryFactory->create();
        parent::__construct($context);    }

    public function _prepareLayout()
    {
        $this->_coreRegistry->register('current_category', $this->_categoryInstance->load($this->getProduct()->getCatDefault()));

        $this->setCategoryColor($this->getCategory() ? $this->getCategory()->getCatColor() : '');
    }

    public function getCategory()
    {
        return $this->_coreRegistry->registry('current_category');
    }

    protected function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

}