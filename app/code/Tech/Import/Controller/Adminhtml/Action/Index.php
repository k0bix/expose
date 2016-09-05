<?php

namespace Tech\Import\Controller\Adminhtml\Action;

class Index extends \Magento\Backend\App\Action
{
    protected $_product;
    protected $_category;
    protected $_attribute;
    protected $_csvProcessor;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\File\Csv $csvProcessor,
        \Tech\Import\Model\Product $product,
        \Tech\Import\Model\Category $category,
        \Tech\Import\Model\Attribute $attribute
    )
    {
        $this->_product = $product;
        $this->_category = $category;
        $this->_attribute = $attribute;
        $this->_csvProcessor = $csvProcessor;

        parent::__construct($context);
    }

    public function execute_()
    {
        $products = $this->_csvProcessor->getData('tech/PARIT.CSV');
        $configurable_product = substr($products[1][0], 0, 6);
        $simple_products = null;
        $i =1; $j = 0; $cat = null; $cat_default = null; $colors = null; $sizes = null;

        for (;$i < count($products);$i++) {

            if ($products[$i][7] == '' || $products[$i][6] == '') continue;

            $products[$i][7] = str_replace('&#39;', '\'', $products[$i][7]); //color
            $products[$i][1] = str_replace('&#39;', '\'', $products[$i][1]); //name

            $products[$i][10] = str_replace('&#39;', '\'', $products[$i][10]); //cat1
            $products[$i][3] = str_replace('&#39;', '\'', $products[$i][3]); //cat2
            $products[$i][20] = str_replace('&#39;', '\'', $products[$i][20]); //cat2

            if (!isset($colors[$products[$i][7]])) {
                $colors[$products[$i][7]] = $this->_attribute->addAttribute(93, $products[$i][7]);      //color
            }

            if (!isset($sizes[$products[$i][6]])) {
                $sizes[$products[$i][6]] = $this->_attribute->addAttribute(137, $products[$i][6]);      //size
            }


            if (!isset($cat1[$products[$i][10].$products[$i][3].$products[$i][20]])) {

                $cat1 = $this->_category->getCategory($products[$i][10], 2);
                if (!$cat1) {
                    $this->_category->addCategory($products[$i][10], 2);
                    $cat1 = $this->_category->getCategory($products[$i][10],2);
                }

                $cat2 = $this->_category->getCategory($products[$i][3], $cat1);
                if (!$cat2) {
                    $this->_category->addCategory($products[$i][3], $cat1);
                    $cat2 = $this->_category->getCategory($products[$i][3],$cat1);
                }

                $cat3 = $this->_category->getCategory($products[$i][20],$cat2);
                if (!$cat3) {
                    $this->_category->addCategory($products[$i][20], $cat2);
                }

                if (!isset($cat_default[$products[$i][3]])) {
                    $cat_default[$products[$i][3]] = $cat2;
                }

                $cat[$products[$i][10].$products[$i][3].$products[$i][20]] = $cat3;
            }




            /*if (substr($products[$i][0], 0, 6) == $configurable_product) {

                $pid = $this->_product->addProduct($products[$i][0], $products[$i][1], $sizes[$products[$i][5]], $colors[$products[$i][6]], 10,
                    $cat[$products[$i][9].$products[$i][3].$products[$i][19]], 100, $products[$i], $cat_default[$products[$i][3]]);

                $simple_products[$j][] = $pid; $colors[$j][$pid] = $colors[$products[$i][6]]; $sizes[$j][$pid] = $sizes[$products[$i][5]];

            } else {
                $this->_product->addConfigurableProduct($configurable_product, $products[$i - 1][1], 100,
                    $cat[$products[$i][9].$products[$i][3].$products[$i][19]], $simple_products[$j], $colors[$j], $sizes[$j], $products[$i], $cat_default[$products[$i][3]]);

                $pid = $this->_product->addProduct($products[$i][0], $products[$i][1], $sizes[$products[$i][5]], $colors[$products[$i][6]], 10,
                    $cat[$products[$i][9].$products[$i][3].$products[$i][19]], 100, $products[$i], $cat_default[$products[$i][3]]);

                $simple_products[++$j][] = $pid; $colors[$j][$pid] = $colors[$products[$i][6]]; $sizes[$j][$pid] = $sizes[$products[$i][5]];
                $configurable_product = substr($products[$i][0], 0, 6);
            }*/
        }


        /*$this->_product->addConfigurableProduct(substr($products[$i][0], 0, 6),
            $products[$i][1], 100, $cat[ $products[$i][9]. $products[$i][3]. $products[$i][19]],
            $simple_products[$j], $colors[$j], $sizes[$j], $products[$i],$cat_default[ $products[$i][3]]);*/

    }

    public function execute()
    {
        $products = $this->_csvProcessor->getData('tech/PARIT.CSV');
        $configurable_product = substr($products[1][0], 0, 6); $simple_products=null;
        $fp = fopen('tech/out.csv', 'w');$color=null;$size=null;
        $products[]=$products[2];

        fputcsv($fp, array( 'sku', 'attribute_set_code', 'product_type', 'categories', 'product_websites', 'name', 'description' ,'weight', 'product_online', 'tax_class_name',
            'visibility', 'price', 'url_key', 'additional_attributes', 'qty', 'configurable_variations', 'configurable_variation_labels'));

        for ( $i =1;$i < count($products);$i++) {

            if ($products[$i][7] == '' || $products[$i][6] == '') continue;

            $products[$i][7] = str_replace('&#39;', '\'', $products[$i][7]); //color
            $products[$i][1] = str_replace('&#39;', '\'', $products[$i][1]); //name

            $products[$i][10] = str_replace('&#39;', '\'', $products[$i][10]); //cat1
            $products[$i][3] = str_replace('&#39;', '\'', $products[$i][3]); //cat2
            $products[$i][20] = str_replace('&#39;', '\'', $products[$i][20]); //cat2

            switch ($products[$i][10]) {
                case 'BRA EXPOSE':
                    $products[$i][10] = 'נשים';
                    break;
                case 'WOMEN EXPOZE':
                    $products[$i][10] = 'נשים';
                    break;
                case 'MEN EXPOZE':
                    $products[$i][10] = 'גברים';
                    break;
                case 'KIDS EXPOZE':
                    $products[$i][10] = 'ילדים';
                    break;
            }

            if (substr($products[$i][0], 0, 6) == $configurable_product) {
                //new line with simple products
                echo '<pre>simple-' . $products[$i][0] . '</pre>';
                $additional_attributes = 'cat_default='.$products[$i][3].',color=_'.$products[$i][7].',qvd_evdpim='.$products[$i][21].',qvd_evnh='.$products[$i][13]
                    .',qvd_mbce_hnvivt='.$products[$i][30].',qvd_mhlqh='.$products[$i][9].',qvd_mwphh='.$products[$i][13].',qvd_svg_prit='.$products[$i][19]
                    .',size=_'.$products[$i][6].',wm_evnh='.$products[$i][14].',wm_mvtg='.$products[$i][29].',wm_qvlqcih='.$products[$i][39]
                    .',wm_svg_prit='.$products[$i][20].',wm_tqcib='.$products[$i][41].',wm_wnh='.$products[$i][14].'';

                $simple_products[] = array($products[$i][0], 'Default', 'simple', 'Default Category/'.$products[$i][10].'/'.$products[$i][3].'/'
                    .$products[$i][20],'base', $products[$i][1], $products[$i][1], 1, 1, 0, 'Not Visible Individually', 10, $products[$i][0],
                    $additional_attributes, 10, '', '');
                $color[$products[$i][0]]=$products[$i][7]; $size[$products[$i][0]]=$products[$i][6];

            } else {
                //new line with new group
                echo '<pre>configurable-' . $configurable_product . '</pre>';
                $additional_attributes = 'cat_default='.$products[$i][3].',qvd_evdpim='.$products[$i][21].',qvd_evnh='.$products[$i][13]
                    .',qvd_mbce_hnvivt='.$products[$i][30].',qvd_mhlqh='.$products[$i][9].',qvd_mwphh='.$products[$i][13].',qvd_svg_prit='.$products[$i][19]
                    .',wm_evnh='.$products[$i][14].',wm_mvtg='.$products[$i][29].',wm_qvlqcih='.$products[$i][39]
                    .',wm_svg_prit='.$products[$i][20].',wm_tqcib='.$products[$i][41].',wm_wnh='.$products[$i][14].'';

                $configurable_variations = '';
                foreach ($simple_products as $s) {
                    $configurable_variations .= 'sku='.$s[0].',color=_'.$color[$s[0]].',size=_'.$size[$s[0]].'|';
                }

                $pro = array_merge($simple_products, array( array($configurable_product, 'Default', 'configurable', 'Default Category/'.$products[$i-1][10].'/'.$products[$i-1][3].'/'
                    .$products[$i-1][20],'base', $products[$i-1][1], $products[$i-1][1], 1, 1, 0, 'Catalog, Search', 10, $configurable_product,
                    $additional_attributes, 10, rtrim($configurable_variations, "|") , 'color=צבע,size=מידה')));

                foreach ($pro as $p) {
                    fputcsv($fp, $p);
                }

                $configurable_product = substr($products[$i][0], 0, 6);
                unset($simple_products); unset($pro);

                $additional_attributes = 'cat_default='.$products[$i][3].',color=_'.$products[$i][7].',qvd_evdpim='.$products[$i][21].',qvd_evnh='.$products[$i][13]
                    .',qvd_mbce_hnvivt='.$products[$i][30].',qvd_mhlqh='.$products[$i][9].',qvd_mwphh='.$products[$i][13].',qvd_svg_prit='.$products[$i][19]
                    .',size=_'.$products[$i][6].',wm_evnh='.$products[$i][14].',wm_mvtg='.$products[$i][29].',wm_qvlqcih='.$products[$i][39]
                    .',wm_svg_prit='.$products[$i][20].',wm_tqcib='.$products[$i][41].',wm_wnh='.$products[$i][14].'';

                $simple_products[] = array($products[$i][0], 'Default', 'simple', 'Default Category/'.$products[$i][10].'/'.$products[$i][3].'/'
                    .$products[$i][20], 'base', $products[$i][1], $products[$i][1], 1, 1, 0, 'Not Visible Individually', 10, $products[$i][0],
                     $additional_attributes, 10, '', '');
                $color[$products[$i][0]]=$products[$i][7]; $size[$products[$i][0]]=$products[$i][6];
            }
        }

        fclose($fp);
    }

}























