<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed.');

/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2018, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

/**
 * ExpressionEngine Stop Words
 *
 * This file contains an array of words that the search functions in EE will
 * ignore in order to a) reduce load, and b) generate better results.
 */

ee()->load->library('logger');
ee()->logger->deprecated('3.4.0', 'ee()->config->loadFile("stopwords") to load this config file', TRUE, 604800);

$ignore = ee()->config->loadFile('stopwords');

// EOF
