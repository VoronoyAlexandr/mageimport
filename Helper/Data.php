<?php

/**
 * Class Insoft_Cib_Helper_Data
 */
class Insoft_Cib_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @var array
     */
    public $countTotal = [];

    public $priceDifference;

    /**
     * @param string $config
     * @return array|string
     */
    public function getConfig($config)
    {
        return Mage::getConfig()
            ->loadModulesConfiguration('config.xml')
            ->getNode($config)
            ->asArray();
    }

    /**
     * @param array $quoteItem
     * @return array
     */
    public function arrayMapCheckout(array $quoteItem)
    {
        $items = [];

        foreach ($quoteItem as $item) {
            $items[$item->getData('insoft_priem_otpravka_id')][] = $item;
        }

        return $items;
    }

    /**
     * @param int $group
     * @param int $price
     * @return array
     */
    public function countTotalGroup($group, $price)
    {

        $this->countTotal[$group] += $price;

        return $this->countTotal;
    }

    /**
     * @param int $minimalPrice
     * @param int $totalGroup
     * @return bool
     */
    public function checkMinimalPriceGroup($minimalPrice, $totalGroup)
    {
        $this->priceDifference = $minimalPrice - $totalGroup;
        if ((int)$minimalPrice > (int)$totalGroup) {
            $this->minimalPriceError();
        } else {

            return true;
        }
    }

    /**
     * @return void
     */
    public function minimalPriceError()
    {
        $quote = Mage::getModel('checkout/cart')->getQuote();
        $quote->setHasError(true);
        $quote->addErrorInfo(
            'error',
            'checkout',
            null,
            Mage::helper('checkout')->__('Общая цена группы, меньше необходимой'),
            null
        );
    }

    /**
     * @return mixed|null
     */
    public function getCurrentCategory()
    {
        return (Mage::registry('current_category') instanceof Mage_Catalog_Model_Category) ? Mage::registry('current_category')->getData('name') : null;
    }

    /**
     * @return int
     */
    public function getPriceDifference()
    {
        return (int)$this->priceDifference;
    }

    public function getUserInnerId()
    {

        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerData = Mage::getSingleton('customer/session')->getCustomer();

            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $table = $resource->getTableName('clients_site_1c');

            $query = 'SELECT * FROM ' . $table . " WHERE client_id =" . (int)$customerData->getId();;
            $results = $readConnection->fetchOne($query);
            if(empty($results)){
                return $this->__('Не присвоен');
            }else{
                return $results;
            }

        }
    }
}