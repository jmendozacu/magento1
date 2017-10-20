<?php 
# Show error
error_reporting(E_ALL);
ini_set('display_errors','On');

# Stop timeouts
ini_set('memory_limit', '512M');
set_time_limit(0);

// Only for urls
// Don't remove this
$_SERVER['SCRIPT_NAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_NAME']);
$_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_FILENAME']);

# Boot the Mage App
require_once 'app/Mage.php';
umask(0);
Mage::app();

$installer = $this;
$installer->startSetup();
$installer->run("
    DROP TABLE IF EXISTS bitnami_magento.aw_advancedreviews_pc;
    CREATE TABLE IF NOT EXISTS bitnami_magento.aw_advancedreviews_pc(
        `id` bigint(20) unsigned NOT NULL auto_increment,
        `review_id` bigint(20) unsigned NOT NULL,
        `value` int(3) NOT NULL default '0',
        PRIMARY KEY  (`id`),
        KEY `FK_reviews_helpfulness` (`review_id`),
        CONSTRAINT `FK_reviews_helpfulness`
            FOREIGN KEY (`review_id`)
            REFERENCES `{$this->getTable('review/review')}` (`review_id`)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    DROP TABLE IF EXISTS bitnami_magento.aw_advancedreviews_pc;
    CREATE TABLE IF NOT EXISTS bitnami_magento.aw_advancedreviews_pc(
        `id` bigint(20) unsigned NOT NULL auto_increment,
        `store_id` smallint(5) unsigned NOT NULL default '0',
        `review_id` bigint(20) unsigned NOT NULL,
        `customer_name` varchar(255) NOT NULL default '',
        `abused_at` DATETIME NOT NULL,
        PRIMARY KEY  (`id`),
        KEY `FK_reviews_abuse` (`review_id`),
        KEY `FK_reviews_abuse_store` (`store_id`),
        CONSTRAINT `FK_reviews_abuse`
            FOREIGN KEY (`review_id`)
            REFERENCES `{$this->getTable('review/review')}` (`review_id`)
            ON DELETE CASCADE,
        CONSTRAINT `FK_reviews_abuse_store`
            FOREIGN KEY (`store_id`)
            REFERENCES `{$this->getTable('core/store')}` (`store_id`)
            ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    DROP TABLE IF EXISTS bitnami_magento.aw_advancedreviews_pc;
    CREATE TABLE IF NOT EXISTS bitnami_magento.aw_advancedreviews_pc(
        `id` bigint(20) unsigned NOT NULL auto_increment,
        `status`  smallint(2) unsigned NOT NULL,
        `type`  smallint(2) unsigned NOT NULL,
        `owner`  smallint(2) unsigned NOT NULL,
        `sort_order` int(10) NOT NULL default '0',
        `name` varchar(255) NOT NULL default '',
        PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    DROP TABLE IF EXISTS {$this->getTable('advancedreviews/proscons_vote')};
    CREATE TABLE IF NOT EXISTS {$this->getTable('advancedreviews/proscons_vote')}(
        `id` bigint(20) unsigned NOT NULL auto_increment,
        `review_id` bigint(20) unsigned NOT NULL,
        `proscons_id` bigint(20) unsigned NOT NULL,
        PRIMARY KEY  (`id`, `review_id`, `proscons_id`),
        KEY `FK_reviews_pc_votes` (`review_id`),
        KEY `FK_proscons_votes` (`proscons_id`),
        CONSTRAINT `FK_reviews_pc_votes`
            FOREIGN KEY (`review_id`)
            REFERENCES `{$this->getTable('review/review')}` (`review_id`)
            ON DELETE CASCADE,
        CONSTRAINT `FK_proscons_votes`
            FOREIGN KEY (`proscons_id`)
            REFERENCES `{$this->getTable('advancedreviews/proscons')}` (`id`)
            ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    DROP TABLE IF EXISTS {$this->getTable('advancedreviews/proscons_store')};
    CREATE TABLE IF NOT EXISTS {$this->getTable('advancedreviews/proscons_store')}(
        `proscons_id` bigint(20) unsigned NOT NULL auto_increment,
        `store_id` smallint(5) unsigned NOT NULL default '0',
        PRIMARY KEY  (`proscons_id`, `store_id`),
        KEY `FK_reviews_pc` (`proscons_id`),
        KEY `FK_reviews_pc_store` (`store_id`),
        CONSTRAINT `FK_proscons_pc`
            FOREIGN KEY (`proscons_id`)
            REFERENCES `{$this->getTable('advancedreviews/proscons')}` (`id`)
            ON DELETE CASCADE,
        CONSTRAINT `FK_proscons_pc_store`
            FOREIGN KEY (`store_id`)
            REFERENCES `{$this->getTable('core/store')}` (`store_id`)
            ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    DROP TABLE IF EXISTS {$this->getTable('advancedreviews/recommend')};
    CREATE TABLE IF NOT EXISTS {$this->getTable('advancedreviews/recommend')}(
        `id` bigint(20) unsigned NOT NULL auto_increment,
        `review_id` bigint(20) unsigned NOT NULL,
        `value` int(3) NOT NULL default '0',
        PRIMARY KEY  (`id`),
        KEY `FK_reviews_recommend` (`review_id`),
        CONSTRAINT `FK_reviews_recommend`
            FOREIGN KEY (`review_id`)
            REFERENCES `{$this->getTable('review/review')}` (`review_id`)
            ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();