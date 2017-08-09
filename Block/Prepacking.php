<?php

/**
 * Class Insoft_Cib_Block_Prepacking
 */
class Insoft_Cib_Block_Prepacking extends Mage_Core_Block_Template
{
    /**
     * @return mixed|null
     */
    public function prePacking()
    {
        return (Mage::registry('current_product') instanceof Mage_Catalog_Model_Product) ? Mage::registry('current_product')->getData() : null;
    }
}