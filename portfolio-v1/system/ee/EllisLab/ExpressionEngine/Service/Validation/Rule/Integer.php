<?php
/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2018, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

namespace EllisLab\ExpressionEngine\Service\Validation\Rule;

use EllisLab\ExpressionEngine\Service\Validation\ValidationRule;

/**
 * Integer Validation Rule
 */
class Integer extends ValidationRule {

	public function validate($key, $value)
	{
		return (bool) preg_match('/^[\-+]?[0-9]+$/', $value);
	}

	public function getLanguageKey()
	{
		return 'integer';
	}

}

// EOF
