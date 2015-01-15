<?php
/**
 * Listrak Remarketing Magento Extension Ver. 1.0.0
 *
 * PHP version 5
 *
 * @category  Listrak
 * @package   Listrak_Remarketing
 * @author    Listrak Magento Team <magento@listrak.com>
 * @copyright 2011 Listrak Inc
 * @license   http://s1.listrakbi.com/licenses/magento.txt License For Customer Use of Listrak Software
 * @link      http://www.listrak.com
 */

class Listrak_Remarketing_Model_Mysql4_Abandonedcart_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    private $_prepareForReport = false;

    protected function _construct()
    {
        $this->_init('listrak/abandonedcart');
    }

    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()
            ->join(
                array('q' => $this->getTable('sales/quote')),
                'main_table.quote_id = q.entity_id',
                array('items_qty', 'grand_total')
            )
            ->where('main_table.had_items = 1 AND q.is_active = 1'); // is_active is set to false when the order is submitted
    }

    public function addStoreFilter($storeIds)
    {
        $this->getSelect()->where('main_table.store_id IN (?)', $storeIds);
        return $this;
    }
    
    public function addClearCartTrimFilter($fromDate)
    {
        $this->getSelect()
            ->where("q.items_qty > 0 OR main_table.created_at <= '{$fromDate}'");
        return $this;
    }

    public function setPrepareForReport($prepareForReport)
    {
        $this->_prepareForReport = $prepareForReport;
        return $this;
    }

    protected function _afterLoad()
    {
        foreach ($this->_items as $item) {
            $item->afterLoad();
            if ($this->_prepareForReport === true) {
                $item->prepareForReport();
            }
        }

        return parent::_afterLoad();
    }
}
