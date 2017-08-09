<?php

/**
 * Class Insoft_Cib_Block_Categorydescription
 */
class Insoft_Cib_Block_Categorydescription extends Mage_Core_Block_Template
{
    /**
     * @return mixed|null
     */
    public function getCategoryDescription()
    {
        return (Mage::registry('current_category') instanceof Mage_Catalog_Model_Category) ? Mage::registry('current_category')->getData('insoft_delivery_text_category') : null;
    }
}