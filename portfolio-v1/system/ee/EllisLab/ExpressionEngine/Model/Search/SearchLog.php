<?php
/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2018, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

namespace EllisLab\ExpressionEngine\Model\Search;

use EllisLab\ExpressionEngine\Service\Model\Model;

/**
 * Search Log Model
 */
class SearchLog extends Model {

	protected static $_primary_key = 'id';
	protected static $_table_name = 'search_log';

	protected static $_relationships = array(
		'Site' => array(
			'type' => 'BelongsTo'
		),
		'Member'	=> array(
			'type' => 'BelongsTo'
		)
	);

	protected $id;
	protected $site_id;
	protected $member_id;
	protected $screen_name;
	protected $ip_address;
	protected $search_date;
	protected $search_type;
	protected $search_terms;

}

// EOF
