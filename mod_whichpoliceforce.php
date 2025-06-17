<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_whichpoliceforce
 *
 * @copyright   Copyright (C) 2025 SoftForge. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\Service\Provider\Module as ModuleServiceProvider;
use Joomla\CMS\Factory;
use Joomla\DI\Container;

// Load the service provider
$container = Factory::getContainer();
$container->registerServiceProvider(new ModuleServiceProvider);

// Get the module dispatcher
$dispatcher = $container->get(Joomla\CMS\Dispatcher\ModuleDispatcherFactoryInterface::class)->createDispatcher($module, $app);

// Include the helper factory
$dispatcher->setHelperFactory($container->get(Joomla\CMS\Helper\HelperFactoryInterface::class));

// Dispatch the module
$dispatcher->dispatch();