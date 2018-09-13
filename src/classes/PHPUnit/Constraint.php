<?php
/**
 * Base constraint abstract class for WPAssure contraints to extend
 *
 * @package wpassure
 */

namespace WPAssure\PHPUnit;

/**
 * Constract abstract
 */
abstract class Constraint extends \PHPUnit\Framework\Constraint\Constraint {

	const ACTION_SEE     = 'see';
	const ACTION_DONTSEE = 'dontSee';

	/**
	 * The evaluation action.
	 *
	 * @access protected
	 * @var string
	 */
	protected $action = '';

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param string $action The evaluation action.
	 */
	public function __construct( $action ) {
		parent::__construct();
		$this->action = $action;
	}

	/**
	 * Return an instance of the actor class.
	 *
	 * @access protected
	 * @throws \WPAssure\Exception when the constrain is used with not an instance of the Actor class.
	 * @param \WPAssure\PHPUnit\Actor $other Incoming argument that used for this constrain.
	 * @return \WPAssure\PHPUnit\Actor An instance of the Actor class.
	 */
	protected function getActor( $other ) {
		if ( ! ( $other instanceof \WPAssure\PHPUnit\Actor ) ) {
			throw new \WPAssure\Exception( 'The constrain must be used only with an instance of the Actor class.' );
		}

		return $other;
	}

	/**
	 * Return the description of the current evaluation action.
	 *
	 * @access protected
	 * @return string A description of the current action.
	 */
	protected function getActionDescription() {
		switch ( $this->action ) {
			case self::ACTION_SEE:
				return ' sees';
			case self::ACTION_DONTSEE:
				return " doesn't see";
		}

		return '';
	}

	/**
	 * Return the description of the failure.
	 *
	 * @access protected
	 * @param mixed $other An instance of an actor.
	 * @return string A description of the failure.
	 */
	protected function failureDescription( $other ): string {
		$actor = $this->getActor( $other );
		return $actor->getActorName() . $this->getActionDescription() . $this->toString();
	}

}
