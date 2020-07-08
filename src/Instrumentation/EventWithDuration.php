<?php
/**
 * Class EventWithDuration.
 *
 * @package AmpProject\AmpWP
 */

namespace AmpProject\AmpWP\Instrumentation;

/**
 * A server-timing event with a duration.
 *
 * @package AmpProject\AmpWP
 */
class EventWithDuration extends Event {

	/**
	 * Event duration.
	 *
	 * @var float
	 */
	protected $duration = 0.0;

	/**
	 * Set the event duration.
	 *
	 * @param float $duration Event duration.
	 */
	public function set_duration( $duration ) {
		$this->duration = $duration;
	}

	/**
	 * Get the event duration.
	 *
	 * @return float
	 */
	public function get_duration() {
		return $this->duration;
	}

	/**
	 * Get the server timing header string.
	 *
	 * @return string Server timing header string representing this event.
	 */
	public function get_header_string() {
		return sprintf(
			'%s;dur="%.1f"',
			parent::get_header_string(),
			$this->get_duration()
		);
	}
}
