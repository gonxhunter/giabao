<?php
/**
 * Created by PhpStorm.
 * User: chutienphuc
 * Date: 20/09/2017
 * Time: 18:17
 */
class SM_Visitor_Model_Resource_Count extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('sm_visitor/count','id');
    }
}