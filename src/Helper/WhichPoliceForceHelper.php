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
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Session\Session;

/**
 * Helper for mod_whichpoliceforce
 *
 * @since  1.0.0
 */
class WhichPoliceForceHelper
{
    /**
     * Ajax method to get police force data
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public static function getAjax()
    {
        // Check CSRF token
        if (!Session::checkToken('get')) {
            echo new JsonResponse(null, 'Invalid Token', true);
            Factory::getApplication()->close();
            return;
        }
        
        $app = Factory::getApplication();
        $input = $app->input;
        
        // Get postcode from request
        $postcode = $input->getString('postcode', '');
        
        if (empty($postcode)) {
            echo new JsonResponse(null, 'Please provide a postcode', true);
            Factory::getApplication()->close();
            return;
        }
        
        try {
            // Get module parameters (use default API URLs if not in module context)
            $apiUrl = 'https://data.police.uk/api/locate-neighbourhood';
            $postcodeApiUrl = 'https://api.postcodes.io/postcodes/';
            $cacheTime = 900; // 15 minutes default
            
            // Get police force data
            $data = self::getPoliceForce($postcode, $apiUrl, $cacheTime);
            
            if ($data === false) {
                echo new JsonResponse(null, 'Unable to find police force for this postcode', true);
                Factory::getApplication()->close();
                return;
            }
            
            // Get force details
            $forceDetails = self::getForceDetails($data->force);
            
            // Get postcode details for area information
            $postcodeDetails = self::getPostcodeData($postcode, $postcodeApiUrl);
            
            // Prepare response data
            $response = [
                'postcode' => strtoupper($postcode),
                'force' => $data->force ?? 'Unknown',
                'neighbourhood' => $data->neighbourhood ?? 'Unknown',
                'force_name' => $forceDetails->name ?? $data->force ?? 'Unknown Police Force',
                'force_url' => $forceDetails->url ?? null,
                'force_telephone' => $forceDetails->telephone ?? null,
                'force_description' => $forceDetails->description ?? null,
                'latitude' => $postcodeDetails->latitude ?? null,
                'longitude' => $postcodeDetails->longitude ?? null,
                // Area information from postcode data
                'area' => [
                    'district' => $postcodeDetails->admin_district ?? null,
                    'ward' => $postcodeDetails->admin_ward ?? null,
                    'parish' => $postcodeDetails->parish ?? null,
                    'constituency' => $postcodeDetails->parliamentary_constituency ?? null,
                    'region' => $postcodeDetails->region ?? null,
                    'country' => $postcodeDetails->country ?? null
                ]
            ];
            
            echo new JsonResponse($response);
            Factory::getApplication()->close();
            
        } catch (\Exception $e) {
            echo new JsonResponse(null, 'An error occurred while processing your request', true);
            Factory::getApplication()->close();
        }
    }
    
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
                return self::fetchPoliceForceData($postcode, $apiUrl, 'https://api.postcodes.io/postcodes/');
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
     * @param   string  $postcodeApiUrl The postcode API URL
     *
     * @return  object|false
     */
    private static function fetchPoliceForceData($postcode, $apiUrl, $postcodeApiUrl = 'https://api.postcodes.io/postcodes/')
    {
        try {
            // First get lat/lng from postcode
            $postcodeData = self::getPostcodeData($postcode, $postcodeApiUrl);
            
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
     * @param   string  $postcodeApiUrl The postcode API URL
     *
     * @return  object|false
     */
    private static function getPostcodeData($postcode, $postcodeApiUrl = 'https://api.postcodes.io/postcodes/')
    {
        try {
            $http = HttpFactory::getHttp();
            $url = rtrim($postcodeApiUrl, '/') . '/' . $postcode;
            
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