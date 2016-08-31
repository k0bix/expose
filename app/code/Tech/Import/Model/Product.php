<?php
namespace Tech\Import\Model;

class Product extends \Magento\Framework\Model\AbstractModel
{

    protected $_productFactory;

    public function __construct(
        \Magento\Catalog\Model\ProductFactory $productFactory
    )
    {
        $this->_productFactory = $productFactory;
    }

    public function getProduct($sku)
    {
        $product = $this->_productFactory->create()->loadByAttribute('sku', $sku);
        $res = null;
        if ($product)
            $res = $product->getId();

        return $res;
    }

    public function addProduct($sku, $name, $size, $color, $price, $cat, $qty, $attr, $cat2)
    {
        echo '<pre> S- ' . $sku . '</pre>';
        $id= $this->getProduct($sku);
        if($id) return $id;

        $p = $this->_productFactory->create();

        $p->setSku($sku);
        $p->setName($name);
        $p->setDescription($name);
        $p->setUrlKey($sku);
        $p->setAttributeSetId(4);
        $p->setSize($size);
        $p->setColor($color);
        $p->setStatus(1);
        $p->setTypeId('simple');
        $p->setPrice($price);
        $p->setTaxClassId(0);
        $p->setWeight(1);
        $p->setWebsiteIds(array(1));
        $p->setCategoryIds(array($cat));
        $p->setStockData(array(
                'use_config_manage_stock' => 0,
                'manage_stock' => 1,
                'min_sale_qty' => 1,
                'max_sale_qty' => 2,
                'is_in_stock' => 1,
                'qty' => $qty
            )
        );

        $p->setStoreId(0);

        $p->setVisibility(1);
        $p->setData('qvd_arc_icvr', $attr[22]);  //קוד ארץ יצור
        $p->setData('qvd_evdpim', $attr[20]);    //קוד עודפים
        $p->setData('qvd_evnh', $attr[12]);  //קוד עונה
        $p->setData('qvd_mbce_hnvivt', $attr[29]);   //קוד מבצע חנויות
        $p->setData('qvd_mhlqh', $attr[8]); //קוד מחלקה
        $p->setData('qvd_mwphh', $attr[16]); //קוד משפחה
        $p->setData('qvd_spq', $attr[41]);   //קוד ספק
        $p->setData('qvd_svg_prit', $attr[18]);  //קוד סוג פריט

        $p->setData('wm_aizi_17', $attr[44]);
        $p->setData('wm_arc_icvr', $attr[23]);
        $p->setData('wm_evnh', $attr[13]);
        $p->setData('wm_mvtg', $attr[28]);
        $p->setData('wm_qvlqcih', $attr[38]);
        $p->setData('wm_svg_prit', $attr[19]);
        $p->setData('wm_tqcib', $attr[40]);
        $p->setData('wm_wnh', $attr[15]);

        $p->setData('cat_default', $cat2);

        //$imagePath = "/catalog/product/a/1.png";
        //$p->addImageToMediaGallery($imagePath, array('image', 'small_image', 'thumbnail'), false, false);

        $res = $p->save();
        return $res->getId();

    }


    public function addConfigurableProduct($sku, $name, $price, $cat, $simple_product_ids , $colors, $sizes, $attr, $cat2)
    {
        echo '<pre> C- ' . $sku . '</pre>';
        $id= $this->getProduct($sku);
        if($id) return $id;

        $p = $this->_productFactory->create();

        $p->setSku($sku);
        $p->setName($name);
        $p->setDescription($name);
        $p->setUrlKey($sku);
        $p->setAttributeSetId(4);
        $p->setStatus(1);
        $p->setWeight(1);
        $p->setTypeId('configurable');
        $p->setPrice($price);
        $p->setWebsiteIds(array(1));
        $p->setCategoryIds(array($cat));
        $p->setStockData(array(
                'use_config_manage_stock' => 0,
                'manage_stock' => 1,
                'is_in_stock' => 1,
            )
        );

        $p->setData('cat_default', $cat2);
        $p->setVisibility(4);
        $p->setStoreId(0);
        $p->setData('cat_default', $cat2);

        $p->setData('qvd_arc_icvr', $attr[22]);  //קוד ארץ יצור
        $p->setData('qvd_evdpim', $attr[20]);    //קוד עודפים
        $p->setData('qvd_evnh', $attr[12]);  //קוד עונה
        $p->setData('qvd_mbce_hnvivt', $attr[29]);   //קוד מבצע חנויות
        $p->setData('qvd_mhlqh', $attr[8]); //קוד מחלקה
        $p->setData('qvd_mwphh', $attr[16]); //קוד משפחה
        $p->setData('qvd_spq', $attr[41]);   //קוד ספק
        $p->setData('qvd_svg_prit', $attr[18]);  //קוד סוג פריט

        $p->setData('wm_aizi_17', $attr[44]);
        $p->setData('wm_arc_icvr', $attr[23]);
        $p->setData('wm_evnh', $attr[13]);
        $p->setData('wm_mvtg', $attr[28]);
        $p->setData('wm_qvlqcih', $attr[38]);
        $p->setData('wm_svg_prit', $attr[19]);
        $p->setData('wm_tqcib', $attr[40]);
        $p->setData('wm_wnh', $attr[15]);

        //$imagePath = "/catalog/product/a/1.png";
        //$p->addImageToMediaGallery($imagePath, array('image', 'small_image', 'thumbnail'), false, false);

        $p->getTypeInstance()->setUsedProductAttributeIds(array(93, 137), $p);
        $configurableAttributesData = $p->getTypeInstance()->getConfigurableAttributesAsArray($p);

        $p->setCanSaveConfigurableAttributes(true);
        $p->setConfigurableAttributesData($configurableAttributesData);
        $p->setConfigurableProductLinks($simple_product_ids);
        $p->setAssociatedProductIds($simple_product_ids);

        $configurableProductsData = array();

        foreach ($simple_product_ids as $simple_product_id){
            $configurableProductsData[$simple_product_id] = array(
                '0' => array(
                    'label' => 'Color',
                    'attribute_id' => '93',
                    'value_index' => $colors[$simple_product_id],
                    'is_percent' => 0,
                    'pricing_value' => '10',
                ),
                '1' => array(
                    'label' => 'Size',
                    'attribute_id' => '137',
                    'value_index' => $sizes[$simple_product_id],
                    'is_percent' => 0,
                    'pricing_value' => '10',
                )
            );
        }

        $p->setConfigurableProductsData($configurableProductsData);

        $res = $p->save();
        return $res->getId();
    }
}