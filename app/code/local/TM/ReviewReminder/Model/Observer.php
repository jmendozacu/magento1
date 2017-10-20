<?php
class TM_ReviewReminder_Model_Observer
{
    /**
     * Check order status and save it to review reminder table
     *
     * @param Varien_Event_Observer $observer
     * @return TM_ReviewReminder_Model_Observer
     */
    public function orderSaved(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('tm_reviewreminder')->isEnabled()) {
            return $this;
        }
        $order = $observer->getOrder();

        if (Mage::helper('tm_reviewreminder')->allowSpecificStatuses()) {
            $orderStatus = $order->getStatus();
            $allowedStatuses = Mage::helper('tm_reviewreminder')->specificOrderStatuses();
            if (in_array($orderStatus, $allowedStatuses)) {
                $this->saveOrder($order);
            }
        } else {
            $this->saveOrder($order);
        }

        return $this;
    }
    /**
     * Check order id and save it to review reminder table
     */
    protected function saveOrder($order)
    {
        $model = Mage::getModel('tm_reviewreminder/entity');
        $collection = $model->getCollection()
            ->addFieldTofilter('order_id', $order->getId());

        if ($collection->getSize() == 0) {
            $model->setOrderId($order->getId());
            $model->setCustomerEmail($order->getCustomerEmail());
            $model->setStatus(Mage::helper('tm_reviewreminder')->getDefaultStatus());
            $model->setOrderDate(Mage::helper('tm_reviewreminder')->getOrderDate($order));
            $model->setHash(Mage::helper('tm_reviewreminder')->generateHash());
            $model->save();
        }
    }
    /**
     * Send emails cron observer
     *
     * @param Mage_Cron_Model_Schedule $observer
     * @return TM_ReviewReminder_Model_Observer
     */
    public function sendEmails(Mage_Cron_Model_Schedule $observer)
    {
        Mage::helper('tm_reviewreminder')->sendReminders(null);
        return $this;
    }
}