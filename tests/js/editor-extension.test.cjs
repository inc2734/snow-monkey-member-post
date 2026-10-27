const assert = require( 'node:assert/strict' );
const { readFileSync } = require( 'node:fs' );
const path = require( 'node:path' );
const { test } = require( 'node:test' );
const vm = require( 'node:vm' );

const bundle = readFileSync(
	path.join( __dirname, '../../dist/js/editor-extension.js' ),
	'utf8'
);

// Execute the production bundle against the WordPress APIs provided by the editor.
function loadEditor( { roles = [ 'administrator' ], registered = true } = {} ) {
	const filters = new Map();
	const element = ( type, props, ...children ) => ( {
		type,
		props: children.length ? { ...props, children } : props,
	} );
	const jsx = ( type, props ) => ( { type, props } );
	const runtime = { jsx, jsxs: jsx, Fragment: 'Fragment' };
	const wp = {
		components: {
			ToggleControl: 'ToggleControl',
			__experimentalToolsPanel: 'ToolsPanel',
			__experimentalToolsPanelItem: 'ToolsPanelItem',
		},
		blocks: { getBlockType: () => ( registered ? {} : undefined ) },
		blockEditor: { InspectorControls: 'InspectorControls' },
		compose: { createHigherOrderComponent: ( factory ) => factory },
		data: {
			useSelect: ( callback ) =>
				callback( () => ( { getCurrentUser: () => ( { roles } ) } ) ),
		},
		hooks: {
			addFilter: ( name, namespace, callback ) => {
				const callbacks = filters.get( name ) || [];
				callbacks.push( callback );
				filters.set( name, callbacks );
			},
			applyFilters: ( name, value, ...args ) =>
				( filters.get( name ) || [] ).reduce(
					( result, callback ) => callback( result, ...args ),
					value
				),
		},
		i18n: { __: ( text ) => text },
		element: { createElement: element, Fragment: 'Fragment' },
	};
	const window = {
		wp,
		React: { createElement: element, Fragment: 'Fragment' },
		ReactJSXRuntime: runtime,
	};
	vm.runInNewContext( bundle, { window, ReactJSXRuntime: runtime } );
	const BlockEdit = 'BlockEdit';
	const Edit = wp.hooks.applyFilters( 'editor.BlockEdit', BlockEdit );
	const updates = [];
	return {
		wp,
		updates,
		render: ( props = {} ) =>
			Edit( {
				name: 'core/paragraph',
				attributes: { smmpIsRestrected: false },
				setAttributes: ( value ) => updates.push( { ...value } ),
				...props,
			} ),
	};
}

function findElement( node, type ) {
	if ( ! node || typeof node !== 'object' ) {
		return undefined;
	}
	if ( node.type === type ) {
		return node;
	}
	const children = node.props?.children;
	for ( const child of Array.isArray( children ) ? children : [ children ] ) {
		const match = findElement( child, type );
		if ( match ) {
			return match;
		}
	}
	return undefined;
}

test( 'registers restriction attributes while preserving block attributes', () => {
	const { wp } = loadEditor();
	const settings = wp.hooks.applyFilters( 'blocks.registerBlockType', {
		attributes: { content: { type: 'string' } },
	} );
	assert.equal( settings.attributes.content.type, 'string' );
	assert.equal( settings.attributes.smmpIsRestrected.type, 'boolean' );
	assert.equal( settings.attributes.smmpIsRestrected.default, false );
} );

test( 'allowed roles can change and clear the member-only setting', () => {
	for ( const role of [
		'administrator',
		'editor',
		'author',
		'contributor',
	] ) {
		const editor = loadEditor( { roles: [ role ] } );
		const view = editor.render( {
			attributes: { smmpIsRestrected: true },
		} );
		const toggle = findElement( view, 'ToggleControl' );
		assert.equal( toggle.props.checked, true );
		toggle.props.onChange( false );
		findElement( view, 'ToolsPanelItem' ).props.onDeselect();
		assert.deepEqual( editor.updates, [
			{ smmpIsRestrected: false },
			{ smmpIsRestrected: undefined },
		] );
	}
} );

test( 'subscribers and unsupported blocks retain the original editor', () => {
	assert.equal(
		loadEditor( { roles: [ 'subscriber' ] } ).render().type,
		'BlockEdit'
	);
	const editor = loadEditor();
	assert.equal(
		editor.render( { name: 'other/paragraph' } ).type,
		'BlockEdit'
	);
	assert.equal( editor.render( { attributes: {} } ).type, 'BlockEdit' );
	assert.equal(
		loadEditor( { registered: false } ).render().type,
		'BlockEdit'
	);
} );

test( 'Snow Monkey blocks expose the restriction control', () => {
	assert.ok(
		findElement(
			loadEditor().render( { name: 'snow-monkey-blocks/box' } ),
			'ToggleControl'
		)
	);
} );

test( 'role and namespace filters still customize editor access', () => {
	const editor = loadEditor( { roles: [ 'subscriber' ] } );
	for ( const [ name, values ] of [
		[ 'allowedRoles', [ 'subscriber' ] ],
		[ 'allowedNameSpaces', [ 'custom' ] ],
	] ) {
		editor.wp.hooks.addFilter(
			`SnowMonkeyMemberPost.extension.${ name }`,
			'test',
			( defaults, extensionName ) => {
				assert.equal( extensionName, 'restrected' );
				return values;
			}
		);
	}
	assert.ok(
		findElement(
			editor.render( { name: 'custom/block' } ),
			'ToggleControl'
		)
	);
	assert.equal( editor.render().type, 'BlockEdit' );
} );
