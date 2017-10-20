<?php

class TV_Articles_Model_Articles extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('articles/articles');
    }

    public function getArticles() {
        $articles = Mage::getModel('articles/articles')->getCollection()
                        ->addAttributeToSelect('*')
                        ->setOrder('articles_id', 'DESC')
                        ->setPageSize($this->get_cur_page());
        return $articles;
    }

}
