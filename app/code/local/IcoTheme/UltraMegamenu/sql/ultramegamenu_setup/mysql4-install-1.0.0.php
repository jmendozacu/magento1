<?php
/**
 *
 * ------------------------------------------------------------------------------
 * @category     IcoTheme
 * @package      IcoTheme_Themes
 * ------------------------------------------------------------------------------
 * @copyright    Copyright (C) 2014 IcoTheme.com. All Rights Reserved.
 * @license      GNU General Public License version 2 or later;
 * @author       IcoTheme.com
 * @email        support@ultramegamenu.com
 * ------------------------------------------------------------------------------
 *
 */
?>
<?php
$installer = $this;
$installer->startSetup();

$installer->addAttribute('catalog_category', 'ico_ulmenu_cat_block_right', array(
    'group' => 'UltraMegamenu',
    'label' => 'Block Right',
    'note' => "This field is applicable only for top-level categories.",
    'type' => 'text',
    'input' => 'textarea',
    'visible' => true,
    'required' => false,
    'backend' => '',
    'frontend' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'user_defined' => true,
    'visible_on_front' => true,
    'wysiwyg_enabled' => true,
    'is_html_allowed_on_front' => true,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->addAttribute('catalog_category', 'ico_ulmenu_proportions_right', array(
    'group' => 'UltraMegamenu',
    'label' => 'Proportions: Block Right',
    'note' => "Proportions block Block Right. This field is applicable only for top-level categories.",
    'type' => 'varchar',
    'input' => 'select',
    'source' => 'ultramegamenu/system_config_source_category_attribute_source_block_proportions',
    'visible' => true,
    'required' => false,
    'backend' => '',
    'frontend' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'user_defined' => true,
    'visible_on_front' => true,
    'wysiwyg_enabled' => false,
    'is_html_allowed_on_front' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->addAttribute('catalog_category', 'ico_ulmenu_cat_block_left', array(
    'group' => 'UltraMegamenu',
    'label' => 'Block Left',
    'note' => "This field is applicable only for top-level categories.",
    'type' => 'text',
    'input' => 'textarea',
    'visible' => true,
    'required' => false,
    'backend' => '',
    'frontend' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'user_defined' => true,
    'visible_on_front' => true,
    'wysiwyg_enabled' => true,
    'is_html_allowed_on_front' => true,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->addAttribute('catalog_category', 'ico_ulmenu_proportions_left', array(
    'group' => 'UltraMegamenu',
    'label' => 'Proportions: Block Left',
    'note' => "Proportions block Block Left. This field is applicable only for top-level categories.",
    'type' => 'varchar',
    'input' => 'select',
    'source' => 'ultramegamenu/system_config_source_category_attribute_source_block_proportions',
    'visible' => true,
    'required' => false,
    'backend' => '',
    'frontend' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'user_defined' => true,
    'visible_on_front' => true,
    'wysiwyg_enabled' => false,
    'is_html_allowed_on_front' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->addAttribute('catalog_category', 'ico_ulmenu_cat_columns', array(
    'group' => 'UltraMegamenu',
    'label' => 'Categories Columns',
    'note' => "This field is applicable only for top-level categories.",
    'type' => 'varchar',
    'input' => 'select',
    'source' => 'ultramegamenu/system_config_source_category_attribute_source_block_proportions',
    'visible' => true,
    'required' => false,
    'backend' => '',
    'frontend' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'user_defined' => true,
    'visible_on_front' => true,
    'wysiwyg_enabled' => false,
    'is_html_allowed_on_front' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->addAttribute('catalog_category', 'ico_ulmenu_cat_groups', array(
    'group' => 'UltraMegamenu',
    'label' => 'Category Menu Type',
    'note' => "Show subcategories by. This field is applicable only for top-level categories.",
    'type' => 'varchar',
    'input' => 'select',
    'source' => 'ultramegamenu/system_config_source_category_attribute_source_block_types',
    'visible' => true,
    'required' => false,
    'backend' => '',
    'frontend' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'user_defined' => true,
    'visible_on_front' => true,
    'wysiwyg_enabled' => false,
    'is_html_allowed_on_front' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->addAttribute('catalog_category', 'ico_ulmenu_cat_block_top', array(
    'group' => 'UltraMegamenu',
    'label' => 'Block Top',
    'type' => 'text',
    'input' => 'textarea',
    'visible' => true,
    'required' => false,
    'backend' => '',
    'frontend' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'user_defined' => true,
    'visible_on_front' => true,
    'wysiwyg_enabled' => true,
    'is_html_allowed_on_front' => true,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->addAttribute('catalog_category', 'ico_ulmenu_cat_block_bottom', array(
    'group' => 'UltraMegamenu',
    'label' => 'Block Bottom',
    'type' => 'text',
    'input' => 'textarea',
    'visible' => true,
    'required' => false,
    'backend' => '',
    'frontend' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'user_defined' => true,
    'visible_on_front' => true,
    'wysiwyg_enabled' => true,
    'is_html_allowed_on_front' => true,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->addAttribute('catalog_category', 'ico_ulmenu_cat_label', array(
    'group' => 'UltraMegamenu',
    'label' => 'Category Label',
    'note' => "Labels have to be defined in menu settings",
    'type' => 'varchar',
    'input' => 'select',
    'source' => 'ultramegamenu/system_config_source_category_attribute_source_categorylabel',
    'visible' => true,
    'required' => false,
    'backend' => '',
    'frontend' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'user_defined' => true,
    'visible_on_front' => true,
    'wysiwyg_enabled' => false,
    'is_html_allowed_on_front' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));

$installer->addAttribute('catalog_category', 'ico_ulmenu_is_category', array(
    'group' => 'UltraMegamenu',
    'label' => 'Display in menu',
    'note' => "Display category in menu. This field is applicable only for top-level categories.",
    'type' => 'varchar',
    'input' => 'select',
    'source' => 'ultramegamenu/system_config_source_category_attribute_source_block_display',
    'visible' => true,
    'required' => false,
    'backend' => '',
    'frontend' => '',
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'user_defined' => true,
    'visible_on_front' => true,
    'wysiwyg_enabled' => false,
    'is_html_allowed_on_front' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
));


$installer->endSetup();