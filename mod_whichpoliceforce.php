<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_whichpoliceforce
 *
 * @copyright   Copyright (C) 2025 SoftForge. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\WhichPoliceForce\Site\Helper\WhichPoliceForceHelper;

// Get the helper
require_once __DIR__ . '/helper.php';

// Get module parameters
$showTitle = $params->get('show_title', 1);
$apiUrl = $params->get('api_url', 'https://data.police.uk/api/locate-neighbourhood');

// Load the default layout
require ModuleHelper::getLayoutPath('mod_whichpoliceforce', $params->get('layout', 'default'));