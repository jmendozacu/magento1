<?php

class TV_Articles_Block_Articles extends Mage_Core_Block_Template {

    public function getArticles() {
        // call model to fetch data
        $arr_articles = array();
        $articles = Mage::getModel("articles/articles")->getArticles();

        foreach ($products as $product) {
            $arr_articles[] = array(
                'id' => $product - ­ > getId(),
                'title' => $product­->getTitle(),
                'short_desc' => $product­->getShortDecs(),
            );
        }

        return $arr_articles;
    }

}
