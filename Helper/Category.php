<?php

/**
 * Class Insoft_Cib_Helper_Category
 */
class Insoft_Cib_Helper_Category extends Mage_Core_Helper_Abstract
{

    /**
     * @param string $file
     * @return array|null
     */
    public function getCsvRow($file)
    {
        if (($handle = fopen($file, "r")) !== false) {
            $categories = [];
            $count = -1;
            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                if ($count == -1) {
                    $count++;
                    continue;
                }
                list($name,
                    $parent,
                    $delivery_text_category,
                    $min_price_category,
                    $delivery_date_category
                    ) = $data;
                $categories[$count]['name'] = $name;
                $categories[$count]['parent'] = $parent;
                $categories[$count]['delivery_text_category'] = $delivery_text_category;
                $categories[$count]['min_price_category'] = $min_price_category;
                $categories[$count]['delivery_date_category'] = $delivery_date_category;
                $count++;
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('insoft_cib')->__('Импортировано категорий: ' . $count));
            fclose($handle);

            return $categories;
        }

        return null;
    }
}