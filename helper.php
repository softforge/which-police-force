<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_whichpoliceforce
 *
 * @copyright   Copyright (C) 2025 SoftForge. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Module\WhichPoliceForce\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Cache\Controller\CallbackController;

/**
 * Helper for mod_whichpoliceforce
 *
 * @since  1.0.0
 */
class WhichPoliceForceHelper
{
    /**
     * Get police force data for a postcode
     *
     * @param   string  $postcode  The postcode to look up
     * @param   string  $apiUrl    The API URL
     * @param   int     $cacheTime Cache time in seconds
     *
     * @return  object|false  Police force data or false on error
     *
     * @since   1.0.0
     */
    public static function getPoliceForce($postcode, $apiUrl, $cacheTime = 900)
    {
        // Clean the postcode
        $postcode = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $postcode));
        
        // Get cache controller
        $cache = Factory::getCache('mod_whichpoliceforce', 'callback');
        $cache->setCaching(true);
        $cache->setLifeTime($cacheTime);
        
        // Try to get from cache or fetch new
        $data = $cache->get(
            function($postcode, $apiUrl) {
                return self::fetchPoliceForceData($postcode, $apiUrl);
            },
            array($postcode, $apiUrl),
            'whichpoliceforce_' . $postcode
        );
        
        return $data;
    }
    
    /**
     * Fetch police force data from API
     *
     * @param   string  $postcode  The postcode
     * @param   string  $apiUrl    The API URL
     *
     * @return  object|false
     */
    private static function fetchPoliceForceData($postcode, $apiUrl)
    {
        try {
            // First get lat/lng from postcode
            $postcodeData = self::getPostcodeData($postcode);
            
            if (!$postcodeData) {
                return false;
            }
            
            // Now get police force from coordinates
            $http = HttpFactory::getHttp();
            $url = $apiUrl . '?q=' . $postcodeData->latitude . ',' . $postcodeData->longitude;
            
            $response = $http->get($url);
            
            if ($response->code !== 200) {
                return false;
            }
            
            $data = json_decode($response->body);
            
            if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
                return false;
            }
            
            // Add the postcode to the response
            $data->postcode = $postcode;
            
            return $data;
            
        } catch (\Exception $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            return false;
        }
    }
    
    /**
     * Get postcode data including lat/lng
     *
     * @param   string  $postcode  The postcode
     *
     * @return  object|false
     */
    private static function getPostcodeData($postcode)
    {
        try {
            $http = HttpFactory::getHttp();
            $url = 'https://api.postcodes.io/postcodes/' . $postcode;
            
            $response = $http->get($url);
            
            if ($response->code !== 200) {
                return false;
            }
            
            $data = json_decode($response->body);
            
            if (json_last_error() !== JSON_ERROR_NONE || !isset($data->result)) {
                return false;
            }
            
            return $data->result;
            
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Get detailed information about a police force
     *
     * @param   string  $forceId  The force identifier
     *
     * @return  object|false  Force details or false on error
     *
     * @since   1.0.0
     */
    public static function getForceDetails($forceId)
    {
        try {
            // Sanitize force ID
            $forceId = preg_replace('/[^a-z0-9\-]/', '', strtolower($forceId));
            
            if (empty($forceId)) {
                return false;
            }
            
            // Get cache controller
            $cache = Factory::getCache('mod_whichpoliceforce', 'callback');
            $cache->setCaching(true);
            $cache->setLifeTime(86400); // Cache for 24 hours
            
            // Try to get from cache or fetch new
            $data = $cache->get(
                function($forceId) {
                    $http = HttpFactory::getHttp();
                    $url = 'https://data.police.uk/api/forces/' . $forceId;
                    
                    $response = $http->get($url);
                    
                    if ($response->code !== 200) {
                        return false;
                    }
                    
                    $data = json_decode($response->body);
                    
                    if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
                        return false;
                    }
                    
                    return $data;
                },
                array($forceId),
                'force_details_' . $forceId
            );
            
            return $data;
            
        } catch (\Exception $e) {
            return false;
        }
    }
}