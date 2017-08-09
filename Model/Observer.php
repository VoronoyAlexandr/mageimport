<?php

/**
 * Class Insoft_Cib_Model_Observer
 */
class Insoft_Cib_Model_Observer
{
    public $countTotal = [];

    /**
     * @param $observer
     * @return mixed
     */
    public function salesQuoteItemSetProduct($observer)
    {
        $quoteItem = $observer->getQuoteItem();
        $product = $observer->getProduct();
        $quoteItem->setData('insoft_delivery_date_product', $product->getData('insoft_delivery_date_product'));
        $quoteItem->setData('insoft_min_price_product', $product->getData('insoft_min_price_product'));
        $quoteItem->setData('insoft_delivery_text_product', $product->getData('insoft_delivery_text_product'));
        $quoteItem->setData('insoft_priem_otpravka_id', $product->getData('insoft_priem_otpravka_id'));

        return $quoteItem;
    }

    public function addErrorMessageForGroup($observer)
    {
        $count_total = 0;
        $quote = $observer->getQuote();
        $allItems = $observer->getQuote()->getAllItems();
        $_items = Mage::helper('insoft_cib')->arrayMapCheckout($allItems);
        foreach ($_items as $key => $_item) {
            foreach ($_item as $value) {
                $count_total = $this->countTotalGroup($key, $value->getData('base_row_total'));
            }
            if (!$this->checkMinimalPriceGroup($_item[0]->getData('insoft_min_price_product'),
                $count_total[$key])
            ) {
                $quote->setHasError(true);
                $quote->addErrorInfo(
                    'error',
                    'checkout',
                    null,
                    Mage::helper('checkout')->__('ВНИМАНИЕ! В корзине могут находится товары из разных групп “Приема-отправки”. Пока не будут удовлетворены все условия по минимальным суммам для каждой группы, невозможно продолжить оформление заказа.'),
                    null
                );
            }
        }
    }

    public function checkMinimalPriceGroup($minimalPrice, $totalGroup)
    {
        if ((int)$minimalPrice > (int)$totalGroup) {
            return false;
        } else {

            return true;
        }
    }

    public function countTotalGroup($group, $price)
    {

        $this->countTotal[$group] += $price;

        return $this->countTotal;
    }
}