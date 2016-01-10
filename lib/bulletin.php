<?php
/**
 * Solid Gear
 *
 * @author Jesus Macias Portela <jmacias@solidgear.es>
 * @copyright (C) 2016 ownCloud, Inc.
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace SG\bulletin\lib;

/**
 * SG Bulletin
 *
 * PHP Class to manage Sharepoint instance as virtual file system
 *
 * @author Jesus Macias Portela
 * @version 0.1.0
 *
 */

class Bulletin { 

    private $bulletin = array();
    private $dir = "./";
    private $createThumnails = false;
    private $dir_webscreenshots;
    private $dir_thumbnails;
    private $thumbWidth = 150;
    private $thumbHeight = 100;
    private $template;

    public function __construct($dir, $createThumnails = false) {
        if (!file_exists($dir)) {
            throw new \Exception("Folder does not exists", 1);
        }        
        $this->dir = $dir;
        $this->template = "./templates/bulletin.php";

        $this->createThumnails = $createThumnails;
        if($this->createThumnails){
            $this->dir_webscreenshots = $this->dir . "webscreenshots";
            $this->dir_thumbnails = $this->dir . "thumbnails";
            if (!file_exists($this->dir_webscreenshots)) {
                mkdir($this->dir_webscreenshots, 0777, true);
            }
            if (!file_exists($this->dir_thumbnails)) {
                mkdir($this->dir_thumbnails, 0777, true);
                copy("./templates/thumb.jpg", $this->dir_thumbnails."/thumb.jpg");
            } 
        }
        try{
            $this->buildBulletin();
        }catch (\Exception $e) {
            //TODO: Log exception
            throw $e;
        }
    }

    public function getBulletin(){
        return $this->bulletin;
    }

    public function getHTML(){
        return $this->render($this->bulletin);
    }

    public function getJSON(){
        
    }

    public function getPDF(){
        
    }    

    public function buildBulletin(){
        foreach (glob($this->dir . "*.txt") as $filename) {
            //Delete blank lines
            file_put_contents($filename, preg_replace('/\R+/', "\n", file_get_contents($filename)));

            $handle = @fopen($filename, "r");
            if ($handle) {
                $writer = pathinfo($filename, PATHINFO_FILENAME);
                $bulletin[$writer] = array();

                while (($title = fgets($handle)) !== false && ($url = fgets($handle)) !== false && ($description = fgets($handle)) !== false && ($tags = fgets($handle)) !== false && ($twitter = fgets($handle)) !== false){
                    $element = array();
                    $element['title'] = trim(str_replace("TITLE:", "", $title));
                    $element['url'] = trim(str_replace("URL:", "", $url));
                    $element['description'] = trim(str_replace("DESCRIPTION:", "", $description));
                    $element['tags'] = trim(str_replace("TAGS:", "", $tags));
                    $element['twitter'] = trim(str_replace("TWITTER:", "", $twitter));
                    if($this->createThumnails){
                        list($element['image'], $element['thumb']) = $this->getWebScreenShot($element['url']);
                        if($element['thumb'] === ''){
                            $element['thumb'] = $element['image'] = 'thumnails/thumb.jpg'; 
                        }
                    } else {
                        $element['thumb'] = $element['image'] = 'thumbnails/thumb.jpg';             
                    }
                    $this->bulletin[$writer][] = $element;
                }
                if (!feof($handle)) {
                    throw new \Exception("Error: unexpected fgets() fail with file ". $filename);
                }
                fclose($handle);
            }
        }
    }

    private function getWebScreenShot($url){
        $headers = @get_headers($url);
        if(strpos($headers[0], '200') === false){
            return array('', '');
        }
        $name = sha1($url).".jpg";
        if(!file_exists($this->dir_webscreenshots . "/" . $name)){
            $command = "phantomjs /rasterize.js";
            $ex = "$command $url '" . $this->dir_webscreenshots . "/" . $name."' 1280px";
            system($ex, $result);
            $this->createThumbs($this->dir_webscreenshots."/".$name, $this->thumbWidth, $this->thumbHeight);            
            if(!file_exists($this->dir_thumbnails . "/" . $name)){
                return array('', '');
            }
        }
        return array( "webscreenshots/".$name, "thumbnails/".$name);
    }

    private function createThumbs($image, $thumbWidth, $thumbHeight){
        if(file_exists($image)){
            // parse path for the extension
            $info = pathinfo($image);
            // continue only if this is a JPEG image
            if ( strtolower($info['extension']) == 'jpg' && exif_imagetype($image) == IMAGETYPE_JPEG) 
            {
              // load image and get image size
              $img = imagecreatefromjpeg($image);
              $width = imagesx($img);
              $height = imagesy($img);

              // calculate thumbnail size
              $new_width = $thumbWidth;
              $new_height = floor($height * ($thumbWidth / $width));

              // create a new temporary image
              $tmp_img = imagecreatetruecolor($new_width, $new_height);

              // copy and resize old image into new image 
              imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

              $to_crop_array = array('x' =>0 , 'y' => 0, 'width' => $thumbWidth, 'height'=> $thumbHeight);
              $thumb_im = imagecrop($tmp_img, $to_crop_array);

              // save thumbnail into a file
              imagejpeg($thumb_im, str_replace($this->dir_webscreenshots, $this->dir_thumbnails, $image));
            } 
        }
    }  

    /**
     * Render the template, returning it's content.
     * @param array $data Data made available to the view.
     * @return string The rendered template.
     */
    public function render(Array $data) {
        //extract($data);
        ob_start();
        include($this->template);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

 }
