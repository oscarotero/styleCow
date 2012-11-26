<?php
/**
 * Stylecow PHP library
 *
 * Vendor_prefixes plugin
 * Adds the vendor prefixes of all css properties, selectors and values in need.
 *
 * Example:
 * border-radius: 5px;
 *
 * PHP version 5.3
 *
 * @author Oscar Otero <http://oscarotero.com> <oom@oscarotero.com>
 * @license GNU Affero GPL version 3. http://www.gnu.org/licenses/agpl-3.0.html
 * @version 1.1.0 (2012)
 */

namespace Stylecow\Plugins;

use Stylecow\Parser;
use Stylecow\Css;
use Stylecow\Property;

class VendorPrefixes {
	const POSITION = 3;

	static $vendorPrefixesFunctions = array(
		array(
			'properties' => array(
				'animation',
				'animation-delay',
				'animation-direction',
				'animation-duration',
				'animation-fill-mode',
				'animation-iteration-count',
				'animation-name',
				'animation-play-state',
				'animation-timing-function',
				'backface-visibility',
				'transform',
				'transform-origin'
			),
			'fn' => array(
				'addPropertiesVendorPrefixes' => array('moz', 'webkit', 'o', 'ms')
			)
		),
		array(
			'properties' => array(
				'appearance',
				'background-clip',
				'background-origin',
				'box-sizing',
				'column-count',
				'column-gap',
				'column-rule',
				'column-rule-color',
				'column-rule-style',
				'column-rule-width',
				'column-span',
				'column-width',
				'columns',
				'opacity',
				'user-select'
			),
			'fn' => array(
				'addPropertiesVendorPrefixes' => array('moz', 'webkit')
			)
		),
		array(
			'properties' => array(
				'background-size',
				'border-bottom-image',
				'border-bottom-left-image',
				'border-bottom-right-image',
				'border-corner-image',
				'border-image',
				'border-left-image',
				'border-top-image',
				'border-top-left-image',
				'border-top-right-image',
				'border-radius',
				'border-right-image',
				'box-shadow',
				'transition',
				'transition-delay',
				'transition-duration',
				'transition-property',
				'transition-timing-function'
			),
			'fn' => array(
				'addPropertiesVendorPrefixes' => array('moz', 'webkit', 'o')
			)
		),
		array(
			'properties' => array(
				'border-after',
				'border-after-color',
				'border-after-style',
				'border-after-width',
				'border-before',
				'border-before-color',
				'border-before-style',
				'border-before-width',
			),
			'fn' => array(
				'addPropertiesVendorPrefixes' => array('webkit')
			)
		),
		array(
			'properties' => array(
				'filter',
				'grid-column',
				'grid-column-align',
				'grid-column-span',
				'grid-columns',
				'grid-layer',
				'grid-row',
				'grid-row-align',
				'grid-row-span',
				'grid-rows',
			),
			'fn' => array(
				'addPropertiesVendorPrefixes' => array('ms')
			)
		),
		array(
			'properties' => array('hyphens'),
			'fn' => array(
				'addPropertiesVendorPrefixes' => array('moz', 'webkit', 'epub', 'ms')
			)
		),
		array(
			'properties' => array('text-overflow'),
			'fn' => array(
				'addPropertiesVendorPrefixes' => array('o')
			)
		),
		array(
			'properties' => array('text-size-adjust'),
			'fn' => array(
				'addPropertiesVendorPrefixes' => array('moz', 'webkit', 'ms')
			)
		),
		array(
			'properties' => array('border-top-left-radius'),
			'fn' => array(
				'addPropertiesVendorPrefixes' => array('webkit'),
				'addRenamedProperty' => array('moz' => '-moz-border-radius-topleft')
			)
		),
		array(
			'properties' => array('border-top-right-radius'),
			'fn' => array(
				'addPropertiesVendorPrefixes' => array('webkit'),
				'addRenamedProperty' => array('moz' => '-moz-border-radius-topright')
			)
		),
		array(
			'properties' => array('border-bottom-left-radius'),
			'fn' => array(
				'addPropertiesVendorPrefixes' => array('webkit'),
				'addRenamedProperty' => array('moz' => '-moz-border-radius-bottomleft')
			)
		),
		array(
			'properties' => array('border-bottom-right-radius'),
			'fn' => array(
				'addPropertiesVendorPrefixes' => array('webkit'),
				'addRenamedProperty' => array('moz' => '-moz-border-radius-bottomright')
			)
		),
		array(
			'selector' => '::selection',
			'fn' => array(
				'addRenamedSelector' => array('moz' => '::-moz-selection')
			)
		),
		array(
			'selector' => '::input-placeholder',
			'fn' => array(
				'addRenamedSelector' => array(
					'moz' => ':-moz-placeholder',
					'webkit' => '::-webkit-input-placeholder',
					'ms' => '::-ms-input-placeholder'
				)
			)
		),
		array(
			'type' => '@keyframes',
			'fn' => array(
				'addRenamedType' => array(
					'moz' => '@-moz-keyframes',
					'webkit' => '@-webkit-keyframes',
					'ms' => '@-ms-keyframes',
					'o' => '@-o-keyframes'
				)
			)
		),
		array(
			'type' => '@document',
			'fn' => array(
				'addRenamedType' => array(
					'moz' => '@-moz-document'
				)
			)
		),
		array(
			'value' => 'inline-block',
			'fn' => array(
				'addValuesVendorPrefixes' => array('moz')
			)
		),
		array(
			'value' => 'calc',
			'fn' => array(
				'addValuesVendorPrefixes' => array('moz', 'webkit')
			)
		),
		array(
			'value' => 'linear-gradient',
			'fn' => array(
				'normalizeLinearGradient' => null,
				'webkitLinearGradient' => null,
				'oldLinearGradient' => array('moz', 'webkit', 'o')
			)
		)
	);



