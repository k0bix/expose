<?php
namespace Tech\Import\Model;

use \Magento\Framework\ObjectManagerInterface;

class Attribute extends \Magento\Framework\Model\AbstractModel
{
    protected $eavConfig;
    protected $objectManager;

    public function __construct(\Magento\Eav\Model\Config $eavConfig, ObjectManagerInterface $manager)
    {
        $this->objectManager = $manager;
        $this->eavConfig = $eavConfig;
    }

    public function addAttribute($attributeCode, $front)
    {
        $res = $this->getAttribute($attributeCode, $front);

        if (!$res) {
            $languageValues[0] = '_' . $front;
            $languageValues[1] = $front;

            $obj = $this->objectManager;
            $attr = $obj->create('\Magento\Eav\Model\Entity\Attribute');
            $attr->load($attributeCode);

            $option = [];
            $option['value'][$languageValues[0]] = $languageValues;
            $attr->addData(array('option' => $option));
            $attr->save();
        }
        return $this->getAttribute($attributeCode, $front);
    }

    private function getAttribute($attributeCode, $front)
    {
        $obj = $this->objectManager;
        $attr = $obj->create('\Magento\Eav\Model\Entity\Attribute');
        $attr->load($attributeCode);

        $res = null;

        foreach ($attr->getOptions() as $opt)
            if ($opt['label'] == $front)
                $res = $opt['value'];

        return $res;
    }
}