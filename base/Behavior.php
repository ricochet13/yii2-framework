<?php
/**
 * Behavior class file.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2012 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\base;

/**
 * Behavior is the base class for all behavior classes.
 *
 * A behavior can be used to enhance the functionality of an existing component.
 * In particular, it can "inject" its own methods and properties into the component
 * and make them directly accessible via the component.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Behavior extends Object
{
	/**
	 * @var Component the owner component
	 */
	private $_owner;

	/**
	 * Declares event handlers for the [[owner]]'s events.
	 *
	 * Child classes may override this method to declare what PHP callbacks should
	 * be attached to the events of the [[owner]] component.
	 *
	 * The callbacks will be attached to the [[owner]]'s events when the behavior is
	 * attached to the owner; and they will be detached from the events when
	 * the behavior is detached from the component.
	 *
	 * The callbacks can be any of the followings:
	 *
	 * - method in this behavior: `'handleOnClick'`, equivalent to `array($this, 'handleOnClick')`
	 * - object method: `array($object, 'handleOnClick')`
	 * - static method: `array('Page', 'handleOnClick')`
	 * - anonymous function: `function($event) { ... }`
	 *
	 * The following is an example:
	 *
	 * ~~~
	 * array(
	 *	 'onBeforeValidate' => 'myBeforeValidate',
	 *	 'onAfterValidate' => 'myAfterValidate',
	 * )
	 * ~~~
	 *
	 * @return array events (keys) and the corresponding behavior method names (values).
	 */
	public function events()
	{
		return array();
	}

	/**
	 * Attaches the behavior object to the component.
	 * The default implementation will set the [[owner]] property
	 * and attach event handlers as declared in [[events]].
	 * Make sure you call the parent implementation if you override this method.
	 * @param Component $owner the component that this behavior is to be attached to.
	 */
	public function attach($owner)
	{
		$this->_owner = $owner;
		foreach ($this->events() as $event => $handler) {
			$owner->attachEventHandler($event, is_string($handler) ? array($this, $handler) : $handler);
		}
	}

	/**
	 * Detaches the behavior object from the component.
	 * The default implementation will unset the [[owner]] property
	 * and detach event handlers declared in [[events]].
	 * Make sure you call the parent implementation if you override this method.
	 * @param Component $owner the component that this behavior is to be detached from.
	 */
	public function detach($owner)
	{
		foreach ($this->events() as $event => $handler) {
			$owner->detachEventHandler($event, is_string($handler) ? array($this, $handler) : $handler);
		}
		$this->_owner = null;
	}

	/**
	 * Returns the owner component that this behavior is attached to.
	 * @return Component the owner component that this behavior is attached to.
	 */
	public function getOwner()
	{
		return $this->_owner;
	}
}