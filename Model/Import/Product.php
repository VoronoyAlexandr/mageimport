<?php

/**
 * Class Insoft_Cib_Model_Import_Product
 */
class Insoft_Cib_Model_Import_Product extends Mage_Core_Model_Abstract implements ImportStrategyInterface
{

    protected $_updateCount;
    protected $_expectedCount;
    protected $_disabledCount;

    /**
     * @param array $attributes
     */
    public function importData($attributes)
    {
        $count = 0;
        $this->_updateCount = 0;
        $this->_expectedCount = 0;
        $this->_disabledCount = 0;

        foreach ($attributes as $attribute) {
            $product = Mage::getModel('catalog/product');

            try {
                $categoryIds = explode(',', $attribute['category_ids']);

                if ($loadedProduct = $product->loadByAttribute('sku', $attribute['sku'])) {
                    $product = $loadedProduct->load($loadedProduct->getId());
                    $this->updateProduct($product, $attribute, $categoryIds);
                    $this->_updateCount++;
                } else {
                    $attributeSetId = Mage::getModel('catalog/config')->getAttributeSetId('catalog_product', 'Phone');
                    $product
                        ->setStoreId(0)//you can set data in store scope
                        ->setWebsiteIds(array(1))//website ID the product is assigned to, as an array
                        ->setAttributeSetId($attributeSetId)//ID of a attribute set named 'default'
                        ->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)//product type
                        ->setCreatedAt(strtotime('now'))//product creation time
                        ->setUpdatedAt(strtotime('now'))
                        ->setSku($attribute['sku'])//SKU
                        ->setName($attribute['name'])//product name
                        ->setWeight(1.0000)
                        ->setStatus(1)//product status (1 - enabled, 2 - disabled)
                        ->setTaxClassId(2)//tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
                        ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)//catalog and search visibility
                        ->setPrice($attribute['price'])//price in form 11.22
                        ->setDescription($attribute['description'])
                        ->setShortDescription($attribute['short_description']);

                    if ($product->getId()) {
                        $items = $this->getMediaAPI($product->getId());
                    } else {
                        $items = '';
                    }


                    if (empty($items)) {
                        $product->setMediaGallery(array(
                            'images' => array(),
                            'values' => array()
                        ));//media gallery initialization
                        if (file_exists('media/import' . $attribute['image']) && exif_imagetype('media/import' . $attribute['image']) == IMAGETYPE_JPEG) {
                            $product
                                ->addImageToMediaGallery('media/import' . $attribute['image'],
                                    array('image', 'thumbnail', 'small_image'), false,
                                    false);
                        } else {
                            Mage::log("SKU:  " . $product->getSku() . ' - ' . $product->getName(), null,
                                'noimage_product.log');
                        }
                    }
                    $product
                        ->setStockData(array(
                                'use_config_manage_stock' => 0, //'Use config settings' checkbox
                                'manage_stock' => 0, //manage stock
                            )
                        )
                        ->setData('insoft_fasovka_value', $attribute['fasovka_value'])
                        ->setData('insoft_fasovka_type', $attribute['fasovka_type'])
                        ->setData('diametr_cvetka', $attribute['flower_size'])
                        ->setData('hit_of_sales', $attribute['hit_of_sales'])
                        ->setData('blossom', $attribute['blossom']);


                    /**
                     * For SEO
                     */
                    $product
                        ->setMetaTitle($attribute['name'] . ' ' . $attribute['fasovka_value'] . ' ' . $attribute['fasovka_type'] . '  купить семена почтой - ')
                        ->setMetaDescription('Купите семена : ' . $attribute['name'] . ' ' . $attribute['fasovka_value'] . ' ' . $attribute['fasovka_type'] . '. Саженцы, рассада и семена почтой по всей Украине.')
                        ->setMetaKeyword('Сільський вісник, ' . $attribute['name'] . ', купить семена, семена почтой, рассада, саженцы');


                    /**
                     * For Receiving Shipping
                     */
                    $receivingShippingId = Mage::getModel('insoft_cib/import_category')->getParentId($attribute['priem_otpravka']);
                    $category = Mage::getModel('catalog/category')->load($receivingShippingId)->getData();
                    if (array_key_exists('insoft_delivery_text_category', $category)) {
                        $product->setData('insoft_delivery_text_product', $category['insoft_delivery_text_category']);
                    }
                    if (array_key_exists('insoft_min_price_category', $category)) {
                        $product->setData('insoft_min_price_product', $category['insoft_min_price_category']);
                    }
                    if (array_key_exists('insoft_delivery_date_category', $category)) {
                        $product->setData('insoft_delivery_date_product', $category['insoft_delivery_date_category']);


                        /**
                         * Expected Date
                         */
                        $deliveryDate = $category['insoft_delivery_date_category'];
                        $deliveryDate = date("d-m-Y", strtotime($deliveryDate));
                        $expectedDate = Mage::helper('insoft_conditions')->getExpectedDate($deliveryDate);
                        $product->setData('insoft_conditions_date', $expectedDate);

                        $date = Mage::getModel('core/date')->gmtDate();

                        if($date > $expectedDate) {
                            $product->setData('insoft_conditions_flag', 1);
                            $this->_expectedCount++;
                        }else{
                            $product->setData('insoft_conditions_flag', 0);
                            $this->_disabledCount++;
                        }


                    }
                    if ($receivingShippingId) {
                        array_push($categoryIds, $receivingShippingId);
                        $product->setData('insoft_priem_otpravka_id', $receivingShippingId);
                    }
                    $product->setCategoryIds($categoryIds);//assign product to categories
                    Mage::log("Product  " . $product->getSku() . '-' . $product->getName(), null, 'product_add.log');
                    $product->getOptionInstance()->unsetOptions();
                    $product->setHasOptions(0);
                    $product->setRequiredOptions(0);
                    $product->save();
                    $count++;

                }
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::log("Product with sku %s isn't import " . $attribute['sku'], null, 'not_save_product.log');
            }
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('insoft_cib')->__('Создано товаров: ' . $count));
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('insoft_cib')->__('Обновлено товаров: ' . $this->_updateCount));
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('insoft_cib')->__('Ожидается: ' . $this->_expectedCount));
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('insoft_cib')->__('Больше не ожидается: ' . $this->_disabledCount));
      
    }

    public function getMediaAPI($productId)
    {
        $mediaApi = Mage::getModel("catalog/product_attribute_media_api");
        $items = $mediaApi->items($productId);

        return $items;

    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param array $attribute
     * @param $categoryIds
     */
    public function updateProduct(Mage_Catalog_Model_Product $product, array $attribute, $categoryIds)
    {
        if (!empty($attribute['sku'])) {
            $product->setData('sku', $attribute['sku']);
        }
        if (!empty($attribute['name'])) {
            $product->setData('name', $attribute['name']);
        }
        if (!empty($attribute['price'])) {
            $product->setData('price', $attribute['price']);
        }
        if (!empty($attribute['description'])) {
            $product->setData('description', $attribute['description']);
        }
        if (!empty($attribute['short_description'])) {
            $product->setData('short_description', $attribute['short_description']);
        }
        if (!empty($attribute['fasovka_value'])) {
            $product->setData('insoft_fasovka_value', $attribute['fasovka_value']);
        }
        if (!empty($attribute['fasovka_type'])) {
            $product->setData('insoft_fasovka_type', $attribute['fasovka_type']);
        }
        if (!empty($attribute['flower_size'])) {
            $product->setData('diametr_cvetka', $attribute['flower_size']);
        }
        if (!empty($attribute['hit_of_sales'])) {
            $product->setData('hit_of_sales', $attribute['hit_of_sales']);
        }
        if (!empty($attribute['blossom'])) {
            $product->setData('blossom', $attribute['blossom']);
        }

        if (!empty($attribute['priem_otpravka'])) {
            $receivingShippingId = Mage::getModel('insoft_cib/import_category')->getParentId($attribute['priem_otpravka']);
            $category = Mage::getModel('catalog/category')->load($receivingShippingId)->getData();
            if (array_key_exists('insoft_delivery_text_category', $category)) {
                $product->setData('insoft_delivery_text_product', $category['insoft_delivery_text_category']);
            }
            if (array_key_exists('insoft_min_price_category', $category)) {
                $product->setData('insoft_min_price_product', $category['insoft_min_price_category']);
            }
            if (array_key_exists('insoft_delivery_date_category', $category)) {
                $product->setData('insoft_delivery_date_product', $category['insoft_delivery_date_category']);

                $deliveryDate = $category['insoft_delivery_date_category'];
                $deliveryDate = date("d-m-Y", strtotime($deliveryDate));
                $expectedDate = Mage::helper('insoft_conditions')->getExpectedDate($deliveryDate);
                $product->setData('insoft_conditions_date', $expectedDate);

                $date = Mage::getModel('core/date')->gmtDate();

                if($date > $expectedDate) {
                    $product->setData('insoft_conditions_flag', 1);
                    $this->_expectedCount++;
                }else{
                    $product->setData('insoft_conditions_flag', 0);
                    $this->_disabledCount++;
                }

            }
            if ($receivingShippingId) {
                array_push($categoryIds, $receivingShippingId);
                $product->setData('insoft_priem_otpravka_id', $receivingShippingId);
            }
            if (!empty($categoryIds)) {
                $product->setCategoryIds($categoryIds);//assign product to categories
            }

        }
        if ($product->getId()) {
            $items = $this->getMediaAPI($product->getId());
        } else {
            $items = '';
        }


        if (empty($items)) {
            $product->setMediaGallery(array(
                'images' => array(),
                'values' => array()
            ));//media gallery initialization
            if (file_exists('media/import' . $attribute['image']) && exif_imagetype('media/import' . $attribute['image']) == IMAGETYPE_JPEG) {
                $product
                    ->addImageToMediaGallery('media/import' . $attribute['image'],
                        array('image', 'thumbnail', 'small_image'), false,
                        false);
            } else {
                Mage::log("SKU:  " . $product->getSku() . ' - ' . $product->getName(), null,
                    'noimage_product.log');
            }
        }
        $product
            ->setStockData(array(
                    'use_config_manage_stock' => 0, //'Use config settings' checkbox
                    'manage_stock' => 0, //manage stock
                )
            );
        $product->getOptionInstance()->unsetOptions();
        $product->setHasOptions(0);
        $product->setRequiredOptions(0);
        $product->save();
        Mage::log("Product  " . $product->getSku() . '-' . $product->getName(), null, 'product_updated.log');
    }
}