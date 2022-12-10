/**
 * Gutenberg block logic
 */
import './editor';

const $ = window.jQuery;
const { registerBlockType } = wp.blocks;
const NumberControl = wp.components.__experimentalNumberControl;
const { useBlockProps, RichText, InspectorControls } = wp.blockEditor;
const { PanelBody, Panel, PanelRow, Button, SelectControl } = wp.components;
const _nonce = window.sdk_gutenberggpt.executeGPTPrompt_nonce;

registerBlockType( 'sdk/gpt-prompt', {
	title: 'ChatGPT Prompt',
	icon: 'editor-quote',
	description: '',
	category: 'text',
	attributes: {
		prompt: {
			type: 'string',
			source: 'html',
			selector: '.prompt-text',
			default: '',
		},
		response: {
			type: 'string',
			source: 'html',
			selector: '.response-text',
			default: 'Please input a prompt.',
		},
		maxTokens: { type: 'integer', default: 2048 },
		temperature: { type: 'float', default: 1 },
		model: { type: 'string', default: 'text-davinci-003' },
	},

	edit: ( { clientId, className, attributes, setAttributes } ) => {
		const blockProps = useBlockProps();
		const { prompt, response, maxTokens, temperature, model } = attributes;

		return (
			<div { ...blockProps }>
				<InspectorControls>
					<Panel>
						<PanelBody title={ 'ChatGPT' }>
							<PanelRow>
								<p>
									Write a prompt for ChatGPT and let the model generate output for your. For example:
									<em>&quot;Write a blog post about creating a WordPress plugin which integrates ChatGPT into the Gutenberg editor.&quot;</em>
								</p>
							</PanelRow>
							<NumberControl
								label="Maximum length"
								step={ 1 }
								min={ 1 }
								max={ 2048 }
								value={ maxTokens }
								onChange={ ( newMaxTokens ) => setAttributes( { maxTokens: newMaxTokens } ) }
							/>
							<NumberControl
								label="Temperature"
								step={ 0.1 }
								min={ 0 }
								max={ 1 }
								value={ temperature }
								onChange={ ( newTemperature ) => setAttributes( { temperature: newTemperature } ) }
							/>
							<SelectControl
								label="GPT Model"
								value={ model }
								options={ [
									{ label: 'text-similarity-babbage-001', value: 'text-similarity-babbage-001' },
									{ label: 'text-davinci-003', value: 'text-davinci-003' },
									{ label: 'text-davinci-001', value: 'text-davinci-001' },
									{ label: 'text-ada-001', value: 'text-ada-001' },
									{ label: 'text-similarity-ada-001', value: 'text-similarity-ada-001' },
									{ label: 'text-davinci-insert-002', value: 'text-davinci-insert-002' },
									{ label: 'text-davinci-002', value: 'text-davinci-002' },
									{ label: 'text-search-ada-query-001', value: 'text-search-ada-query-001' },
									{ label: 'text-curie-001', value: 'text-curie-001' },
									{ label: 'text-davinci-edit-001', value: 'text-davinci-edit-001' },
									{ label: 'text-search-ada-doc-001', value: 'text-search-ada-doc-001' },
									{ label: 'text-babbage-001', value: 'text-babbage-001' },
									{ label: 'text-similarity-curie-001', value: 'text-similarity-curie-001' },
									{ label: 'text-search-davinci-query-001', value: 'text-search-davinci-query-001' },
									{ label: 'text-davinci-insert-001', value: 'text-davinci-insert-001' },
									{ label: 'text-search-babbage-doc-001', value: 'text-search-babbage-doc-001' },
									{ label: 'text-search-curie-doc-001', value: 'text-search-curie-doc-001' },
									{ label: 'text-search-curie-query-001', value: 'text-search-curie-query-001' },
									{ label: 'text-search-davinci-doc-001', value: 'text-search-davinci-doc-001' },
									{ label: 'text-search-babbage-query-001', value: 'text-search-babbage-query-001' },
									{ label: 'text-similarity-davinci-001', value: 'text-similarity-davinci-001' },
									{ label: 'text-babbage:001', value: 'text-babbage:001' },
								] }
								onChange={ ( newModel ) => setAttributes( { model: newModel } ) }
							/>
							<Button
								variant="primary"
								className="js--executeProm"
								onClick={ ( ) => {
									$( '.sdk-chatgptprompt' ).addClass( 'loading' );
									setAttributes( { response: 'Executing prompt...' } );

									const post = wp.ajax.send( {
										method: 'POST',
										data: { action: 'executeGPTPrompt', prompt, temperature, maxTokens, model, _nonce },
									} );

									post.done( function( response ) {
										let message = '';
										if ( response && typeof response === 'object' ) {
											message = typeof response.message === 'string' ? response.message : '';
										}

										wp.data.dispatch( 'core/block-editor' ).replaceBlock(
											clientId,
											wp.blocks.createBlock( 'core/paragraph', { content: `${ message }` } )
										);
									} );

									post.fail( function( response ) {
										let message = '';
										if ( response && typeof response === 'object' ) {
											message = typeof response.message === 'string' ? response.message : '';
										}

										setAttributes( { response: `An error has occurred. The server responded with: "${ message }". Please try again.` } );
									} );

									post.always( () => $( '.sdk-chatgptprompt' ).removeClass( 'loading' ) );
								} }
							>Prompt uitvoeren</Button>
						</PanelBody>
					</Panel>
				</InspectorControls>
				<div className={ `${ className } sdk-chatgptprompt` }>
					<RichText
						value={ prompt }
						tagName="p"
						className="prompt-text"
						onChange={ newPrompt => {
							setAttributes( { prompt: newPrompt } );
						} }
					/>
					<p className="response-text"><em>{ response }</em></p>
				</div>
			</div>
		);
	},

	save: () => null,
} );
