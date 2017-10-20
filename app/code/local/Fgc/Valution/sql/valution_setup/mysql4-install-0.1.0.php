<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table valutionrequest(id int not null auto_increment,
    your_name varchar(100),
    your_email varchar(100),
    phone_number varchar(10),
    country varchar(10),
    message_titile varchar(100),        
    your_message varchar(512),    
    created_time   DATETIME,
    update_time  DATETIME ,
    primary key(id));
		
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 