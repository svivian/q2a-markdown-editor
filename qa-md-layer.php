<?php

class qa_html_theme_layer extends qa_html_theme_base
{

	function head_custom()
	{
		parent::head_custom();

		$hidecss = qa_opt('markdown_editor_css') === '1';
		$wmd_buttons = QA_HTML_THEME_LAYER_URLTOROOT . 'pagedown/wmd-buttons.png';

		$this->output_raw(
			"<style>\n" .
			".wmd-button > span { background-image: url('$wmd_buttons') }\n"
		);

		if ( !$hidecss )
		{
			$this->output_raw(
				".wmd-button-bar { width: 100%; padding: 5px 0; }\n" .
				".wmd-input { width: 598px; height: 250px; margin: 0 0 10px; padding: 2px; border: 1px solid #ccc; }\n" .
				".wmd-preview { width: 584px; margin: 10px 0; padding: 8px; border: 2px dashed #ccc; }\n" .
				".wmd-preview pre, .qa-a-item-content pre { width: 100%; max-height: 400px; overflow: auto; }\n" .
				".wmd-button-row { position: relative; margin: 0; padding: 0; height: 20px; }\n" .
				".wmd-spacer { width: 1px; height: 20px; margin-left: 14px; position: absolute; background-color: Silver; display: inline-block; list-style: none; }\n" .
				".wmd-button { width: 20px; height: 20px; padding-left: 2px; padding-right: 3px; position: absolute; display: inline-block; list-style: none; cursor: pointer; }\n" .
				".wmd-button > span { background-repeat: no-repeat; background-position: 0px 0px; width: 20px; height: 20px; display: inline-block; }\n" .
				".wmd-spacer1 { left: 50px; }\n" .
				".wmd-spacer2 { left: 175px; }\n" .
				".wmd-spacer3 { left: 300px; }\n" .
				".wmd-prompt-background { background-color: #000; }\n" .
				".wmd-prompt-dialog { border: 1px solid #999; background-color: #f5f5f5; }\n" .
				".wmd-prompt-dialog > div { font-size: 0.8em; }\n" .
				".wmd-prompt-dialog > form > input[type='text'] { border: 1px solid #999; color: black; }\n" .
				".wmd-prompt-dialog > form > input[type='button'] { border: 1px solid #888; font-size: 11px; font-weight: bold; }\n"
			);
		}

		$this->output_raw("</style>\n\n");
	}

}
