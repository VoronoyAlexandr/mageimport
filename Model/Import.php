<?php

/**
 * Interface ImportStrategyInterface
 */
interface ImportStrategyInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function importData($data);
}

/**
 * Class Insoft_Cib_Model_Import
 */
class Insoft_Cib_Model_Import extends Mage_Core_Model_Abstract
{
    const IMPORT_STRATEGY_FILE_NODE_PATH = 'global/insoft_strategy';
    const IMPORT_ENTITY_TYPE_FILE_NODE_PATH = 'global/insoft_entity_type';
    const ENTITY_CATEGORY = 'category';
    const ENTITY_PRODUCT = 'product';

    /**
     * @param string $entity
     * @param string $data
     */
    public function Import($entity, $data)
    {
        $strategy = Mage::helper('insoft_cib')->getConfig(Insoft_Cib_Model_Import::IMPORT_STRATEGY_FILE_NODE_PATH);
        Mage::getModel($strategy[$entity])->importData($data);

    }
}