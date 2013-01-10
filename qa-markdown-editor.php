<?php
/*
	Question2Answer Markdown editor plugin
	License: http://www.gnu.org/licenses/gpl.html
*/

class qa_markdown_editor
{
	private $pluginurl;
	private $cssopt = 'markdown_editor_css';
	private $convopt = 'markdown_comment';
	private $hljsopt = 'markdown_highlightjs';

	function load_module( $directory, $urltoroot )
	{
		$this->pluginurl = $urltoroot;
	}

    function option_default($option)
    {
        if ($option=='markdown_editor_upload_max_size') {
            require_once QA_INCLUDE_DIR.'qa-app-blobs.php';

            return min(qa_get_max_upload_size(), 1048576);
        }
    }


    function calc_quality( $content, $format )
	{
		return $format == 'markdown' ? 1.0 : 0.8;
	}

	function get_field(&$qa_content, $content, $format, $fieldname, $rows, $autofocus)
	{
		$html = '<div id="wmd-button-bar-'.$fieldname.'" class="wmd-button-bar"></div>' . "\n";
		$html .= '<textarea name="'.$fieldname.'" id="wmd-input-'.$fieldname.'" class="wmd-input">'.$content.'</textarea>' . "\n";
		$html .= '<h3>Preview</h3>' . "\n";
		$html .= '<div id="wmd-preview-'.$fieldname.'" class="wmd-preview"></div>' . "\n";

         $html .= '<script src="'.$this->pluginurl.'pagedown/Markdown.Converter.js"></script>' . "\n";
        $html .= '<script src="'.$this->pluginurl.'pagedown/Markdown.Sanitizer.js"></script>' . "\n";
        $html .= '<script src="'.$this->pluginurl.'pagedown/Markdown.Editor.js"></script>' . "\n";

        // Only load the scripts when they are required
        if(qa_opt('markdown_editor_upload_images')) {
            $html .= '<script src="'.$this->pluginurl.'pagedown/Markdown.QAFileUpload.js"></script>' . "\n";
            $html .= '<script src="'.$this->pluginurl.'ajaxfileupload/ajaxfileupload.js"></script>' . "\n";
        }

        // comment this script and uncomment the 3 above to use the non-minified code
        //    	$html .= '<script src="'.$this->pluginurl.'pagedown/markdown.min.js"></script>' . "\n";

		return array( 'type'=>'custom', 'html'=>$html );
	}

	function read_post($fieldname)
	{
		$html = $this->_my_qa_post_text($fieldname);

		return array(
			'format' => 'markdown',
			'content' => $html
		);
	}

	function load_script($fieldname)
	{
		$script =
			'var converter = Markdown.getSanitizingConverter();' . "\n" .
			'var editor = new Markdown.Editor(converter, "-'.$fieldname.'");' . "\n";

        // Only get the insert image callback if allowed to upload images
        if(qa_opt('markdown_editor_upload_images')) {
            $script .=
                'var fileUpload = new Markdown.qaFileUpload("'. qa_path('markdown-editor-upload') .'") ;' . "\n" .
                'editor.hooks.set("insertImageDialog", fileUpload.prompt);'. "\n";
        }
        $script .= 'editor.run();' . "\n";

        return $script;
	}


	// set admin options
	function admin_form( &$qa_content )
	{
        require_once QA_INCLUDE_DIR.'qa-app-blobs.php';

        $saved_msg = null;

		if ( qa_clicked('markdown_save') )
		{
			// save options
			$hidecss = qa_post_text('md_hidecss') ? '1' : '0';
			qa_opt($this->cssopt, $hidecss);
			$convert = qa_post_text('md_comments') ? '1' : '0';
			qa_opt($this->convopt, $convert);
			$convert = qa_post_text('md_highlightjs') ? '1' : '0';
			qa_opt($this->hljsopt, $convert);

            qa_opt('markdown_editor_upload_images', (int)qa_post_text('markdown_editor_upload_images_field'));
            qa_opt('markdown_editor_upload_all', (int)qa_post_text('markdown_editor_upload_all_field'));
            qa_opt('markdown_editor_upload_max_size', min(qa_get_max_upload_size(), 1048576*(float)qa_post_text('markdown_editor_upload_max_size_field')));

            $saved_msg = 'Options saved.';
		}

        qa_set_display_rules($qa_content, array(
            'markdown_editor_upload_all_display' => 'markdown_editor_upload_images_field',
            'markdown_editor_upload_max_size_display' => 'markdown_editor_upload_images_field',
        ));



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
				'highlightjs' => array(
					'type' => 'checkbox',
					'label' => 'Use syntax highlighting',
					'tags' => 'NAME="md_highlightjs"',
					'value' => qa_opt($this->hljsopt) === '1',
					'note' => 'Integrates highlight.js for code blocks.',
				),
                array(
                    'label' => 'Allow images to be uploaded',
                    'type' => 'checkbox',
                    'value' => (int)qa_opt('markdown_editor_upload_images'),
                    'tags' => 'NAME="markdown_editor_upload_images_field" ID="markdown_editor_upload_images_field"',
                ),

                array(
                    'id' => 'markdown_editor_upload_all_display',
                    'label' => 'Allow other content to be uploaded, e.g. Flash, PDF',
                    'type' => 'checkbox',
                    'value' => (int)qa_opt('markdown_editor_upload_all'),
                    'tags' => 'NAME="markdown_editor_upload_all_field"',
                ),

                array(
                    'id' => 'markdown_editor_upload_max_size_display',
                    'label' => 'Maximum size of uploads:',
                    'suffix' => 'MB (max '.$this->bytes_to_mega_html(qa_get_max_upload_size()).')',
                    'type' => 'number',
                    'value' => $this->bytes_to_mega_html(qa_opt('markdown_editor_upload_max_size')),
                    'tags' => 'NAME="markdown_editor_upload_max_size_field"',
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

    function bytes_to_mega_html($bytes)
    {
        return qa_html(number_format($bytes/1048576, 1));
    }



    // copy of qa-base.php > qa_post_text, with trim() function removed.
	function _my_qa_post_text($field)
	{
		return isset($_POST[$field]) ? preg_replace( '/\r\n?/', "\n", qa_gpc_to_string($_POST[$field]) ) : null;
	}


}
