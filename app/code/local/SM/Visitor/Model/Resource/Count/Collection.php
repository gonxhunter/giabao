<?php
/**
 * Created by PhpStorm.
 * User: chutienphuc
 * Date: 20/09/2017
 * Time: 18:18
 */
class SM_Visitor_Model_Resource_Count_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct(); // TODO: Change the autogenerated stub
        $this->_init('sm_visitor/count');
    }
}