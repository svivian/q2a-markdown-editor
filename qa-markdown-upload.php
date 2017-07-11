<?php

class qa_markdown_upload
{

	private $imguplopt = 'markdown_uploadimage';

	public function match_request($request)
	{
		return ($request == 'qa-markdown-upload');
	}

	public function process_request($request)
	{
		$message = '';
		$url = '';

		if (is_array($_FILES) && count($_FILES)) {
			$uploadimg = qa_opt($this->imguplopt) === '1';

			if (!$uploadimg) {
				$message = qa_lang('users/no_permission');
				
				header('Content-type: application/json');
				echo json_encode([
					'error'=>$message
				]);
			} else {
				require_once QA_INCLUDE_DIR.'app/upload.php';

				$upload = qa_upload_file_one(
					qa_get_max_upload_size(), // do not restrict upload size
					true, // force upload to image only
					500, // max width (px)
					500 // max height (px)
				);

				$message = @$upload['error'];
				$url = @$upload['bloburl'];
				$id = @$upload['blobid'];


				header('Content-type: application/json');
				echo json_encode([
					'error'=>$message,
					'url'=>$url,
					'id'=>$id,
				]);
			}
		}

		return null;
	}
}
