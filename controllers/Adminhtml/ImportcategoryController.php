<?php

/**
 * Class Insoft_Cib_Adminhtml_ImportcategoryController
 */
class Insoft_Cib_Adminhtml_ImportcategoryController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @return void
     */
    public function indexAction()
    {
        $this->_title($this->__('Импорт категорий'));

        $this->loadLayout();
        $this->_addBreadcrumb(
            Mage::helper('insoft_cib')->__('Импорт категорий'),
            Mage::helper('insoft_cib')->__('Импорт категорий')
        );
        $this->renderLayout();
    }

    /**
     * @return $this|Mage_Core_Controller_Varien_Action
     */
    public function validateAction()
    {
        $data = $this->getRequest()->getPost();
        if (!empty($data)) {
            try {
                $fileName = Mage::helper('insoft_core')->uploadFile();
            } catch (Mage_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Файл не загружен'));
                Mage::logException($e);

                return $this->_redirect('*/*/edit');
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('insoft_cib')->__('Файл успешно загружен'));
            $entity = Mage::helper('insoft_cib')->getConfig(Insoft_Cib_Model_Import::IMPORT_ENTITY_TYPE_FILE_NODE_PATH);
            $importData = Mage::helper($entity[$data['import_entity']])->getCsvRow($fileName);
            if (!empty($importData)) {
                Mage::getModel('insoft_cib/import')->Import($data['import_entity'], $importData);
            }

        }

        return $this->_redirect('*/*/');
    }
}