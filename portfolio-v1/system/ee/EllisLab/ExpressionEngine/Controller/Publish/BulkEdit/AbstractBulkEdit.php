<?php
/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2018, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

namespace EllisLab\ExpressionEngine\Controller\Publish\BulkEdit;

use CP_Controller;

/**
 * Abstract Bulk Edit Controller
 */
abstract class AbstractBulkEdit extends CP_Controller {

	public function __construct()
	{
		parent::__construct();

		ee()->lang->loadfile('content');
	}

	/**
	 * Given a collection of entries, lets us know if the logged-in member has
	 * permission to edit all entries
	 *
	 * @param Collection $entries Entries to check editing permissions for
	 * @return Boolean Whether or not the logged-in member has permission
	 */
	protected function hasPermissionToEditEntries($entries)
	{
		$author_ids = array_unique($entries->Author->getIds());
		$member_id = ee()->session->userdata('member_id');

		// Can edit others' entries?
		if ( ! ee('Permission')->has('can_edit_other_entries'))
		{
			$other_authors = array_diff($author_ids, [$member_id]);

			if (count($other_authors))
			{
				return FALSE;
			}
		}

		// Can edit own entries?
		if ( ! ee('Permission')->has('can_edit_self_entries') &&
			in_array($member_id, $author_ids))
		{
			return FALSE;
		}

		// Finally, assigned channels
		$assigned_channel_ids = array_keys(ee()->session->userdata('assigned_channels'));
		$editing_channel_ids = $entries->Channel->getIds();

		$disallowed_channels = array_diff($editing_channel_ids, $assigned_channel_ids);

		return count($disallowed_channels) == 0;
	}

	/**
	 * Renders the Fluid UI markup for a given set of fields
	 *
	 * @param Array $displayed_fields Fields that should be displayed on load
	 * @param Array $template_fields Fields to keep off screen as available templates
	 * @param Array $filter_fields Fields to display in the Add menu
	 * @param Result $errors Validation result for the given fields, or NULL
	 * @return String HTML markup of Fluid UI
	 */
	protected function getFluidMarkupForFields($displayed_fields, $template_fields, $filter_fields, $errors = NULL)
	{
		$filters = '';
		if ( ! empty($filter_fields))
		{
			$filters = ee('View')->make('fluid_field:filters')->render(['fields' => $filter_fields]);
		}

		$displayed_fields_markup = '';
		foreach ($displayed_fields as $field_name => $field)
		{
			$displayed_fields_markup .= ee('View')->make('fluid_field:field')->render([
				'field' => $field,
				'field_name' => $field_name,
				'filters' => '',
				'errors' => $errors,
				'reorderable' => FALSE,
				'show_field_type' => FALSE
			]);
		}

		$template_fields_markup = '';
		foreach ($template_fields as $field_name => $field)
		{
			$template_fields_markup .= ee('View')->make('fluid_field:field')->render([
				'field' => $field,
				'field_name' => $field_name,
				'filters' => '',
				'errors' => NULL,
				'reorderable' => FALSE,
				'show_field_type' => FALSE
			]);
		}

		return ee('View')->make('fluid_field:publish')->render([
			'fields'          => $displayed_fields_markup,
			'field_templates' => $template_fields_markup,
			'filters'         => $filters,
		]);
	}

	/**
	 * Given an entry, returns the FieldFacades for the available FieldFacades
	 * for that entry
	 *
	 * @param ChannelEntry $entry Channel entry object to render fields from
	 * @return Array Associative array of FieldFacades
	 */
	protected function getCategoryFieldsForEntry($entry)
	{
		$fields = [];
		foreach ($entry->Channel->CategoryGroups->getIds() as $cat_group)
		{
			$fields[] = 'categories[cat_group_id_'.$cat_group.']';
		}

		$field_facades = $this->getFieldsForEntry($entry, $fields);
		foreach ($field_facades as $field)
		{
			// Cannot edit categories in this view
			$field->setItem('editable', FALSE);
			$field->setItem('editing', FALSE);
		}

		return $field_facades;
	}

	/**
	 * Given an entry, returns the FieldFacades for the given field names
	 *
	 * @param ChannelEntry $entry Channel entry object to render fields from
	 * @param Array $fields Array of field short names to render
	 * @return Array Associative array of FieldFacades
	 */
	protected function getFieldsForEntry($entry, $fields)
	{
		$fields = array_filter($fields, [$entry, 'hasCustomField']);

		$field_facades = [];
		foreach ($fields as $field)
		{
			$field_facades[$field] = $entry->getCustomField($field);
		}

		return $field_facades;
	}

	/**
	 * Given a Collection of channels, returns a channel entry object assigned
	 * to an intersected channel
	 *
	 * @param Collection $channels Collection of channels
	 * @return ChannelEntry
	 */
	protected function getMockEntryForIntersectedChannels($channels)
	{
		$entry = ee('Model')->make('ChannelEntry');
		$entry->entry_id = 0;
		$entry->author_id = ee()->session->userdata('member_id');
		$entry->Channel = $this->getIntersectedChannel($channels);

		return $entry;
	}

	/**
	 * Given a Collection of channels, returns a channel object with traits each
	 * channel has in common, currently category groups and statuses
	 *
	 * @param Collection $channels Collection of channels
	 * @return Channel
	 */
	protected function getIntersectedChannel($channels)
	{
		$channels = $channels->intersect();

		// All entries belong to the same channel, easy peasy!
		if ($channels->count() < 2)
		{
			return $channels->first();
		}

		$channel = ee('Model')->make('Channel');
		$channel->cat_group = implode(
			'|',
			$channels->CategoryGroups->intersect()->getIds()
		);
		$channel->Statuses = $channels->Statuses->intersect();

		// Only enable if ALL channels have comments enabled
		$channel->comment_system_enabled = ! in_array(FALSE, $channels->pluck('comment_system_enabled'), TRUE);

		return $channel;
	}

}

// EOF
