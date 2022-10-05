<?php
/**
 * Settings.
 *
 * @package Vite
 */

$panel_settings = [
	[
		'name'     => 'vite_panel',
		'type'     => 'panel',
		'title'    => esc_html__( 'Vite Panel', 'vite' ),
		'priority' => 10,
	],
];

$section_settings = [
	[
		'name'     => 'vite_section',
		'type'     => 'section',
		'title'    => esc_html__( 'Vite Section', 'vite' ),
		'panel'    => 'vite_panel',
		'priority' => 10,
	],
];

$control_settings = [
	[
		'name'     => 'vite_control',
		'type'     => 'control',
		'control'  => 'vite-dimensions',
		'section'  => 'vite_section',
		'settings' => 'vite_control',
		'label'    => esc_html__( 'Dimensions', 'vite' ),
	],
	[
		'name'     => 'vite_control_1',
		'type'     => 'control',
		'control'  => 'vite-editor',
		'section'  => 'vite_section',
		'settings' => 'vite_control',
		'label'    => esc_html__( 'Editor', 'vite' ),
	],
	[
		'name'     => 'vite_control_2',
		'type'     => 'control',
		'control'  => 'vite-slider',
		'section'  => 'vite_section',
		'settings' => 'vite_control',
		'label'    => esc_html__( 'Slider', 'vite' ),
	],
	[
		'name'     => 'vite_control_3',
		'type'     => 'control',
		'control'  => 'vite-color',
		'section'  => 'vite_section',
		'settings' => 'vite_control',
		'label'    => esc_html__( 'Color', 'vite' ),
	],
	[
		'name'     => 'vite_control_4',
		'type'     => 'control',
		'control'  => 'vite-typography',
		'section'  => 'vite_section',
		'settings' => 'vite_control',
		'label'    => esc_html__( 'Typography', 'vite' ),
	],
];

$settings = apply_filters( 'vite_customizer_control_settings', array_merge( $panel_settings, $section_settings, $control_settings ) );

vite( 'customizer' )->add_settings( $settings );