	/**
	 * Apply the plugin to Css object
	 *
	 * @param Stylecow\Css $css The css object
	 */
	static public function apply (Css $css) {
		foreach (VendorPrefixes::$vendorPrefixesFunctions as $fn) {
			$css->executeRecursive(function ($code) use ($fn) {
				if (isset($fn['type']) && ($fn['type'] === $code->selector->type)) {
					foreach ($fn['fn'] as $func => $args) {
						static::$func($code->selector, $args);
					}
				}

				if (isset($fn['selector']) && (strpos((string)$code->selector, $fn['selector']) !== false)) {
					foreach ($fn['fn'] as $func => $args) {
						static::$func($code->selector, $fn['selector'], $args);
					}
				}

				if (isset($fn['properties'])) {
					foreach ($code->getProperties() as $property) {
						if (in_array($property->name, $fn['properties'])) {
							foreach ($fn['fn'] as $func => $args) {
								static::$func($property, $args);
							}
						}
					}
				}

				if (isset($fn['value'])) {
					foreach ($code->getProperties() as $property) {
						if (strpos($property->value, $fn['value']) !== false) {
							foreach ($fn['fn'] as $func => $args) {
								static::$func($property, $fn['value'], $args);
							}
						}
					}
				}
			});
		}

		//Resolve and simplify the vendors
		$css->resolveVendors();
	}


	static public function addPropertiesVendorPrefixes ($property, $prefixes) {
		foreach ($prefixes as $prefix) {
			$name = "-$prefix-".$property->name;

			if (!$property->parent->hasProperty($name)) {
				$newProperty = clone $property;
				$newProperty->name = $name;
				$newProperty->vendor = $prefix;

				$property->parent->addProperty($newProperty, $property->getPositionInParent());
			}
		}
	}

	static public function addRenamedProperty ($property, $names) {
		foreach ($names as $vendor => $name) {
			if ((empty($property->vendor) || $property->vendor === $prefix) && !$property->parent->hasProperty($name)) {
				$newProperty = clone $property;
				$newProperty->name = $name;
				$newProperty->vendor = $vendor;

				$property->parent->addProperty($newProperty, $property->getPositionInParent());
			}
		}
	}

	static public function addRenamedSelector ($selector, $word, $names) {
		foreach ($names as $vendor => $name) {
			if (empty($selector->vendor) || $selector->vendor === $vendor) {
				$newCode = clone $selector->parent;
				$newCode->selector->set(str_replace($word, $name, $selector->get()));
				$newCode->selector->vendor = $vendor;

				$selector->parent->parent->addChild($newCode, $selector->parent->getPositionInParent());
			}
		}
	}

