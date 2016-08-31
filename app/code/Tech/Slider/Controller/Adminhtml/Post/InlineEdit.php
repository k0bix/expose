<?php
namespace Tech\Slider\Controller\Adminhtml\Post;

abstract class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * JSON Factory
     * 
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * Post Factory
     * 
     * @var \Tech\Slider\Model\PostFactory
     */
    protected $postFactory;

    /**
     * constructor
     * 
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Tech\Slider\Model\PostFactory $postFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Tech\Slider\Model\PostFactory $postFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->jsonFactory = $jsonFactory;
        $this->postFactory = $postFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }
        foreach (array_keys($postItems) as $postId) {
            /** @var \Tech\Slider\Model\Post $post */
            $post = $this->postFactory->create()->load($postId);
            try {
                $postData = $postItems[$postId];//todo: handle dates
                $post->addData($postData);
                $post->save();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithPostId($post, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithPostId($post, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPostId(
                    $post,
                    __('Something went wrong while saving the Post.')
                );
                $error = true;
            }
        }
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add Post id to error message
     *
     * @param \Tech\Slider\Model\Post $post
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithPostId(\Tech\Slider\Model\Post $post, $errorText)
    {
        return '[Post ID: ' . $post->getId() . '] ' . $errorText;
    }
}
