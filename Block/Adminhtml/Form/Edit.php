<?php

/**
 * Class Insoft_Cib_Block_Adminhtml_Form_Edit
 */
class Insoft_Cib_Block_Adminhtml_Form_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Insoft_Cib_Block_Adminhtml_Form_Edit constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->removeButton('back')
            ->removeButton('reset')
            ->_updateButton('save', 'label', $this->__('Загрузить'));
    }

    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'insoft_cib';
        $this->_controller = 'adminhtml_form';
        $this->_mode = 'edit';
        $this->_headerText = Mage::helper('insoft_cib')->__('Импорт');
    }
}