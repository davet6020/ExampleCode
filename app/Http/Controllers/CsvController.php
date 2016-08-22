<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;
use Excel;
use splitbrain\PHPArchive\Tar;
use splitbrain\PHPArchive\Zip;
use App\OctoParts\Client;

class CsvController extends Controller
{

  public function importFile()  {
      return view('importFile');
  }

  public function importWhat()  {
      if(Input::hasFile('import_file')){
          $path = Input::file('import_file')->getRealPath();
          $ext = Input::file('import_file')->getClientOriginalExtension();

            switch(strtolower($ext))  {
                case 'csv':
                  $data = $this->importExcel($path);
                break;
                case 'tgz':
                  $data = $this->viewTar($path);
                break;
                case 'xls':
                  $data = $this->importExcel($path);
                break;
                case 'zip':
                  $data = $this->viewZip($path);
                break;
                default:
                  $data = "That file type is unsupported";
                break;
            } //End switch
          $this->viewVars['data'] = $data;
          return $this->render('octoparts/table');
      } else {
          return view('importFile');
      }
  }

  public function importExcel($path) {
      $data = Excel::load($path, function($reader) {
        // Blah
      })->noHeading()->get()->toArray();

      $headers = array_shift($data);

      $searchParts = array_map(function ($item) {
        return $item[5];
      }, $data);

      $octoRead = new \App\OctoParts\Client();

      return $octoRead->search($searchParts);
  }

  public function viewTar($path) {
      // To list the contents of an existing TAR archive, open() it and use contents() on it:
      $tar = new Tar();
      $tar->open($path);
      $toc = $tar->contents();

      $output = [];

      foreach($toc as $k => $val)  {
        /*echo $val->getPath();
        echo "<br/>";*/
        $output[] = $val->getPath();
      }
      return $output;
  }

  public function viewZip($path) {
      // To list the contents of an existing ZIP archive, open() it and use contents() on it:
      $zip = new Zip();
      $zip->open($path);
      $toc = $zip->contents();

      $output = [];

      foreach($toc as $k => $val)  {
        /*echo $val->getPath();
        echo "<br/>";*/
        $output[] = $val->getPath();
      }
      return $output;
  }

}
