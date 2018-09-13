<?php

namespace WPAssure\PHPUnit\Constraints;

use WPAssure\EnvironmentFactory;

class NewDatabaseEntry extends \WPAssure\PHPUnit\Constraint {

	use Traits\SeeableAction,
		Traits\StringOrPattern;

	/**
	 * Old newest DB entry ID
	 *
	 * @access private
	 * @var int
	 */
	private $_old_id;

	/**
	 * Table to check
	 *
	 * @access private
	 * @var int
	 */
	private $_table;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param string $action The evaluation action. Valid options are "see" or "dontSee".
	 * @param string $table Table to check for entries
	 * @param int    $old_id Old newest DB entry ID
	 */
	public function __construct( $action, $table, $old_id ) {
		parent::__construct( $this->_verifyAction( $action ) );

		$this->_old_id = $old_id;
		$this->_table = $table;
	}

	/**
	 * Evaluate if there is a newer DB entry
	 *
	 * @access protected
	 * @param \WPAssure\PHPUnit\Actor $other The actor instance.
	 * @return boolean TRUE if the constrain is met, otherwise FALSE.
	 */
	protected function matches( $other ): bool {
		$mysql = EnvironmentFactory::get()->getMySQLClient();

		$query = 'SELECT ID FROM ' . $mysql->getTablePrefix() . $this->_table . ' WHERE `ID` > "' . (int) $this->_old_id . '" ORDER BY `ID` DESC LIMIT 1';

		$result = $mysql->query( $query );

		$new_entry = ( $result->num_rows >= 1 );

		return ( $new_entry && self::ACTION_SEE === $this->_action ) || ( ! $new_entry && self::ACTION_DONTSEE === $this->_action );
	}

	/**
	 * Return description of the failure.
	 *
	 * @access public
	 * @return string The description text.
	 */
	public function toString(): string {
		return sprintf( 'Entry with ID newer than %d in %s table.', $this->_old_id, $this->_table );
	}

}