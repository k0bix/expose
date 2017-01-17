<?php
/**
 *
 * @package    Fisha
 * @category   Add Options
 * @author     Fisha Core Team <kobi.p@fisha.co.il>
 *  ______ _ _____ _   _
 *  |  ___(_)  ___| | | |
 *  | |_   _\ `--.| |_| | __ _
 *  |  _| | |`--. \  _  |/ _` |
 *  | |   | /\__/ / | | | (_| |
 *  \_|   |_\____/\_| |_/\__,_|
 */

namespace Fisha\Import\Model\Product;

use \Magento\Framework\ObjectManagerInterface;

class Attribute extends \Magento\Framework\Model\AbstractModel
{
    protected $eavConfig;
    protected $objectManager;
    protected $csvProcessor;
    protected $_eavAttribute;

    public function __construct(
        \Magento\Eav\Model\Config $eavConfig,
        ObjectManagerInterface $manager,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute $eavAttribute)
    {
        $this->objectManager = $manager;
        $this->eavConfig = $eavConfig;
        $this->csvProcessor = $csvProcessor;
        $this->_eavAttribute = $eavAttribute;
    }

    public function execute()
    {
        $pros = $this->csvProcessor->getData($_FILES['import_file']['tmp_name']);

        $size = $this->_eavAttribute->getIdByCode('catalog_product', 'size');
        $stone = $this->_eavAttribute->getIdByCode('catalog_product', 'stone');

        //$size=1;
        //$stone=1;

        for ( $i =1;$i < count($pros);$i++) {

            if($this->getAttribute($size,$pros[$i][16]) == false && $pros[$i][16] != '' ) $this->addAttribute($size, $pros[$i][16]);
            if($this->getAttribute($stone,$pros[$i][20]) == false && $pros[$i][20] != '' ) $this->addAttribute($stone, $pros[$i][20]);

        }
    }

    private function addAttribute($attributeCode, $front)
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

        $res = false;

        foreach ($attr->getOptions() as $opt)
            if ($opt['label'] == $front)
                $res = $opt['value'];

        return $res;
    }
}