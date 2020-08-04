<?php
/**
 * Reference site export active plugins.
 *
 * @package AmpProject\AmpWP
 */

namespace AmpProject\AmpWP\Tests\Cli\Export;

use AmpProject\AmpWP\Tests\Cli\ExportStep;

final class ExportActivePlugins implements ExportStep {

	/**
	 * List of plugin slugs to exclude.
	 *
	 * @var string[]
	 */
	const EXCLUDED_PLUGINS = [
		'airplane-mode',
		'amp',
		'query-monitor',
		'redis-cache',
		'wp-rocket',
	];

	/**
	 * Process the export step.
	 *
	 * @param ExportResult $export_result Export result to adapt.
	 *
	 * @return ExportResult Adapted export result.
	 */
	public function process( ExportResult $export_result ) {
		$active_plugins = $this->get_active_plugins();

		$active_plugins = array_filter(
			$active_plugins,
			[ $this, 'skip_excluded_plugins' ]
		);

		foreach ( $active_plugins as $plugin ) {
			$export_result->add_step( 'activate_plugin', compact( 'plugin' ) );
		}

		return $export_result;
	}

	/**
	 * Get the list of currently active plugins.
	 *
	 * @return string[] Array of currently active plugins.
	 */
	private function get_active_plugins() {
		return array_map( static function ( $plugin ) {
			$filename = basename( dirname( $plugin ) );
			return preg_replace( '/\.php$/', '', $filename );
		}, get_option( 'active_plugins', [] ) );
	}

	/**
	 * Skip the plugins that are marked as excluded.
	 *
	 * @param string $active_plugin Active plugin to check.
	 * @return bool Whether to skip the active plugin.
	 */
	private function skip_excluded_plugins( $active_plugin ) {
		return ! in_array( $active_plugin, self::EXCLUDED_PLUGINS, true );
	}
}