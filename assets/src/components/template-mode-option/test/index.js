
/**
 * External dependencies
 */
import { act } from 'react-dom/test-utils';
import { create } from 'react-test-renderer';

/**
 * WordPress dependencies
 */
import { render } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { TemplateModeOption, getId } from '..';
import { READER, STANDARD, TRANSITIONAL } from '../../../common/constants';
import { OptionsContextProvider } from '../../options-context-provider';

jest.mock( '../../../components/options-context-provider' );

let container;

describe( 'TemplateModeOption', () => {
	beforeEach( () => {
		container = document.createElement( 'div' );
		document.body.appendChild( container );
	} );

	afterEach( () => {
		document.body.removeChild( container );
		container = null;
	} );

	it( 'matches snapshot', () => {
		let wrapper = create(
			<OptionsContextProvider>
				<TemplateModeOption details="Component details" mode={ STANDARD }>
					<div>
						{ 'Component children' }
					</div>
				</TemplateModeOption>
			</OptionsContextProvider>,
		);
		expect( wrapper.toJSON() ).toMatchSnapshot();

		wrapper = create(
			<OptionsContextProvider>
				<TemplateModeOption details="Component details" mode={ READER } previouslySelected={ true } />
			</OptionsContextProvider>,
		);
		expect( wrapper.toJSON() ).toMatchSnapshot();
	} );

	it( 'is open by default if is current mode', () => {
		// Reader is the default in mock options context provider.
		act( () => {
			render(
				<OptionsContextProvider>
					<TemplateModeOption mode={ READER }>
						<div id="reader-mode-children">
							{ 'children' }
						</div>
					</TemplateModeOption>
					<TemplateModeOption mode={ STANDARD }>
						<div id="standard-mode-children">
							{ 'children' }
						</div>
					</TemplateModeOption>
				</OptionsContextProvider>,
				container,
			);
		} );

		expect( container.querySelector( '#reader-mode-children' ) ).not.toBeNull();
		expect( container.querySelector( '#standard-mode-children' ) ).toBeNull();
	} );

	it( 'can be toggled', () => {
		act( () => {
			render(
				<OptionsContextProvider>
					<TemplateModeOption mode={ TRANSITIONAL }>
						<div id="transitional-mode-children">
							{ 'children' }
						</div>
					</TemplateModeOption>
				</OptionsContextProvider>,
				container,
			);
		} );

		expect( container.querySelector( '#transitional-mode-children' ) ).toBeNull();

		act( () => {
			document.querySelector( `#template-mode-transitional-container .components-panel__body-toggle` ).dispatchEvent( new global.MouseEvent( 'click', { bubbles: true } ) );
		} );

		expect( container.querySelector( '#transitional-mode-children' ) ).not.toBeNull();
	} );
} );
