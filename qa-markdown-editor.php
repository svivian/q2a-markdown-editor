<?php
/*
	Question2Answer Markdown editor plugin, v2
	License: http://www.gnu.org/licenses/gpl.html
*/

class qa_markdown_editor
{
	private $pluginurl;
	private $cssopt = 'markdown_editor_css';
	private $convopt = 'markdown_comment';

	function load_module( $directory, $urltoroot )
	{
		$this->pluginurl = $urltoroot;
	}

	function calc_quality( $content, $format )
	{
		return $format == 'markdown' ? 1.0 : 0.8;
	}

	function get_field(&$qa_content, $content, $format, $fieldname, $rows, $autofocus)
	{
		$wmd_buttons = $this->pluginurl . 'pagedown/wmd-buttons.png';

		// IMPORTANT: don't forget to copy the CSS from sample.css to your qa-styles.css!
		$html = '';
		$html .= '<div id="wmd-button-bar-'.$fieldname.'" class="wmd-button-bar"></div>' . "\n";
		$html .= '<textarea name="'.$fieldname.'" id="wmd-input-'.$fieldname.'" class="wmd-input">'.$content.'</textarea>' . "\n";
		$html .= '<h3>Preview</h3>' . "\n";
		$html .= '<div id="wmd-preview-'.$fieldname.'" class="wmd-preview"></div>' . "\n";
// 		$html .= '<style>.wmd-button > span { background-image: url("'.$wmd_buttons.'") }</style>' . "\n";

//         $html .= '<script src="'.$this->pluginurl.'pagedown/Markdown.Converter.js"></script>' . "\n";
//         $html .= '<script src="'.$this->pluginurl.'pagedown/Markdown.Sanitizer.js"></script>' . "\n";
//         $html .= '<script src="'.$this->pluginurl.'pagedown/Markdown.Editor.js"></script>' . "\n";

		// comment this script and uncomment the 3 above to use the non-minified code
    	$html .= '<script src="'.$this->pluginurl.'pagedown/markdown.min.js"></script>' . "\n";

		return array( 'type'=>'custom', 'html'=>$html );
	}

	function read_post($fieldname)
	{
		$html=qa_post_text($fieldname);

		return array(
			'format' => 'markdown',
			'content' => $html
		);
	}

	function load_script($fieldname)
	{
		return
			'var converter = Markdown.getSanitizingConverter();' . "\n" .
			'var editor = new Markdown.Editor(converter, "-'.$fieldname.'");' . "\n" .
			'editor.run();' . "\n";
	}


	// set admin options
	function admin_form( &$qa_content )
	{
		$saved_msg = null;

		if ( qa_clicked('markdown_save') )
		{
			// save options
			$hidecss = qa_post_text('md_hidecss') ? '1' : '0';
			qa_opt($this->cssopt, $hidecss);
			$convert = qa_post_text('md_comments') ? '1' : '0';
			qa_opt($this->convopt, $convert);

			$saved_msg = 'Options saved.';
		}


		return array(
			'ok' => $saved_msg,
					'style' => 'wide',

			'fields' => array(
				'css' => array(
					'type' => 'checkbox',
					'label' => 'Don\'t add CSS inline',
					'tags' => 'NAME="md_hidecss"',
					'value' => qa_opt($this->cssopt) === '1',
					'note' => 'Tick if you added the CSS to your own stylesheet (more efficient).',
				),
				'comments' => array(
					'type' => 'checkbox',
					'label' => 'Plaintext comments',
					'tags' => 'NAME="md_comments"',
					'value' => qa_opt($this->convopt) === '1',
					'note' => 'Sets a post as plaintext when converting answers to comments.',
				),
			),

			'buttons' => array(
				'save' => array(
					'tags' => 'NAME="markdown_save"',
					'label' => 'Save options',
					'value' => '1',
				),
			),
		);
	}

}
