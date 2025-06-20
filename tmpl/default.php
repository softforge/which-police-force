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

// Get document
$document = Factory::getApplication()->getDocument();

// Add CSS and JavaScript directly
$document->addStyleSheet('/whichpolice-test/media/mod_whichpoliceforce/css/style.css');
$document->addScript('/whichpolice-test/media/mod_whichpoliceforce/js/script.js', [], ['defer' => true]);
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
        ajaxUrl: '<?php echo Route::_('index.php?option=com_ajax&module=whichpoliceforce&format=json'); ?>'
    };
</script>