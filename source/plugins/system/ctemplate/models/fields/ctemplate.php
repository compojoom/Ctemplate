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

JFormHelper::loadFieldClass('groupedlist');

/**
 * Form Field class for the Joomla CMS.
 * Supports a select grouped list of template
 *
 * @since  1.0
 */
class JFormFieldCtemplate extends JFormFieldGroupedList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'Ctemplate';

	/**
	 * Method to get the list of templates
	 *
	 * Use the client attribute to specify a specific client.
	 *
	 * @return  array  The field option objects as a nested array in groups.
	 *
	 * @since   1.6
	 */
	protected function getGroups()
	{
		$groups = array();
		$lang = JFactory::getLanguage();

		// Get the client and client_id.
		$clientName = $this->element['client'] ? (string) $this->element['client'] : 'site';
		$client = JApplicationHelper::getClientInfo($clientName, true);

		// Get the database object and a new query object.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Build the query.
		$query->select('s.id, s.title, e.name as name, s.template')
			->from('#__template_styles as s')
			->where('s.client_id = ' . (int) $client->id)
			->order('template')
			->order('title');

		$query->join('LEFT', '#__extensions as e on e.element=s.template')
			->where('e.enabled=1')
			->where($db->quoteName('e.type') . '=' . $db->quote('template'));

		// Set the query and load the styles.
		$db->setQuery($query);
		$styles = $db->loadObjectList();

		// Build the grouped list array.
		if ($styles)
		{
			foreach ($styles as $style)
			{
				$template = $style->template;
				$lang->load('tpl_' . $template . '.sys', $client->path, null, false, false)
				|| $lang->load('tpl_' . $template . '.sys', $client->path . '/templates/' . $template, null, false, false)
				|| $lang->load('tpl_' . $template . '.sys', $client->path, $lang->getDefault(), false, false)
				|| $lang->load('tpl_' . $template . '.sys', $client->path . '/templates/' . $template, $lang->getDefault(), false, false);
				$name = JText::_($style->name);

				// Initialize the group if necessary.
				if (!isset($groups[$name]))
				{
					$groups[$name] = array();
				}

				$groups[$name][] = JHtml::_('select.option', $style->template, $style->title);
			}
		}

		// Merge any additional groups in the XML definition.
		$groups = array_merge(parent::getGroups(), $groups);

		return $groups;
	}
}
