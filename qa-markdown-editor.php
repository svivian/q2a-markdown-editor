<?php
/*
	Question2Answer Markdown editor plugin, v2
	License: http://www.gnu.org/licenses/gpl.html
*/

class qa_markdown_editor
{
	var $urltoroot;

	function load_module($directory, $urltoroot)
	{
		$this->urltoroot = $urltoroot;
	}

	function calc_quality($content, $format)
	{
		if ($format=='markdown')
			return 1.0;
		else
			return 0.8;
	}

	function get_field(&$qa_content, $content, $format, $fieldname, $rows, $autofocus)
	{
		$wmd_buttons = $this->urltoroot . 'pagedown/wmd-buttons.png';

		// IMPORTANT: don't forget to copy the CSS from sample.css to your qa-styles.css!
		$html = '';
		$html .= '<div id="wmd-button-bar" class="wmd-button-bar"></div>' . "\n";
		$html .= '<textarea name="'.$fieldname.'" id="wmd-input" class="wmd-input">'.$content.'</textarea>' . "\n";
		$html .= '<h3>Preview</h3>' . "\n";
		$html .= '<div id="wmd-preview" class="wmd-preview"></div>' . "\n";
		$html .= '<style>.wmd-button > span { background-image: url("'.$wmd_buttons.'") }</style>' . "\n";

//         $html .= '<script src="'.$this->urltoroot.'pagedown/Markdown.Converter.js"></script>' . "\n";
//         $html .= '<script src="'.$this->urltoroot.'pagedown/Markdown.Sanitizer.js"></script>' . "\n";
//         $html .= '<script src="'.$this->urltoroot.'pagedown/Markdown.Editor.js"></script>' . "\n";

		// comment this script and uncomment the 3 above to use the non-minified code
    	$html .= '<script src="'.$this->urltoroot.'pagedown/markdown.min.js"></script>' . "\n";
        $html .= '
			<script>(function () {
				var converter = Markdown.getSanitizingConverter();
				var editor = new Markdown.Editor(converter);
				editor.run();
			})();</script>' . "\n";

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

}
