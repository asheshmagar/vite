export type InputAttrsType = {
	responsive?: boolean;
	units?: string[];
	min?: number;
	max?: number;
	step?: number;
	// eslint-disable-next-line camelcase
	allow_reset?: boolean;
	marks?: boolean|{
		[ key: string ]: string;
	}[];
	input?: boolean;
	[key: string ]: any;
}

export type ParamsType = {
	label?: string;
	description?: string;
	choices?: {
		[ key: string ]: string;
	}|{
		[ key: string ]: string;
	}[];
	default?: any;
	inputAttrs: InputAttrsType;
	settings: any;
	type: string;
	link?: string;

	fonts?: {
		id: string;
		family: string;
		variants: string[];
		subsets: string[];
		category: string;
		version: string;
		lastModified: string;
		popularity: number;
		defSubset: string;
		defVariant: string;
		label: string;
		value: string;
	}[]
}

export type ControlType = {
	id: string;
	setting: any;
	params: ParamsType
}

export type ControlPropsType = {
	control: ControlType;
	customizer: any;
	[ key: string ]: any;
}
