<?php

/**
 * Plugin Name:       WP GraphQL Municipio
 * Plugin URI:
 * Description:       Integrates WP GraphQL with custom post types created with Municipio
 * Version:           0.1.0
 * Author:            Nikolas Ramstedt
 * Author URI:
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       wp-graphql-municipio
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('WPGRAPHQLMUNICIPIO', plugin_dir_path(__FILE__));
define('WPGRAPHQLMUNICIPIO_URL', plugins_url('', __FILE__));
define('WPGRAPHQLMUNICIPIO_TEMPLATE_PATH', WPGRAPHQLMUNICIPIO . 'templates/');

load_plugin_textdomain('wp-graphql-municipio', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once WPGRAPHQLMUNICIPIO . 'source/php/Vendor/Psr4ClassLoader.php';
require_once WPGRAPHQLMUNICIPIO . 'Public.php';

// Instantiate and register the autoloader
$loader = new WPGraphQLMunicipio\Vendor\Psr4ClassLoader();
$loader->addPrefix('WPGraphQLMunicipio', WPGRAPHQLMUNICIPIO);
$loader->addPrefix('WPGraphQLMunicipio', WPGRAPHQLMUNICIPIO . 'source/php/');
$loader->register();

// Acf auto import and export
$acfExportManager = new \AcfExportManager\AcfExportManager();
$acfExportManager->setTextdomain('wp-graphql-municipio');
$acfExportManager->setExportFolder(WPGRAPHQLMUNICIPIO . 'source/php/AcfFields/');
$acfExportManager->autoExport(array(
    'location' => 'group_5ce4f4ffad4f0',
    'calendar' => 'group_5ce508bf360b2',
    'activities' => 'group_5ce4ef761078e',
    'lunch_menu' => 'group_5ce7c8020683c',
    'clock_events' => 'group_5ceacf9ce6ae4'
));
$acfExportManager->import();

// Start application
new WPGraphQLMunicipio\App();
