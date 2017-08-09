<?php

/**
 * Class Insoft_Cib_Model_Resource_Setup
 */
class Insoft_Cib_Model_Resource_Setup extends Mage_Catalog_Model_Resource_Setup
{

    /**
     * Check and delete existing attribute
     * @param string $entity
     * @param string $code
     * @return bool
     */
    public function checkAttribute($entity, $code)
    {
        $attribute = Mage::getResourceModel('catalog/eav_attribute')->loadByCode($entity, $code);
        if ($attribute->getId()) {
            Mage::getResourceModel('catalog/setup', 'catalog_setup')->removeAttribute($entity, $code);
        }

        return true;
    }
}