<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_whichpoliceforce
 *
 * @copyright   Copyright (C) 2025 SoftForge. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Module\WhichPoliceForce\Site\Dispatcher;

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

/**
 * Dispatcher class for mod_whichpoliceforce
 *
 * @since  1.0.0
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;
    
    /**
     * Returns the layout data.
     *
     * @return  array
     *
     * @since   1.0.0
     */
    protected function getLayoutData()
    {
        $data = parent::getLayoutData();

        $data['showTitle'] = $data['params']->get('show_title', 1);
        $data['apiUrl'] = $data['params']->get('api_url', 'https://data.police.uk/api/locate-neighbourhood');
        $data['postcodeApiUrl'] = $data['params']->get('postcode_api_url', 'https://api.postcodes.io/postcodes/');
        
        // Display options
        $data['showPostcode'] = $data['params']->get('show_postcode', 1);
        $data['showNeighbourhood'] = $data['params']->get('show_neighbourhood', 1);
        $data['showWebsite'] = $data['params']->get('show_website', 1);
        $data['showPhone'] = $data['params']->get('show_phone', 1);
        $data['showCoordinates'] = $data['params']->get('show_coordinates', 0);
        
        // Individual area detail options
        $data['showDistrict'] = $data['params']->get('show_district', 1);
        $data['showWard'] = $data['params']->get('show_ward', 1);
        $data['showParish'] = $data['params']->get('show_parish', 1);
        $data['showConstituency'] = $data['params']->get('show_constituency', 1);
        $data['showRegion'] = $data['params']->get('show_region', 1);
        $data['showCountry'] = $data['params']->get('show_country', 1);

        return $data;
    }
}