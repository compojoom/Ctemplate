<?php
/**
 * @package    Plg_Ctemplate
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       17.10.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class PlgSystemCtemplate
 *
 * @since  1.0
 */
class PlgSystemCtemplate extends JPlugin
{
	/**
	 * This function will check if we are in the frontend,
	 * if the plugin has the necessary params and then will assign
	 * our desired template if the user belongs to the specified group
	 *
	 * @return void
	 */
	public function onAfterInitialise()
	{
		$appl = JFactory::getApplication();
		$params = $this->params;

		// If we are in the frontend and plugin is configured properly
		if ($appl->isSite() && $params->get('usergroup', 0))
		{
			$user = JFactory::getUser();

			// If the user belongs to the group, let us to the magic!
			if (in_array($this->params->get('usergroup'), $user->getAuthorisedGroups()))
			{
				// Set the new template
				if ($params->get('template'))
				{
					$appl->input->set('template', $params->get('template'));
				}
			}
		}
	}
}
