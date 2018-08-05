<?php

namespace Modules\Authorization\Orm;

use Modules\Authorization\Models\Route;
use Modules\Authorization\RouteParamsList\RplCollector;
use Phact\Main\Phact;

class RouteParams implements \Iterator, \ArrayAccess, \Countable
{
	use RplCollector;

	public $route = null;

	protected $params = [];
	protected $isArray = false;

	private $_pos = null;

	public function __construct(Route &$route, $value) {
		$this->route = $route;
		$params = [];

		$pnames = $this->route->getParamsNames();

		if ($pnames) {
			$paramsSet = $this->val2params($pnames, $value);
			foreach ($pnames as $k => $v) {
				$params[$v] = is_string($paramsSet[$v]) && strstr($paramsSet[$v], '|')
					? explode('|', $paramsSet[$v])
					: (array)$paramsSet[$v];
			}
		}

		$this->params = $params ?: $value;
		$this->isArray = (bool)$params;
	}

	public function val2params($pnames, $value) {
		return is_array($value)
			? array_replace(array_combine(
				$pnames,
				array_fill(0, sizeof($pnames), '')
			), $value)
			: array_combine($pnames, array_replace(
				array_fill(0, sizeof($pnames), ''),
				explode('/', $value)
			));
	}

	public function isArray() {
		return $this->isArray;
	}

	public function getChildParams($param) {
		$params = array_keys($this->params);
		$id = array_search($param, $params);

		return array_slice($params, ++$id);
	}

	/**
	 * Relational operator >=
	 * @return bool
	 */
	public function ge(RouteParams $params) {
		return $this->eq($params) || $this->gt($params);
	}

	/**
	 * Relational operator ==
	 * @return bool
	 */
	public function eq(RouteParams $params) {
		return $this->params == $params->params;
	}

	/**
	 * Relational operator >
	 * @return bool
	 */
	public function gt(RouteParams $params) {
		if ($this->isArray) {
			foreach ($this->params as $key => $value) {
				if (isset($params[$key])
					&& $value !== ['']
					&& !in_array($params[$key][0], $value)
				) {
					return false;
				}

			}
		}

		return true;
	}

	public function toList() {
		$list = [];

		if ($this->isArray()) {
			$rpls = $this->getRplClasses();

			foreach ($this->params as $pname => $activeVaules) {
				$list[$pname] = isset($rpls[$pname])
					? $rpls[$pname]::o()->filter($this)
					: implode('|', (array)$activeVaules);
			}
		}

		return $list;
	}

//-----------------------------------------------------
// ### MAGIC ###
//-----------------------------------------------------

	public function __toString()
	{
		$value = $this->params;

		if (is_array($value)) {
			$params = $value;

			foreach ($params as $k => $v) {
				$params[$k] = implode('|', $v);
			}

			$value = implode('/', $params);
		}

		return (string)$value;
	}

	public function __get($name) {
		return $this->offsetGet($name);
	}

	public function __set($name, $value) {
		$this->offsetSet($name, $value);
	}

	public function __isset($name) {
		return $this->offsetExists($name);
	}

	public function __unset($name) {
		$this->offsetUnset($name);
	}

//-----------------------------------------------------
// ### ArrayAccess ###
//-----------------------------------------------------

	public function offsetGet($name) {
		return isset($this->params[$name]) ? $this->params[$name] : null;
	}

	public function offsetSet($name, $value) {
		$this->params[$name] = $value;
	}

	public function offsetExists($name) {
		return isset($this->params[$name]);
	}

	public function offsetUnset($name) {
		unset($this->params[$name]);
	}


//-----------------------------------------------------
// ### Iterator interface ###
//-----------------------------------------------------
	public function current() {
		return current($this->params);
	}

	public function key() {
		return key($this->params);
	}

	public function next() {
		next($this->params);
		$this->_pos = key($this->params);
	}

	public function rewind() {
		reset($this->params);
		$this->_pos = key($this->params);
	}

	public function valid() {
		return isset($this->params[$this->_pos]);
	}

//-----------------------------------------------------
// ### Countable interface ###
//-----------------------------------------------------
	public function count() {
		return sizeof($this->params);
	}
}
