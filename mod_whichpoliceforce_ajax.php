<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_whichpoliceforce
 *
 * @copyright   Copyright (C) 2025 SoftForge. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Session\Session;
use Joomla\Module\WhichPoliceForce\Site\Helper\WhichPoliceForceHelper;

// Check CSRF token
if (!Session::checkToken('get')) {
    echo new JsonResponse(null, 'Invalid Token', true);
    jexit();
}

// Get the application
$app = Factory::getApplication();
$input = $app->input;

// Get postcode from request
$postcode = $input->getString('postcode', '');

if (empty($postcode)) {
    echo new JsonResponse(null, 'Please provide a postcode', true);
    jexit();
}

// Include the helper
require_once __DIR__ . '/helper.php';

try {
    // Get module parameters (use default API URL if not in module context)
    $apiUrl = 'https://data.police.uk/api/locate-neighbourhood';
    $cacheTime = 900; // 15 minutes default
    
    // Get police force data
    $data = WhichPoliceForceHelper::getPoliceForce($postcode, $apiUrl, $cacheTime);
    
    if ($data === false) {
        echo new JsonResponse(null, 'Unable to find police force for this postcode', true);
        jexit();
    }
    
    // Get force details
    $forceDetails = WhichPoliceForceHelper::getForceDetails($data->force);
    
    // Prepare response data
    $response = [
        'postcode' => $postcode,
        'force' => $data->force ?? 'Unknown',
        'neighbourhood' => $data->neighbourhood ?? 'Unknown',
        'force_name' => $forceDetails->name ?? $data->force ?? 'Unknown Police Force',
        'force_url' => $forceDetails->url ?? null,
        'force_telephone' => $forceDetails->telephone ?? null,
        'force_description' => $forceDetails->description ?? null
    ];
    
    echo new JsonResponse($response);
    
} catch (Exception $e) {
    echo new JsonResponse(null, 'An error occurred while processing your request', true);
}

jexit();