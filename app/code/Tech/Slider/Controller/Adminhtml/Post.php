<?php
namespace Tech\Slider\Controller\Adminhtml;

abstract class Post extends \Magento\Backend\App\Action
{
    /**
     * Post Factory
     * 
     * @var \Tech\Slider\Model\PostFactory
     */
    protected $postFactory;

    /**
     * Core registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * Result redirect factory
     * 
     * @var \Magento\Backend\Model\View\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * constructor
     * 
     * @param \Tech\Slider\Model\PostFactory $postFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Tech\Slider\Model\PostFactory $postFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->postFactory           = $postFactory;
        $this->coreRegistry          = $coreRegistry;
        $this->resultRedirectFactory = $resultRedirectFactory;
        parent::__construct($context);
    }

    /**
     * Init Post
     *
     * @return \Tech\Slider\Model\Post
     */
    protected function initPost()
    {
        $postId  = (int) $this->getRequest()->getParam('post_id');
        /** @var \Tech\Slider\Model\Post $post */
        $post    = $this->postFactory->create();
        if ($postId) {
            $post->load($postId);
        }
        $this->coreRegistry->register('tech_slider_post', $post);
        return $post;
    }
}
