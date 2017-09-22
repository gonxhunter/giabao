<?php
/**
 * Created by PhpStorm.
 * User: chutienphuc
 * Date: 20/09/2017
 * Time: 18:38
 */
class SM_Visitor_Block_Count extends Mage_Core_Block_Template
{
    public function getVisitorToday(){
        $collection = Mage::getModel('sm_visitor/count')->getCollection()
            ->addFieldToFilter('Time', array('ep' => date('Y-m-d')));
        return count($collection);
    }
    
    public function getVisitorAll(){
        $collection = Mage::getModel('sm_visitor/count')->getCollection();
        return count($collection);
    }
}