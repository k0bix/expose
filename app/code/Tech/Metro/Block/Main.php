<?php

namespace Tech\Metro\Block;

use Magento\Framework\View\Element\Template;

class Main extends Template {

    protected $collectionFactory;

    public function __construct(
        \Tech\Metro\Model\ResourceModel\Post\CollectionFactory $collectionFactory,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    )
    {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout() {
        $this->setCollection($this->collectionFactory->create());
    }
}