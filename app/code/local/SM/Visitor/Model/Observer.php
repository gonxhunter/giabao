<?php
/**
 * Created by PhpStorm.
 * User: chutienphuc
 * Date: 21/09/2017
 * Time: 17:27
 */
class SM_Visitor_Model_Observer
{
    public function updateCount()
    {
        $collection = Mage::getModel('sm_visitor/count')->getCollection()
            ->addFieldToFilter('IP', array('eq' => $_SERVER['REMOTE_ADDR']))
            ->addFieldToFilter('Time', array('ep' => date('Y-m-d')));
        if(count($collection) == 0){
            $customData = array(
                'IP' => $_SERVER['REMOTE_ADDR'],
                'Time' => date('Y-m-d')
            );
            $model = Mage::getModel('sm_visitor/count');
            $model->addData($customData);
            $model->save();
        }
    }
}