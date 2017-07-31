<?php
class Mycompany_Contactsave_Block_Adminhtml_Contactdata_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Mycompany_Contactsave_Block_Adminhtml_Contactdata_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('contactdata_');
        $form->setFieldNameSuffix('contactdata');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'contactdata_form',
            array('legend' => Mage::helper('mycompany_contactsave')->__('Báo lỗi sản phẩm'))
        );

        $fieldset->addField(
            'customer_name',
            'text',
            array(
                'label' => Mage::helper('mycompany_contactsave')->__('Customer Name'),
                'name'  => 'customer_name',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'customer_email',
            'text',
            array(
                'label' => Mage::helper('mycompany_contactsave')->__('Email'),
                'name'  => 'customer_email',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'customer_phone',
            'text',
            array(
                'label' => Mage::helper('mycompany_contactsave')->__('Telephone'),
                'name'  => 'customer_phone',

           )
        );

        $fieldset->addField(
            'customer_comment',
            'textarea',
            array(
                'label' => Mage::helper('mycompany_contactsave')->__('Nội dung lỗi sản phẩm'),
                'name'  => 'customer_comment',
                'required'  => true,
                'class' => 'required-entry',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('mycompany_contactsave')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('mycompany_contactsave')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('mycompany_contactsave')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_contactdata')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getContactdataData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getContactdataData());
            Mage::getSingleton('adminhtml/session')->setContactdataData(null);
        } elseif (Mage::registry('current_contactdata')) {
            $formValues = array_merge($formValues, Mage::registry('current_contactdata')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
