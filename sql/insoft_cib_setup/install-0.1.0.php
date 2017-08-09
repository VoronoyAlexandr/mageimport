<?php
/* @var $installer Insoft_Cib_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Adding minimal price attribute for category
 */
if ($installer->checkAttribute('catalog_category', 'insoft_min_price_category')) {
    $installer->addAttribute("catalog_category", "insoft_min_price_category", array(
        "type" => "int",
        'backend' => 'eav/entity_attribute_backend_array',
        "frontend" => "",
        "source" => "",
        "label" => Mage::helper('insoft_cib')->__('Минимальная цена'),
        "input" => "text",
        "global" => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        "visible" => true,
        "required" => false,
        "user_defined" => true,
        "searchable" => false,
        "filterable" => false,
        "comparable" => false,
        'group' => Mage::helper('insoft_cib')->__('Прием-Отправка'),
        "visible_on_front" => false,
    ));
}
/**
 * Adding delivery text attributes for category
 */
if ($installer->checkAttribute('catalog_category', 'insoft_delivery_text_category')) {
    $installer->addAttribute('catalog_category', 'insoft_delivery_text_category', array(
        'group' => Mage::helper('insoft_cib')->__('Прием-Отправка'),
        'input' => 'textarea',
        'type' => 'text',
        'label' => Mage::helper('insoft_cib')->__('Описание сроков поставки'),
        'backend' => '',
        'visible' => true,
        'required' => false,
        'wysiwyg_enabled' => true,
        'visible_on_front' => false,
        'is_html_allowed_on_front' => true,
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));
}
/**
 * Adding delivery date attribute for category
 */
if ($installer->checkAttribute('catalog_category', 'insoft_delivery_date_category')) {
    $installer->addAttribute("catalog_category", 'insoft_delivery_date_category', array(
        'group' => Mage::helper('insoft_cib')->__('Прием-Отправка'),
        'input' => 'date',
        'type' => 'datetime',
        'label' => Mage::helper('insoft_cib')->__('Конечная дата заказа'),
        'backend' => "eav/entity_attribute_backend_datetime",
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'searchable' => false,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => false,
        'visible_in_advanced_search' => false,
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));
}

/**
 * Adding delivery date attribute for product
 */
if ($installer->checkAttribute('catalog_product', 'insoft_delivery_date_product')) {
    $installer->addAttribute('catalog_product', 'insoft_delivery_date_product', array(
        'group' => Mage::helper('insoft_cib')->__('Прием-Отправка'),
        'input' => 'date',
        'type' => 'datetime',
        'label' => Mage::helper('insoft_cib')->__('Конечная дата заказа'),
        'backend' => "eav/entity_attribute_backend_datetime",
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'searchable' => false,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => false,
        'visible_in_advanced_search' => false,
        'is_html_allowed_on_front' => false,
        'used_for_sort_by' => true,
        'used_in_product_listing' => true,
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

    $installer->addAttributeToSet(
        'catalog_product',
        'Default',
        'General',
        'insoft_delivery_date_product'
    );
}

/**
 * Adding minimal price attribute for product
 */
if ($installer->checkAttribute('catalog_product', 'insoft_min_price_product')) {
    $installer->addAttribute('catalog_product', 'insoft_min_price_product', array(
        'group' => Mage::helper('insoft_cib')->__('Прием-Отправка'),
        "label" => Mage::helper('insoft_cib')->__('Минимальная цена'),
        'type' => 'int',
        'input' => 'text',
        'backend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'searchable' => false,
        'filterable' => true,
        'comparable' => false,
        'visible_on_front' => false,
        'used_in_product_listing' => true,
        'visible_in_advanced_search' => false,
    ));
    $installer->addAttributeToSet(
        'catalog_product',
        'Default',
        'General',
        'insoft_min_price_product'
    );
}
/**
 * Adding delivery text attributes for product
 */
if ($installer->checkAttribute('catalog_product', 'insoft_delivery_text_product')) {
    $installer->addAttribute('catalog_product', 'insoft_delivery_text_product', array(
        'group' => Mage::helper('insoft_cib')->__('Прием-Отправка'),
        'input' => 'textarea',
        'type' => 'text',
        'label' => Mage::helper('insoft_cib')->__('Описание сроков поставки'),
        'visible' => true,
        'required' => false,
        'wysiwyg_enabled' => true,
        'visible_on_front' => false,
        'is_html_allowed_on_front' => true,
        'used_in_product_listing' => true,
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

    $installer->addAttributeToSet(
        'catalog_product',
        'Default',
        'General',
        'insoft_delivery_text_product'
    );
}
if ($installer->checkAttribute('catalog_product', 'insoft_fasovka_value')) {
    $installer->addAttribute('catalog_product', 'insoft_fasovka_value', array(
        'group' => Mage::helper('insoft_cib')->__('Прием-Отправка'),
        "label" => Mage::helper('insoft_cib')->__('Фасовка - Значение'),
        'type' => 'varchar',
        'input' => 'text',
        'backend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'searchable' => false,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => false,
        'used_in_product_listing' => true,
        'visible_in_advanced_search' => false,
    ));
    $installer->addAttributeToSet(
        'catalog_product',
        'Default',
        'General',
        'insoft_fasovka_value'
    );
}
if ($installer->checkAttribute('catalog_product', 'insoft_fasovka_type')) {
    $installer->addAttribute('catalog_product', 'insoft_fasovka_type', array(
        'group' => Mage::helper('insoft_cib')->__('Прием-Отправка'),
        "label" => Mage::helper('insoft_cib')->__('Фасовка - Тип'),
        'type' => 'varchar',
        'input' => 'text',
        'backend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'searchable' => false,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => false,
        'used_in_product_listing' => true,
        'visible_in_advanced_search' => false,
    ));
    $installer->addAttributeToSet(
        'catalog_product',
        'Default',
        'General',
        'insoft_fasovka_type'
    );
}

if ($installer->checkAttribute('catalog_product', 'insoft_priem_otpravka_id')) {
    $installer->addAttribute('catalog_product', 'insoft_priem_otpravka_id', array(
        'group' => Mage::helper('insoft_cib')->__('Прием-Отправка'),
        "label" => Mage::helper('insoft_cib')->__('Номер категории Прием-Отправка'),
        'type' => 'int',
        'input' => 'text',
        'backend' => '',
        'source' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'searchable' => false,
        'filterable' => true,
        'comparable' => false,
        'visible_on_front' => false,
        'used_in_product_listing' => true,
        'visible_in_advanced_search' => false,
    ));
    $installer->addAttributeToSet(
        'catalog_product',
        'Default',
        'General',
        'insoft_priem_otpravka_id'
    );
}

/* @var $installer Mage_Sales_Model_Resource_Setup */
$installer = new Mage_Sales_Model_Resource_Setup('core_setup');
/**
 * Add 'custom_attribute' attribute for entities
 */
$entities = array(
    'quote',
    'quote_address',
    'quote_item',
    'quote_address_item',
    'order',
    'order_item'
);
$options_date = array(
    'type' => Varien_Db_Ddl_Table::TYPE_DATE,
    'visible' => true,
    'required' => false
);
$options_integer = array(
    'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'visible' => true,
    'required' => false
);
foreach ($entities as $entity) {
    $installer->addAttribute($entity, 'insoft_delivery_date_product', $options_date);
    $installer->addAttribute($entity, 'insoft_min_price_product', $options_integer);
    $installer->addAttribute($entity, 'insoft_priem_otpravka_id', $options_integer);
}
$installer->endSetup();
