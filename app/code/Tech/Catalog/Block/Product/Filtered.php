<?php
namespace Tech\Catalog\Block\Product;

use Magento\Catalog\Block\Product\ListProduct;

class Filtered extends ListProduct
{
    protected function _getProductCollection()
    {
        if ($this->_productCollection === null) {
            $layer = $this->getLayer();

            $origCategory = null;
            if ($this->getCategoryId()) {
                try {
                    $category = $this->categoryRepository->get($this->getCategoryId());
                } catch (NoSuchEntityException $e) {
                    $category = null;
                }

                if ($category) {
                    $origCategory = $layer->getCurrentCategory();
                    $layer->setCurrentCategory($category);
                }
            }
            $this->_productCollection = $layer->getProductCollection();

            $cat = $this->getRequest()->getParam('cat');
            $color = $this->getRequest()->getParam('color');
            $size =$this->getRequest()->getParam('size');

            if($color!='') $this->_productCollection->addFieldToFilter('color',array('in' => explode(',',$color)));
            if($size!='')  $this->_productCollection->addFieldToFilter('size',array('in' => explode(',',$size)));
            if($cat!='')   $this->_productCollection->getSelect()->joinInner('exp_catalog_category_product_index',
                    'exp_catalog_category_product_index.product_id=e.entity_id AND exp_catalog_category_product_index.category_id in ('.$cat.')');

            $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

            if ($origCategory) {
                $layer->setCurrentCategory($origCategory);
            }
        }

        return $this->_productCollection;

    }
}
