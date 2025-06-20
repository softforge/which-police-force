<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_whichpoliceforce
 *
 * @copyright   Copyright (C) 2025 SoftForge. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_whichpoliceforce - Bridge for com_ajax
 *
 * @since  1.0.0
 * @deprecated  2.0  Use the namespaced helper instead
 */
class ModWhichPoliceForceHelper
{
    /**
     * Ajax entry point for com_ajax
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public static function getAjax()
    {
        // Include the namespaced helper
        JLoader::registerNamespace('Joomla\\Module\\WhichPoliceForce', JPATH_SITE . '/modules/mod_whichpoliceforce/src');
        
        // Call the namespaced helper
        \Joomla\Module\WhichPoliceForce\Site\Helper\WhichPoliceForceHelper::getAjax();
    }
}