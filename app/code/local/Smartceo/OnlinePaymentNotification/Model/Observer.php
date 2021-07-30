<?php

class Smartceo_OnlinePaymentNotification_Model_Observer {

    public function implementOrderStatus($event) {
        $order = $event->getOrder();

        $order->getState();
//        $order->getStatus();
        if (Mage_Sales_Model_Order::STATE_PROCESSING == $order->getState()) {
            //отправление письма
            $this->_sendMail();
        }

        return $this;
    }

    /**
     * Отправка письма администратору магазина
     */
    protected function _sendMail() {
        //емайл магазина
        $storeemail = Mage::getStoreConfig('trans_email/ident_general/email');

        $store = Mage::app()->getStore();
        $storeName = $store->getName();
        
        //адрес получателя
        $mailTo = "";
        $nameTo = "";
        
        //текст письма
        $mailBody = "";
        $mailSubject = "";

        $mail = Mage::getModel('core/email');
        $mail->setToName($nameTo);
        $mail->setToEmail($mailTo);
        $mail->setBody($mailBody);
        $mail->setSubject($mailSubject);
        $mail->setFromEmail($storeemail);
        $mail->setFromName($storeName);
        $mail->setType('html'); // 'html' или 'text'
        try {
            $mail->send();
            Mage::getSingleton('core/session')->addSuccess('Your request has been sent');
        } catch (Exception $ex) {
            Mage::getSingleton('core/session')->addError('Unable to send email.');
        }
    }

}
