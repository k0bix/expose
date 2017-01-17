<?php
/**
 *
 * @package    Fisha
 * @category   converter
 * @author     Fisha Core Team <kobi.p@fisha.co.il>
 *  ______ _ _____ _   _
 *  |  ___(_)  ___| | | |
 *  | |_   _\ `--.| |_| | __ _
 *  |  _| | |`--. \  _  |/ _` |
 *  | |   | /\__/ / | | | (_| |
 *  \_|   |_\____/\_| |_/\__,_|
 */

namespace Fisha\Import\Controller\Adminhtml\Import;


class Validate extends \Magento\ImportExport\Controller\Adminhtml\Import\Validate
{
    /**
     * Import source file.
     */


    public function execute()
    {
        //add attributes
        $attribute=$this->_objectManager->Create('\Fisha\Import\Model\Product\Attribute');
        $attribute->execute();

        // convert original file to new format
        $converter=$this->_objectManager->Create('\Fisha\Import\Model\Converter');
        $converter->execute();

        return parent::execute();
    }

}