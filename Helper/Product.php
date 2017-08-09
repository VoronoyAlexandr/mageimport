<?php

/**
 * Class Insoft_Cib_Helper_Product
 */
class Insoft_Cib_Helper_Product extends Mage_Core_Helper_Abstract
{
    /**
     * @param string $file
     * @return array|null
     */
    public function getCsvRow($file)
    {
        if (($handle = fopen($file, "r")) !== false) {
            $products = [];
            $count = -1;
            while (($data = fgetcsv($handle, null, ";")) !== false) {
                if ($count == -1) {
                    $count++;
                    continue;
                }
                list(
                    $attributeSetId,
                    $sku,
                    $name,
                    $image,
                    $flower_size,
                    $price,
                    $weight,
                    $hit_of_sales,
                    $description,
                    $short_description,
                    $fasovka_value,
                    $fasovka_type,
                    $category_ids,
                    $priem_otpravka,
                    $blossom
                    ) = $data;
                $products[$count]['attributeSetId'] = $attributeSetId;
                $products[$count]['sku'] = $sku;
                $products[$count]['name'] = $name;
                $products[$count]['image'] = $image;
                $products[$count]['flower_size'] = $flower_size;
                $products[$count]['price'] = $price;
                $products[$count]['weight'] = $weight;
                $products[$count]['hit_of_sales'] = $hit_of_sales;
                $products[$count]['description'] = $description;
                $products[$count]['short_description'] = $short_description;
                $products[$count]['fasovka_value'] = $fasovka_value;
                $products[$count]['fasovka_type'] = $fasovka_type;
                $products[$count]['category_ids'] = $category_ids;
                $products[$count]['priem_otpravka'] = $priem_otpravka;
                $products[$count]['blossom'] = $blossom;
                Mage::log("Product " . $sku . ' -' . $name, null, 'product_import.log');
                $count++;
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('insoft_cib')->__('Импортировано товаров: ' . $count));
            fclose($handle);

            return $products;
        }

        return null;
    }
}