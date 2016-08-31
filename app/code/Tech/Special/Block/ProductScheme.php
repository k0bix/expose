<?php
namespace Tech\Special\Block;

use Magento\Catalog\Block\Product\ListProduct;

class ProductScheme extends ListProduct
{
    protected function _getProductCollection()
    {
        if ($this->_productCollection === null) {
            $layer = $this->getLayer();
            /* @var $layer \Magento\Catalog\Model\Layer */
            if ($this->getShowRootCategory()) {
                $this->setCategoryId($this->_storeManager->getStore()->getRootCategoryId());
            }

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

            // 135 val of category cat_color
            // 136 val of product default category
            $expression = '
                ( select c.value from exp_catalog_product_entity_int a
                INNER JOIN exp_catalog_category_entity_varchar c on a.value=c.entity_id and c.attribute_id=135
                where a.attribute_id=136 and a.entity_id=e.entity_id
                )
            ';
            $sentExpr = new \Zend_Db_Expr(sprintf('(%s)', $expression));

            $this->_productCollection->getSelect()->columns(['cat_color' => $sentExpr]);

            //die($this->_productCollection->getSelect());

            $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

            if ($origCategory) {
                $layer->setCurrentCategory($origCategory);
            }
        }

        return $this->_productCollection;
    }
}
