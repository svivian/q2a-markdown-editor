<?php
/*
	Plugin Name: Markdown Editor
	Plugin URI: https://github.com/svivian/q2a-markdown-editor
	Plugin Description: Markdown plugin for simple text-based markup
	Plugin Version: 2
	Plugin Date: 2011-07-15
	Plugin Author: Scott Vivian
	Plugin Author URI: http://codelair.co.uk/
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.3

	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

	More about this license: http://www.gnu.org/licenses/gpl.html
*/

if ( !defined('QA_VERSION') )
{
	header('Location: ../../');
	exit;
}


qa_register_plugin_module('editor', 'qa-markdown-editor.php', 'qa_markdown_editor', 'Markdown Editor');
qa_register_plugin_module('viewer', 'qa-markdown-viewer.php', 'qa_markdown_viewer', 'Markdown Viewer');
qa_register_plugin_layer('qa-md-layer.php', 'Markdown Editor layer');
