<?php 

    class PhotoEditor {
    	private $image;

    	public function __construct($image) {
			$this->Encode($image);	
    	}

    	private function Encode($image) {
    		if (filter_var($image, FILTER_VALIDATE_URL)) {
				$content = file_get_contents($image);
				$ext = pathinfo($image, PATHINFO_EXTENSION);
				if(in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
					$base64 = 'data:image/'.$ext.';base64,'.base64_encode($content);
				} else {
					throw new Exception('File format is not supported');
				}
			} elseif (strpos($image, "\0") === false) {
				if(file_exists($image)) {
					$content = file_get_contents($image);
					$ext = pathinfo($image, PATHINFO_EXTENSION);
					if(in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
						$base64 = 'data:image/'.$ext.';base64,'.base64_encode($content);
					} else {
						throw new Exception('File format is not supported');
					}
				} else {
					throw new Exception('File does not exist');
				}
			} else {
				throw new Exception('Not a valid URL or PATH');
			}
			if(isset($base64)) {
				$this->image = $base64;
			} else {
				throw new Exception('Unable to encode');
			}	
    	}

    	public function ApplyFilter($id) {
            $dict = file_get_contents(__DIR__.'/dict.json');
            $dict = json_decode($dict, true);

    		if(isset($dict[$id])) {
    			$url = 'http://color.photofuneditor.com/'.$dict[$id]['text_code'].'-nvegas';
    		} else {
    			throw new Exception('Invalid filter identifier');
    		}

    		if($url) {
    			$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
				curl_setopt($ch, CURLOPT_POSTFIELDS, ['fileToUpload' => $this->image]);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$response = curl_exec($ch);
				curl_close($ch);
				return 'http://color.photofuneditor.com/output/'.json_decode($response)->file_link;
    		}
    	}
    }

?>