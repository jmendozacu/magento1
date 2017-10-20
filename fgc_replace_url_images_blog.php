<?php

ini_set('memory_limit', '2048M');

set_time_limit(0);

$_SERVER['SCRIPT_NAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_NAME']);
$_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_FILENAME']);

//require_once('/var/www/html/royyoungchemist.com.au/application/site/app/Mage.php');
require_once(dirname(dirname(__FILE__)) . '/app/Mage.php');
umask(0);
Mage::app();
$awcBlogApi = new AW_Blog_Model_Api;

$posts = $awcBlogApi->getPosts();
foreach ($posts as $post) {
//---
    $pattern = array('/http:\/\/admin.royyoungchemist.com.au/', '/http:\/\/staging.royyoungchemist.com.au/');
    $replacement = 'http://www.royyoungchemist.com.au';
//---
    $short_content = $post->getShortContent();
    $short_content = preg_replace($pattern, $replacement, $short_content);
//--
    $content = $post->getPostContent();
    $content = preg_replace($pattern, $replacement, $content);
    //--
    $post->setData('short_content', $short_content);
    $post->setData('post_content', $content);
    $post->save();
}
echo 'Process success';
exit();
?>
