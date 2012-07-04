<?php
/**
 * Stylecow PHP library
 *
 * Css class
 *
 * PHP version 5.3
 *
 * @author Oscar Otero <http://oscarotero.com> <oom@oscarotero.com>
 * @license GNU Affero GPL version 3. http://www.gnu.org/licenses/agpl-3.0.html
 * @version 1.0.0 (2012)
 */

namespace Stylecow;

class Css extends \ArrayObject {
	public $parent;
	public $selector = array();
	public $properties = array();


	public function __construct (array $selectors = null, $type = null) {
		$this->selector = new Selector($type);
		$this->selector->setParent($this);

		if ($selectors) {
			foreach ($selectors as $selector) {
				$this->selector->add($selector);
			}
		}
	}


	public function setParent (Css $parent) {
		$this->parent = $parent;
	}

	public function getParentPosition () {
		if (isset($this->parent)) {
			foreach ($this->parent as $key => $child) {
				if ($child === $this) {
					return $key;
				}
			}
		}

		return false;
	}

	public function removeFromParent () {
		if (($key = $this->getParentPosition()) !== false) {
			unset($this->parent[$key]);
			$this->parent = null;
		}
	}

	public function addChild (Css $css, $position = null) {
		$css->setParent($this);

		if (!isset($position)) {
			return $this[] = $css;
		}

		$children = $this->getArrayCopy();
		array_splice($children, $position, 0, array($css));
		$this->exchangeArray($children);

		return $css;
	}

	public function getChildren ($filter = null) {
		if (!isset($filter)) {
			return $this->getArrayCopy();
		}

		$children = array();

		foreach ($this as $child) {
			if ($child->selector->match($filter)) {
				$children[] = $child;
			}
		}

		return $children;
	}


	public function addProperty (Property $property, $position = null) {
		$property->setParent($this);

		if (!isset($position)) {
			return $this->properties[] = $property;
		}

		array_splice($this->properties, $position, 0, array($property));

		return $property;
	}


	public function getProperties ($filter = null) {
		if (!isset($filter)) {
			return $this->properties ? $this->properties : array();
		}

		$properties = array();

		if ($this->properties) {
			foreach ($this->properties as $property) {
				if ($property->is($filter)) {
					$properties[] = $property;
				}
			}
		}

		return $properties;
	}


	public function executeRecursive ($callback, $contextData = null) {
		$callback($this, $contextData);

		foreach ($this as $child) {
			$childData = $contextData;

			$child->executeRecursive($callback, $childData);
		}
	}


	public function toString ($indent = 0) {
		$indentation = str_repeat("\t", $indent);

		$selector = (string)$this->selector;
		$properties = '';

		if (isset($this->properties)) {
			$indProp = $selector ? $indentation."\t" : $indentation;

			foreach ($this->properties as $property) {
				$properties .= $indProp.(string)$property.";\n";
			}
		}

		if (count($this)) {
			$indent += $selector ? 1 : 0;

			foreach ($this as $child) {
				$properties .= "\n".$child->toString($indent);
			}
		}

		if ($properties && $selector) {
			return $indentation.$selector." {\n".$properties.$indentation."}\n";
		}

		if ($properties) {
			return $properties;
		}

		if ($selector) {
			return $indentation.$selector.";\n";
		}
	}


	public function toArray () {
		$array = array(
			'selector' => $this->selector->get(),
			'properties' => array()
		);

		foreach ($this->properties as $property) {
			$array['properties'][$property->name] = $property->value;
		}

		foreach ($this as $child) {
			$array[] = $child->toArray();
		}

		return $array;
	}
}