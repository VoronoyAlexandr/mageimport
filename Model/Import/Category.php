<?php

/**
 * Class Insoft_Cib_Model_Import_Category
 */
class Insoft_Cib_Model_Import_Category extends Mage_Core_Model_Abstract implements ImportStrategyInterface
{
    /**
     * @param array $attributes
     */
    public function importData($attributes)
    {
        $count = 0;
        foreach ($attributes as $attribute) {

            $category = Mage::getModel('catalog/category')->getCollection();
            try {
                $parentId = $attribute['parent'] ? $this->getParentId($attribute['parent']) : 2;
                if ($loadedCategory = $category
                    ->addAttributeToFilter('name', $attribute['name'])
                    ->addAttributeToFilter('parent_id', $parentId)->getFirstItem()
                ) {
                    $category = $loadedCategory->load($loadedCategory->getId());
                }
                $category->setName($attribute['name']);
                $category->setIsActive(1);
                $category->setDisplayMode('PRODUCTS');
                $category->setIsAnchor(1);
                $category->setStoreId(Mage::app()->getStore()->getId());
                $parentCategory = Mage::getModel('catalog/category')->load($parentId);
                if (!$category->getPath()) {
                    $category->setPath($parentCategory->getPath());
                } else {
                    $category->setPath($parentCategory->getPath() . '/' . $category->getId());
                }
                if (array_key_exists('delivery_text_category', $attribute)) {
                    $category->setData('insoft_delivery_text_category', $attribute['delivery_text_category']);
                }
                if (array_key_exists('min_price_category', $attribute)) {
                    $category->setData('insoft_min_price_category', $attribute['min_price_category']);
                }
                if (array_key_exists('delivery_date_category', $attribute)) {
                    $category->setData('insoft_delivery_date_category', $attribute['delivery_date_category']);
                }
                $category->save();
                $count++;
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('insoft_cib')->__('Создано категорий: ' . $count));
        $process = Mage::getSingleton('index/indexer')->getProcessByCode('catalog_category_flat');
        $process->reindexEverything();
    }

    /**
     * @param $parentName
     * @return mixed
     */
    public function getParentId($parentName)
    {
        $category = Mage::getModel('catalog/category')->loadByAttribute('name', $parentName);
        if (!$category) {
            return null;
        }

        return $category->getId();
    }
}