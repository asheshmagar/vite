export type ScalarDevice = {
	desktop?: string|number,
	tablet?: string|number,
	mobile?: string|number,
}

export type Device = {
	desktop?: {
		value?: string|number,
		unit?: string,
	},
	tablet?: {
		value?: string|number,
		unit?: string,
	},
	mobile?: {
		value?: string|number,
		unit?: string,
	}
}

export type Typography = {
	family?: string,
	weight?: number,
	style?: string,
	transform?: string,
	decoration?: string,
	size?: Device,
	lineHeight?: Device,
	letterSpacing?: Device,
}

export type Border = {
	width?: Dimensions,
	style?: string,
	color?: {
		normal?: string,
		hover?: string,
	},
}

export type Dimensions = {
	top?: Device|string|number,
	right?: Device|string|number,
	bottom?: Device|string|number,
	left?: Device|string|number,
	unit?: string,
}

export type ResponsiveDimensions = {
	desktop?: Dimensions,
	tablet?: Dimensions,
	mobile?: Dimensions,
}

export type Background = {
	type: string,
	color?: string,
	image?: string,
	position?: Device,
	size?: Device,
	repeat?: Device,
	attachment?: Device,
	gradient?: string
}

export type Range = {
	value?: number,
	unit?: string,
}

export type ResponsiveRange = Device;

export type Color = {
	[key: string]: string,
} | string;

export type Common = ScalarDevice | string;
