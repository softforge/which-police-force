<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_whichpoliceforce
 *
 * @copyright   Copyright (C) 2025 SoftForge. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;

// In Joomla 5, the dispatcher passes data directly as variables, not in $displayData
// Set defaults for all variables we use
$showTitle = isset($showTitle) ? (bool) $showTitle : true;
$showPostcode = isset($showPostcode) ? (bool) $showPostcode : true;
$showNeighbourhood = isset($showNeighbourhood) ? (bool) $showNeighbourhood : true;
$showWebsite = isset($showWebsite) ? (bool) $showWebsite : true;
$showPhone = isset($showPhone) ? (bool) $showPhone : true;
$showCoordinates = isset($showCoordinates) ? (bool) $showCoordinates : false;

// Individual area detail options
$showDistrict = isset($showDistrict) ? (bool) $showDistrict : true;
$showWard = isset($showWard) ? (bool) $showWard : true;
$showParish = isset($showParish) ? (bool) $showParish : true;
$showConstituency = isset($showConstituency) ? (bool) $showConstituency : true;
$showRegion = isset($showRegion) ? (bool) $showRegion : true;
$showCountry = isset($showCountry) ? (bool) $showCountry : true;

$moduleclass_sfx = $moduleclass_sfx ?? '';
$module = $module ?? new stdClass();
if (!isset($module->id)) {
    $module->id = 0;
}


// Get document and Web Asset Manager
$document = Factory::getApplication()->getDocument();
$wa = $document->getWebAssetManager();

// Register the asset definition file
$wa->getRegistry()->addRegistryFile('media/mod_whichpoliceforce/joomla.asset.json');

// Ensure core JavaScript is loaded
$wa->useScript('core');

// Use the registered assets
$wa->useStyle('mod_whichpoliceforce.style');
$wa->useScript('mod_whichpoliceforce.script');

// Add inline script to load our module script
$wa->addInlineScript("
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.whichPoliceForceScriptLoaded === 'undefined') {
        var script = document.createElement('script');
        script.src = '" . \Joomla\CMS\Uri\Uri::root(true) . "/media/mod_whichpoliceforce/js/script.js';
        document.head.appendChild(script);
    }
});
");
?>

<div class="mod-whichpoliceforce <?php echo $moduleclass_sfx; ?>">
    <?php if ($showTitle) : ?>
        <h3><?php echo Text::_('MOD_WHICHPOLICEFORCE_TITLE'); ?></h3>
    <?php endif; ?>
    
    <div class="whichpoliceforce-form">
        <form id="whichpoliceforce-form-<?php echo $module->id; ?>" class="form-inline">
            <div class="form-group">
                <label for="postcode-<?php echo $module->id; ?>" class="sr-only">
                    <?php echo Text::_('MOD_WHICHPOLICEFORCE_POSTCODE_LABEL'); ?>
                </label>
                <input 
                    type="text" 
                    id="postcode-<?php echo $module->id; ?>" 
                    name="postcode" 
                    class="form-control whichpoliceforce-postcode" 
                    placeholder="<?php echo Text::_('MOD_WHICHPOLICEFORCE_POSTCODE_PLACEHOLDER'); ?>"
                    maxlength="8"
                    required
                />
            </div>
            <button type="submit" class="btn btn-primary">
                <?php echo Text::_('MOD_WHICHPOLICEFORCE_SUBMIT'); ?>
            </button>
        </form>
    </div>
    
    <div id="whichpoliceforce-result-<?php echo $module->id; ?>" class="whichpoliceforce-result" style="display:none;">
        <div class="alert alert-info">
            <h4 class="police-force-name"></h4>
            <p class="police-force-neighbourhood"></p>
            <div class="police-force-area"></div>
            <div class="police-force-links"></div>
        </div>
    </div>
    
    <div id="whichpoliceforce-error-<?php echo $module->id; ?>" class="whichpoliceforce-error" style="display:none;">
        <div class="alert alert-danger">
            <p class="error-message"></p>
        </div>
    </div>
    
    <div id="whichpoliceforce-loading-<?php echo $module->id; ?>" class="whichpoliceforce-loading" style="display:none;">
        <div class="spinner-border spinner-border-sm" role="status">
            <span class="sr-only"><?php echo Text::_('MOD_WHICHPOLICEFORCE_LOADING'); ?></span>
        </div>
    </div>
</div>

<script>
    // Pass module ID and AJAX URL to JavaScript
    window.whichPoliceForceConfig = window.whichPoliceForceConfig || {};
    window.whichPoliceForceConfig[<?php echo $module->id; ?>] = {
        moduleId: <?php echo $module->id; ?>,
        ajaxUrl: '<?php echo Route::_('index.php?option=com_ajax&module=whichpoliceforce&format=json', false); ?>',
        showPostcode: <?php echo json_encode((bool)$showPostcode); ?>,
        showNeighbourhood: <?php echo json_encode((bool)$showNeighbourhood); ?>,
        showWebsite: <?php echo json_encode((bool)$showWebsite); ?>,
        showPhone: <?php echo json_encode((bool)$showPhone); ?>,
        showCoordinates: <?php echo json_encode((bool)$showCoordinates); ?>,
        showDistrict: <?php echo json_encode((bool)$showDistrict); ?>,
        showWard: <?php echo json_encode((bool)$showWard); ?>,
        showParish: <?php echo json_encode((bool)$showParish); ?>,
        showConstituency: <?php echo json_encode((bool)$showConstituency); ?>,
        showRegion: <?php echo json_encode((bool)$showRegion); ?>,
        showCountry: <?php echo json_encode((bool)$showCountry); ?>
    };
    
</script>