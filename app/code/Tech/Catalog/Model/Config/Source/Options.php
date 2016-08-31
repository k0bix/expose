<?php

namespace Tech\Catalog\Model\Config\Source;

use Magento\Framework\DB\Ddl\Table;


class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    protected $_categoryCollectionFactory;
    protected $_categoryHelper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []
    )
    {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_categoryHelper = $categoryHelper;
    }

    public function getCategoryCollection()
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addIsActiveFilter();
        $collection->addFieldToFilter('level', ['in' => [2,3]]);;

        $collection->getSelect()->joinInner('exp_catalog_category_entity_text',
            'exp_catalog_category_entity_text.entity_id=e.parent_id',  array('parent_name' => 'value'));

        //die($collection->getSelect());
        return $collection;
    }

    public function getAllOptions()
    {
        $categories = $this->getCategoryCollection();
        $arr[]=['label' => 'Default Category', 'value' => '1'];
        foreach ($categories as $category) {
            $arr[] = ['label' => $category->getParentName().'-'.$category->getName(), 'value' => $category->getId()];
        }

        $this->_options = $arr;
        return $this->_options;
    }

    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }

    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        return [
            $attributeCode => [
                'unsigned' => false,
                'default' => null,
                'extra' => null,
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Custom Attribute Options  ' . $attributeCode . ' column',
            ],
        ];
    }
}