	static public function addRenamedType ($selector, $names) {
		foreach ($names as $vendor => $name) {
			$newCode = clone $selector->parent;
			$newCode->selector->type = $name;
			$newCode->selector->vendor = $vendor;

			$selector->parent->parent->addChild($newCode, $selector->parent->getPositionInParent());
		}
	}

	static public function addValuesVendorPrefixes ($property, $value, $prefixes) {
		foreach ($prefixes as $prefix) {
			if (empty($property->vendor) || $property->vendor === $prefix) {
				$newValue = preg_replace('/(^|[^\w-])('.preg_quote($value, '/').')([^\w]|$)/', "\\1-$prefix-$value\\3", $property->value);

				if (!$property->parent->hasProperty($property->name, $newValue)) {
					$newProperty = clone $property;
					$newProperty->value = $newValue;
					$newProperty->vendor = $prefix;

					$property->parent->addProperty($newProperty, $property->getPositionInParent());
				}
			}
		}
	}


	/**
	 * Fix the different syntaxis for the linear-gradient
	 *
	 * @param string  $value  The value of the property
	 *
	 * @return array  The linear-gradient code
	 */
	static public function normalizeLinearGradient ($property) {
		return $property->executeFunction('linear-gradient', function ($params) {
			switch ($params[0]) {
				case 'center top':
				case 'top':
					$params[0] = 'to bottom';
					break;

				case 'center bottom':
				case 'bottom':
					$params[0] = 'to top';
					break;

				case 'left top':
				case 'left':
					$params[0] = 'to right';
					break;

				case 'right top':
				case 'right':
					$params[0] = 'to left';
					break;

				default:
					return null;
			}

			return 'linear-gradient('.implode(', ', $params).')';
		});
	}


	static public function oldLinearGradient ($property, $value, $prefixes) {
		$newProperty = clone $property;

		$newProperty->executeFunction('linear-gradient', function ($params) {
			switch ($params[0]) {
				case 'to bottom':
					$params[0] = 'top';
					break;

				case 'to top':
					$params[0] = 'bottom';
					break;

				case 'to right':
					$params[0] = 'left';
					break;

				case 'to left':
					$params[0] = 'right';
					break;

				default:
					return null;
			}

			return 'linear-gradient('.implode(', ', $params).')';
		});

		static::addValuesVendorPrefixes($newProperty, $value, $prefixes);
	}



	/**
	 * Generate the old webkit syntax for the linear-gradient
	 *
	 * @param Stylecow\Code $code The code where the property is placed
	 *
	 * @return array  The linear-gradient code
	 */
	static public function webkitLinearGradient (Property $property) {
		$newProperty = clone $property;

		$newProperty->executeFunction('linear-gradient', function ($params) {
			$point = 'top';

			if (preg_match('/(top|bottom|left|right|deg)/', $params[0])) {
				$point = array_shift($params);
			}

			switch ($point) {
				case 'to bottom':
					$start = 'left top';
					$end = 'left bottom';
					break;

				case 'to top':
					$start = 'left bottom';
					$end = 'left top';
					break;

				case 'to right':
					$start = 'left top';
					$end = 'right top';
					break;

				case 'to left':
					$start = 'right top';
					$end = 'left top';
					break;

				default:
					if (preg_match('/^\ddeg$/', $point)) {
						$radius = intval($point);
					} else {
						$start = 'left top';
						$end = 'left bottom';
					}
			}

			$color_stops = array();
			$tk = count($params)-1;

			foreach ($params as $k => $param) {
				$param = Parser::explode(' ', trim($param));

				$color = $param[0];
				$stop = isset($param[1]) ? $param[1] : null;

			 	if ($k === 0) {
			 		$text = 'from';
				} else if ($k === $tk) {
					$text = 'to';
				} else {
					$text = 'color-stop';
				}

				if ($stop) {
					$color_stops[] = $text.'('.$stop.', '.$color.')';
				} else {
					$color_stops[] = $text.'('.$color.')';
				}
			}

			if (isset($radius)) {
				return '-webkit-gradient(linear, '.$radius.'deg, '.implode(', ', $color_stops).')';
			} else {
				return '-webkit-gradient(linear, '.$start.', '.$end.', '.implode(', ', $color_stops).')';
			}
		});

		if ($property->value !== $newProperty->value) {
			$newProperty->vendor = 'webkit';
			$property->parent->addProperty($newProperty, $property->getPositionInParent());
		}
	}
}
