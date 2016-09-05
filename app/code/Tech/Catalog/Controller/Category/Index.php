<?php

namespace Tech\Catalog\Controller\Category;


class Index extends \Magento\Framework\App\Action\Action
{

    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;
    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(\Magento\Framework\App\Action\Context $context,
                                \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        $block = $resultPage->getLayout()
            ->createBlock('Tech\Catalog\Block\Product\Filtered')
            ->setTemplate('Tech_Catalog::product/list/filtered.phtml')
            ->toHtml();
        $this->getResponse()->setBody($block);
    }
}