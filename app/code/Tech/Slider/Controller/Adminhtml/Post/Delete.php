<?php
namespace Tech\Slider\Controller\Adminhtml\Post;

class Delete extends \Tech\Slider\Controller\Adminhtml\Post
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('post_id');
        if ($id) {
            $name = "";
            try {
                /** @var \Tech\Slider\Model\Post $post */
                $post = $this->postFactory->create();
                $post->load($id);
                $name = $post->getName();
                $post->delete();
                $this->messageManager->addSuccess(__('The Post has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_tech_slider_post_on_delete',
                    ['name' => $name, 'status' => 'success']
                );
                $resultRedirect->setPath('tech_slider/*/');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_tech_slider_post_on_delete',
                    ['name' => $name, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                $resultRedirect->setPath('tech_slider/*/edit', ['post_id' => $id]);
                return $resultRedirect;
            }
        }
        // display error message
        $this->messageManager->addError(__('Post to delete was not found.'));
        // go to grid
        $resultRedirect->setPath('tech_slider/*/');
        return $resultRedirect;
    }
}
