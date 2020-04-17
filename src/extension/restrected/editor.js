import { getBlockType } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { useSelect } from '@wordpress/data';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';

import { icon } from '../icon';
import { isApplyExtensionToBlock, isApplyExtensionToUser } from '../helper';
import customAttributes from './attributes.json';

addFilter(
	'blocks.registerBlockType',
	'snow-monkey-member-post/restrected/attributes',
	( settings ) => {
		settings.attributes = {
			...settings.attributes,
			...customAttributes,
		};
		return settings;
	}
);

const addBlockControl = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		const { attributes, setAttributes, name } = props;

		const { smmpIsRestrected } = attributes;

		const currentUser = useSelect( ( select ) => {
			return select( 'core' ).getCurrentUser();
		}, [] );

		if ( 0 < Object.keys( currentUser ).length ) {
			const isApplyToUser = isApplyExtensionToUser(
				currentUser,
				'restrected'
			);
			if ( ! isApplyToUser ) {
				return <BlockEdit { ...props } />;
			}
		}

		const isApplyToBlock = isApplyExtensionToBlock( name, 'restrected' );
		if ( ! isApplyToBlock ) {
			return <BlockEdit { ...props } />;
		}

		if ( 'undefined' === typeof smmpIsRestrected ) {
			return <BlockEdit { ...props } />;
		}

		const blockType = getBlockType( name );
		if ( ! blockType ) {
			return <BlockEdit { ...props } />;
		}

		const panelClassName =
			0 < smmpIsRestrected
				? 'smmp-extension-panel smmp-extension-panel--enabled'
				: 'smmp-extension-panel';

		return (
			<>
				<BlockEdit { ...props } />

				<InspectorControls>
					<PanelBody
						title={ __(
							'Snow Monkey Member Post',
							'snow-monkey-member-post'
						) }
						initialOpen={ false }
						icon={ icon }
						className={ panelClassName }
					>
						<ToggleControl
							label={ __(
								'Show this block for members only',
								'snow-monkey-member-post'
							) }
							checked={ !! smmpIsRestrected }
							onChange={ ( value ) =>
								setAttributes( { smmpIsRestrected: value } )
							}
						/>
					</PanelBody>
				</InspectorControls>
			</>
		);
	};
}, 'withSnowMonkeyMemberPostRestrectedBlockEdit' );

addFilter(
	'editor.BlockEdit',
	'snow-monkey-member-post/restrected/block-edit',
	addBlockControl
);
