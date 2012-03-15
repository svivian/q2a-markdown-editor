<?php
/*
	Question2Answer Markdown editor plugin, v2
	License: http://www.gnu.org/licenses/gpl.html
*/

class qa_markdown_viewer
{
	var $path;

	function load_module($directory, $urltoroot)
	{
		$this->path = $directory;
	}

	function calc_quality($content, $format)
	{
		return 1.0;
	}

	function get_html($content, $format, $options)
	{
		if ( isset( $options['blockwordspreg'] ) )
		{
			require_once QA_INCLUDE_DIR.'qa-util-string.php';
			$content = qa_block_words_replace( $content, $options['blockwordspreg'] );
		}

		require_once $this->path . 'inc.markdown.php';
		$html = Markdown( $content );
		return qa_sanitize_html($html);
	}

	function get_text($content, $format, $options)
	{
		$viewer = qa_load_module( 'viewer', '' );
		$text = $viewer->get_text( $content, 'html', array() );

		return $text;
	}

}
