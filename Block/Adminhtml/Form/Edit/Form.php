<?php

/**
 * Class Insoft_Cib_Block_Adminhtml_Form_Edit_Form
 */
class Insoft_Cib_Block_Adminhtml_Form_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/validate'),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));
        $fieldset = $form->addFieldset('base_fieldset',
            array('legend' => Mage::helper('insoft_cib')->__('Настройки импорта')));

        $fieldset->addField('import_entity', 'select', array(
            'name' => 'import_entity',
            'title' => Mage::helper('insoft_cib')->__('Тип импорта'),
            'label' => Mage::helper('insoft_cib')->__('Тип импорта'),
            'required' => true,
            'values' => array(
                array(
                    'value' => Insoft_Cib_Model_Import::ENTITY_CATEGORY,
                    'label' => Mage::helper('insoft_cib')->__('Категории')
                ),
                array(
                    'value' => Insoft_Cib_Model_Import::ENTITY_PRODUCT,
                    'label' => Mage::helper('insoft_cib')->__('Товары')
                ),
            ),
        ));

        $fieldset->addField('file', 'file', array(
            'name' => 'file',
            'label' => Mage::helper('insoft_cib')->__('Выберите файл для импорта'),
            'title' => Mage::helper('insoft_cib')->__('Выберите файл для импорта'),
            'required' => true
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}