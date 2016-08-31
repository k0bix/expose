<?php
namespace Tech\Import\Model;

class Category extends \Magento\Framework\Model\AbstractModel
{
    protected $_categoryFactory;

    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
    )
    {
        $this->_categoryFactory = $categoryFactory;
    }

    public function getCategory($name, $parent)
    {
        switch ($name) {
            case 'BRA EXPOSE':
                $name = 'נשים';
                break;
            case 'WOMEN EXPOZE':
                $name = 'נשים';
                break;
            case 'MEN EXPOZE':
                $name = 'גברים';
                break;
            case 'KIDS EXPOZE':
                $name = 'ילדים';
                break;
        }

        $category = $this->_categoryFactory->create()->getResourceCollection()
            ->addAttributeToFilter('name', $name)
            ->addAttributeToFilter('parent_id', $parent)->load();



        $res = null;
        if ($category)
            $res = $category->getFirstItem()->getId();

        return $res;
    }

    public function addCategory($name, $parent)
    {
        if($name=="") return 12;

        switch ($name) {
            case 'BRA EXPOSE':
                $name = 'נשים';
                break;
            case 'WOMEN EXPOZE':
                $name = 'נשים';
                break;
            case 'MEN EXPOZE':
                $name = 'גברים';
                break;
            case 'KIDS EXPOZE':
                $name = 'ילדים';
                break;
        }

        $categoryTmp = $this->_categoryFactory->create();
        $categoryTmp->setName($name);
        $categoryTmp->setIsActive(true);
        $categoryTmp->setDescription($name);
        $categoryTmp->setParentId($parent);
        $categoryTmp->setStoreId(0);
        $categoryTmp->setPath($this->_categoryFactory->create()->load($parent)->getPath());


        $categoryTmp->save();
    }

}