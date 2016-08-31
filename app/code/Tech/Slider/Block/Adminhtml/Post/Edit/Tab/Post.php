<?php
namespace Tech\Slider\Block\Adminhtml\Post\Edit\Tab;

class Post extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Tech\Slider\Model\Post $post */
        $post = $this->_coreRegistry->registry('tech_slider_post');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('post_');
        $form->setFieldNameSuffix('post');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Post Information'),
                'class'  => 'fieldset-wide'
            ]
        );
        $fieldset->addType('image', 'Tech\Slider\Block\Adminhtml\Post\Helper\Image');
        if ($post->getId()) {
            $fieldset->addField(
                'post_id',
                'hidden',
                ['name' => 'post_id']
            );
        }
        $fieldset->addField(
            'name',
            'text',
            [
                'name'  => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'img',
            'image',
            [
                'name'  => 'img',
                'label' => __('Image'),
                'title' => __('Image'),
            ]
        );
        $fieldset->addField(
            'link',
            'text',
            [
                'name'  => 'link',
                'label' => __('Link'),
                'title' => __('Link'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'position',
            'text',
            [
                'name'  => 'position',
                'label' => __('Position'),
                'title' => __('Position'),
                'required' => true,
            ]
        );

        $postData = $this->_session->getData('tech_slider_post_data', true);
        if ($postData) {
            $post->addData($postData);
        } else {
            if (!$post->getId()) {
                $post->addData($post->getDefaultValues());
            }
        }
        $form->addValues($post->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Post');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
