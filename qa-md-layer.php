<?php

class qa_html_theme_layer extends qa_html_theme_base
{
	private $cssopt = 'markdown_editor_css';
	private $hljsopt = 'markdown_highlightjs';

	function head_custom()
	{
		parent::head_custom();

		$tmpl = array( 'ask', 'question' );
		if ( !in_array($this->template, $tmpl) )
			return;

		$hidecss = qa_opt($this->cssopt) === '1';
		$usehljs = qa_opt($this->hljsopt) === '1';
		$wmd_buttons = QA_HTML_THEME_LAYER_URLTOROOT . 'pagedown/wmd-buttons.png';

		$this->output_raw(
			"<style>\n" .
			".wmd-button > span { background-image: url('$wmd_buttons') }\n"
		);

		// display CSS for Markdown Editor
		if ( !$hidecss )
		{
			$this->output_raw(
				".wmd-button-bar { width: 100%; padding: 5px 0 }\n" .
				".wmd-input { width: 598px; height: 250px; margin: 0 0 10px; padding: 2px; border: 1px solid #ccc }\n" .
				".wmd-preview { width: 584px; margin: 10px 0; padding: 8px; border: 2px dashed #ccc }\n" .
				".wmd-preview pre, .qa-a-item-content pre { width: 100%; max-height: 400px; overflow: auto }\n" .
				".wmd-button-row { position: relative; margin: 0; padding: 0; height: 20px }\n" .
				".wmd-spacer { width: 1px; height: 20px; margin-left: 14px; position: absolute; background-color: Silver; display: inline-block; list-style: none }\n" .
				".wmd-button { width: 20px; height: 20px; padding-left: 2px; padding-right: 3px; position: absolute; display: inline-block; list-style: none; cursor: pointer }\n" .
				".wmd-button > span { background-repeat: no-repeat; background-position: 0px 0px; width: 20px; height: 20px; display: inline-block }\n" .
				".wmd-spacer1 { left: 50px }\n" .
				".wmd-spacer2 { left: 175px }\n" .
				".wmd-spacer3 { left: 300px }\n" .
				".wmd-prompt-background { background-color: #000 }\n" .
				".wmd-prompt-dialog { border: 1px solid #999; background-color: #f5f5f5 }\n" .
				".wmd-prompt-dialog > div { font-size: 0.8em }\n" .
				".wmd-prompt-dialog > form > input[type='text'] { border: 1px solid #999; color: black }\n" .
				".wmd-prompt-dialog > form > input[type='button'] { border: 1px solid #888; font-size: 11px; font-weight: bold }\n"
			);
		}

		// display CSS for HighlightJS
		if ( $usehljs && !$hidecss )
		{
			$this->output_raw(
				"pre code { display:block; padding:.5em; background:#f6f6f6 }\n" .
				"pre code,pre .ruby .subst,pre .tag .title,pre .lisp .title,pre .nginx .title { color:black }\n" .
				"pre .string,pre .title,pre .constant,pre .parent,pre .tag .value,pre .rules .value,pre .rules .value .number,pre .preprocessor,pre .ruby .symbol,pre .ruby .symbol .string,pre .ruby .symbol .keyword,pre .ruby .symbol .keymethods,pre .instancevar,pre .aggregate,pre .template_tag,pre .django .variable,pre .smalltalk .class,pre .addition,pre .flow,pre .stream,pre .bash .variable,pre .apache .tag,pre .apache .cbracket,pre .tex .command,pre .tex .special,pre .erlang_repl .function_or_atom,pre .markdown .header { color:#800 }\n" .
				"pre .comment,pre .annotation,pre .template_comment,pre .diff .header,pre .chunk,pre .markdown .blockquote { color:#888 }\n" .
				"pre .number,pre .date,pre .regexp,pre .literal,pre .smalltalk .symbol,pre .smalltalk .char,pre .go .constant,pre .change,pre .markdown .bullet,pre .markdown .link_url { color:#080 }\n" .
				"pre .label,pre .javadoc,pre .ruby .string,pre .decorator,pre .filter .argument,pre .localvars,pre .array,pre .attr_selector,pre .important,pre .pseudo,pre .pi,pre .doctype,pre .deletion,pre .envvar,pre .shebang,pre .apache .sqbracket,pre .nginx .built_in,pre .tex .formula,pre .erlang_repl .reserved,pre .input_number,pre .markdown .link_label,pre .vhdl .attribute { color:#88F }\n" .
				"pre .keyword,pre .id,pre .phpdoc,pre .title,pre .built_in,pre .aggregate,pre .css .tag,pre .javadoctag,pre .phpdoc,pre .yardoctag,pre .smalltalk .class,pre .winutils,pre .bash .variable,pre .apache .tag,pre .go .typename,pre .tex .command,pre .markdown .strong,pre .request,pre .status { font-weight:bold }\n" .
				"pre .markdown .emphasis { font-style:italic }\n" .
				"pre .nginx .built_in { font-weight:normal }\n" .
				"pre .coffeescript .javascript,pre .xml .css,pre .xml .javascript,pre .xml .vbscript,pre .tex .formula { opacity:.5 }\n"
			);
		}

		$this->output_raw("</style>\n\n");

		// set up HighlightJS
		if ( $usehljs )
		{
			$this->output_raw(
				"<script src=\"" . QA_HTML_THEME_LAYER_URLTOROOT . "pagedown/highlight.min.js\"></script>\n" .

				"<script type=\"text/javascript\">\n" .
				"$(function(){\n" .
				"	$('.wmd-input').keypress(function(){\n" .
				"		window.clearTimeout(hljs.Timeout);\n" .
				"		hljs.Timeout = window.setTimeout(function() {\n" .
				"			hljs.initHighlighting.called = false;\n" .
				"			hljs.initHighlighting();\n" .
				"		}, 500);\n" .
				"	});\n" .
				"	window.setTimeout(function() {\n" .
				"		hljs.initHighlighting.called = false;\n" .
				"		hljs.initHighlighting();\n" .
				"	}, 500);\n" .
				"});\n" .
				"</script>\n\n"
			);
		}
	}

}
