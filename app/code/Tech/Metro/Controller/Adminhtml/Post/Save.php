<?php
namespace Tech\Metro\Controller\Adminhtml\Post;

class Save extends \Tech\Metro\Controller\Adminhtml\Post
{
    /**
     * Upload model
     * 
     * @var \Tech\Metro\Model\Upload
     */
    protected $uploadModel;

    /**
     * Image model
     * 
     * @var \Tech\Metro\Model\Post\Image
     */
    protected $imageModel;

    /**
     * Backend session
     * 
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * constructor
     * 
     * @param \Tech\Metro\Model\Upload $uploadModel
     * @param \Tech\Metro\Model\Post\Image $imageModel
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Tech\Metro\Model\PostFactory $postFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Tech\Metro\Model\Upload $uploadModel,
        \Tech\Metro\Model\Post\Image $imageModel,
        \Magento\Backend\Model\Session $backendSession,
        \Tech\Metro\Model\PostFactory $postFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->uploadModel    = $uploadModel;
        $this->imageModel     = $imageModel;
        $this->backendSession = $backendSession;
        parent::__construct($postFactory, $registry, $resultRedirectFactory, $context);
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('post');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $post = $this->initPost();
            $post->setData($data);
            $image = $this->uploadModel->uploadFileAndGetName('image', $this->imageModel->getBaseDir(), $data);
            $post->setImage('\tech\metro\post\image'.str_replace('\tech\metro\post\image','',$image));
            $this->_eventManager->dispatch(
                'tech_metro_post_prepare_save',
                [
                    'post' => $post,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $post->save();
                $this->messageManager->addSuccess(__('The Post has been saved.'));
                $this->backendSession->setTechMetroPostData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'tech_metro/*/edit',
                        [
                            'post_id' => $post->getId(),
                            '_current' => true
                        ]
                    );
                    return $resultRedirect;
                }
                $resultRedirect->setPath('tech_metro/*/');
                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Post.'));
            }
            $this->_getSession()->setTechMetroPostData($data);
            $resultRedirect->setPath(
                'tech_metro/*/edit',
                [
                    'post_id' => $post->getId(),
                    '_current' => true
                ]
            );
            return $resultRedirect;
        }
        $resultRedirect->setPath('tech_metro/*/');
        return $resultRedirect;
    }
}
