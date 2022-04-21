<?php


class Storage {



  private $size;
  private $info;
  private $type;
  private $link;

  private $tempPath;
  private $path;
  private $file;

  private const storageFolder = "/Datafiles/";
  private const uploadDirectory = ROOT . "/";

  private $mimeTypes = array(
    'text/plain' => 'txt',
    'text/html' => 'htm',
    'text/html' => 'html',
    'text/html' => 'php',
    'text/css' => 'css',
    'application/javascript' => 'js',
    'application/json' => 'json',
    'application/xml' => 'xml',
    'application/x-shockwave-flash' => 'swf',
    'video/x-flv' => 'flv',

    // images
    'image/png' => 'png',
    'image/jpeg' => 'jpe',
    'image/jpeg' => 'jpeg',
    'image/jpeg' => 'jpg',
    'image/gif' => 'gif',
    'image/bmp' => 'bmp',
    'image/vnd.microsoft.icon' => 'ico',
    'image/tiff' => 'tiff',
    'image/tiff' => 'tif',
    'image/svg+xml' => 'svg',
    'image/svg+xml' => 'svgz',

    // archives
    'application/zip' => 'zip',
    'application/x-rar-compressed' => 'rar',
    'application/x-msdownload' => 'exe',
    'application/x-msdownload' => 'msi',
    'application/vnd.ms-cab-compressed' => 'cab',

    // audio/video
    'audio/mpeg' => 'mp3',
    'video/quicktime' => 'qt',
    'video/quicktime' => 'mov',

    // adobe
    'application/pdf' => 'pdf',
    'image/vnd.adobe.photoshop' => 'psd',
    'application/postscript' => 'ai',
    'application/postscript' => 'eps',
    'application/postscript' => 'ps',

    // ms office
    'application/msword' => 'doc',
    'application/rtf' => 'rtf',
    'application/vnd.ms-excel' => 'xls',
    'application/vnd.ms-powerpoint' => 'ppt',
    'application/msword' => 'docx',
    'application/vnd.ms-excel' => 'xlsx',
    'application/vnd.ms-powerpoint' => 'pptx',


    // open office
    'application/vnd.oasis.opendocument.text' => 'odt',
    'application/vnd.oasis.opendocument.spreadsheet' => 'ods',
  );

  public function prepare($file) {
    echo $file['tmp_name'];
    echo filesize($this->tempPath);

    $this->tempPath = $file['tmp_name'];
    $this->size = filesize($this->tempPath);
    //$this->info = finfo_open(FILEINFO_MIME_TYPE);
    $this->info = $file["type"];
    var_dump($this->info);
    //$this->type = finfo_file($this->info, $this->tempPath);
    $this->type = $file["type"];
  }

  public function validate($rules = []) {
    if (empty($rules)) {
      return true;
    }
    if (isset($rules['size'])) {
      if ($rules['size'] > (1024 * 1024 * $this->size)) {
        return false;
      }
    }
    if (isset($rules['mime']) && !empty($rules['mime'])) {
      if (!in_array($this->type, $rules['mime'])) {
        return false;
      }
    }
    return true;
  }

  public function upload($filename) {

    try {
      $filename = $filename ? $filename : randomString(5);

      $extension = $this->mimeTypes[$this->type];
      $newFilepath = $filename . "." . $extension;

      if (!cp($this->tempPath, self::uploadDirectory . self::storageFolder . $newFilepath)) {
        return "";
      }
      unlink($this->tempPath);

      $this->link ="http://sl.regeneratedidentities.org/" . self::storageFolder . $newFilepath;

      return $this->link;
    } catch (\Throwable $th) {
      return "";
    }
  }

  public static function getLink($file) {
    if (is_array($file)) {
      $links = [];
      for ($i = 0; $i < count($file); $i++) {
        $target = "http://sl.regeneratedidentities.org/" . self::storageFolder . basename($file[$i]);
        $links[] = $target;
      }
      return $links;
    }
    return "http://sl.regeneratedidentities.org/" . self::storageFolder . basename($file);
  }

  public function getUploadedLink() {
    return $this->link;
  }

  public static function delete($file) {
    try {
      if (!is_array($file)) {
        $file = [$file];
      }
      for ($i = 0; $i < count($file); $i++) {
        $target = self::uploadDirectory . self::storageFolder . basename($file[$i]);
        // echo "target >>> " . $target . "<<<";
        unlink($target);
      }
      return true;
    } catch (\Throwable $th) {
      return false;
    }
  }
}
