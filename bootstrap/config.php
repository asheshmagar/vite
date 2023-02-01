<?php
/**
 * Configs.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

return [
	'core'                 => [
		'concrete' => 'Vite\Core',
	],
	'builder-elements'     => [
		'concrete' => 'Vite\Elements\BuilderElements',
	],
	'entry-elements'       => [
		'concrete' => 'Vite\Elements\EntryElements',
	],
	'breadcrumbs'          => [
		'concrete' => 'Vite\Breadcrumbs',
	],
	'comments'             => [
		'concrete'  => 'Vite\Comments\Comments',
		'arguments' => [
			'Vite\Comments\WalkerComment',
		],
	],
	'nav-menu'             => [
		'concrete'  => 'Vite\NavMenu\NavMenu',
		'arguments' => [
			'Vite\NavMenu\WalkerNavMenu',
			'Vite\NavMenu\WalkerPage',
		],
	],
	'icon'                 => [
		'concrete' => 'Vite\Icon',
	],
	'widgets'              => [
		'concrete' => 'Vite\Widgets',
	],
	'page-header-elements' => [
		'concrete' => 'Vite\Elements\PageHeaderElements',
	],
	'performance'          => [
		'concrete'  => 'Vite\Performance',
		'arguments' => [
			'Vite\WebFontLoader',
		],
	],
	'customizer'           => [
		'concrete'  => 'Vite\Customizer\Customizer',
		'arguments' => [
			'Vite\DynamicCSS',
			'Vite\Customizer\Sanitize',
		],
	],
	'template-hooks'       => [
		'concrete' => 'Vite\TemplateHooks',
	],
	'scripts-styles'       => [
		'concrete' => 'Vite\ScriptsStyles',
	],
	'compatibility'        => [
		'concrete' => 'Vite\Compatibility\Compatibility',
	],
	'schema'               => [
		'concrete' => 'Vite\Schema',
	],
	'theme'                => [
		'concrete' => 'Vite\Vite',
	],
];
