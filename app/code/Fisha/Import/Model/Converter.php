<?php
/**
 *
 * @package    Fisha
 * @category   Converter
 * @author     Fisha Core Team <kobi.p@fisha.co.il>
 *  ______ _ _____ _   _
 *  |  ___(_)  ___| | | |
 *  | |_   _\ `--.| |_| | __ _
 *  |  _| | |`--. \  _  |/ _` |
 *  | |   | /\__/ / | | | (_| |
 *  \_|   |_\____/\_| |_/\__,_|
 */

namespace Fisha\Import\Model;

class Converter extends \Magento\ImportExport\Model\AbstractModel{

    const SKU=24; const SITE="base"; const NAME=2; const DESC=28; const PRICE=26; const QTY=3;
    const FINISH=7; const MAT_MAIN=9; const MAT_COMB_1=11; const MAT_COMB_2=13;
    const SIZE=17; const STONE=21; const IMG=1; const CAT=6; const KNOTS=3; const MODEL=7;

    protected $_csvProcessor;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\File\Csv $csvProcessor
    )
    {
        $this->_csvProcessor = $csvProcessor;
    }

    public function execute()
    {

        $products = $this->sortBySuperAttr($_FILES['import_file']['tmp_name']);
        $configurable = $products[1][0]; $simple_products=null; $products[]=$products[0];

        $fp=fopen($_FILES['import_file']['tmp_name'], 'w'); $sa1=null; $sa2=null;

        fputcsv($fp, array( 'sku', 'attribute_set_code', 'product_type', 'categories', 'product_websites',
            'name', 'description' ,'weight', 'product_online', 'tax_class_name',
            'visibility', 'price', 'url_key', 'additional_attributes', 'qty', 'configurable_variations', 'configurable_variation_labels',
            'base_image', 'small_image', 'thumbnail_image', 'knots_desc', 'model', 'finish_desc',
            'material_main', 'material_comb_1', 'material_comb_2'));

        for ( $i =1; $i < count($products); $i++) {

            if ($products[$i][0] != $configurable) {

                $conf = '';
                foreach ($simple_products as $s) {
                    $conf .= 'sku='.$s[0].',size=_'.$sa1[$s[0]].',stone=_'.$sa2[$s[0]].'|';
                }

                $additional_attributes = '';

                $pro = array_merge($simple_products, array( array($products[$i-1][self::SKU], 'Default', 'configurable',
                    'Default Category/Featured Collections/'.$products[$i-1][self::CAT]
                , self::SITE, $products[$i-1][self::NAME], $products[$i-1][self::DESC], 1, 1, 0,
                    'Catalog, Search', $products[$i-1][self::PRICE], $products[$i-1][self::SKU],
                    $additional_attributes, $products[$i-1][self::QTY], rtrim($conf, "|") , 'size=size, stone=stone',
                    $products[$i-1][self::IMG], $products[$i-1][self::IMG], $products[$i-1][self::IMG],
                    $products[$i-1][self::KNOTS], $products[$i-1][self::MODEL], $products[$i-1][self::FINISH],
                    $products[$i-1][self::MAT_MAIN], $products[$i-1][self::MAT_COMB_1], $products[$i-1][self::MAT_COMB_2]
                )));

                foreach ($pro as $p) {fputcsv($fp, $p);}

                $configurable = $products[$i][0]; unset($simple_products);
            }
            $sku = $products[$i][self::SKU].$products[$i][self::SIZE-1].$products[$i][self::STONE-1];
            $additional_attributes = 'size=_'.$products[$i][self::SIZE].',stone=_'.$products[$i][self::STONE].'';

            $simple_products[] = array($sku,
                'Default', 'simple', 'Default Category/Featured Collections/'.$products[$i-1][self::CAT],
                self::SITE,  $products[$i][self::NAME], $products[$i][self::DESC], 1, 1, 0,
                'Not Visible Individually', $products[$i][self::PRICE], $sku,
                $additional_attributes, $products[$i][self::QTY], '', '',
                    $products[$i-1][self::IMG], $products[$i-1][self::IMG], $products[$i-1][self::IMG],
                    $products[$i-1][self::KNOTS], $products[$i-1][self::MODEL], $products[$i-1][self::FINISH],
                    $products[$i-1][self::MAT_MAIN], $products[$i-1][self::MAT_COMB_1], $products[$i-1][self::MAT_COMB_2]
            );

            $sa1[$sku]=$products[$i][self::SIZE]; $sa2[$sku]=$products[$i][self::STONE];

        }

        fclose($fp);
    }

    public function sortBySuperAttr($csv){
        $pros = $this->_csvProcessor->getData($csv);

        for ( $i =1;$i < count($pros);$i++) {
            if($pros[$i][1]=='') continue;
            $p[] = array_merge(array($pros[$i][self::NAME] . $pros[$i][self::FINISH] . $pros[$i][self::MAT_MAIN].
                $pros[$i][self::MAT_COMB_1]. $pros[$i][self::MAT_COMB_2]), $pros[$i]);
        }

        sort($p);
        return $p;
    }


}


