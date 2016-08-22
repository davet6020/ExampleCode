<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class OctopartController extends Controller
{

  public function srchParts()  {
    $srchParts = ['PMS430E337AHFD', '24LC256-I/SM'];
    return $this->octoRead($srchParts);
  }


  public function octoRead($srchParts)  {
    // parts should be in the format of ['PMS430E337AHFD', '24LC256-I/SM']
    // $query = ['apikey' => 'SomeKey', 'queries' => json_encode([['mpn' => 'PMS430E337AHFD'], ['mpn' => '24LC256-I/SM']])];
    foreach($srchParts as $p) {
      $parts[] = ['mpn' => $p];
    }

    $query = ['apikey' => 'SomeKey', 'queries' => json_encode($parts)];

    $client = new \GuzzleHttp\Client();
    $res = $client->get('http://octopart.com/api/v3/parts/match', [
      'query' => $query
    ]);

    $contents = (string) $res->getBody();
    $data = json_decode($contents, true);

    // clean up the octopart data
    $cleanedData = [];
    foreach ($data['results'] as $result) {
      foreach ($result['items'] as $item) {
        foreach ($item['offers'] as $offer) {
          if (!empty($offer['prices']['USD'])) {
            $toAdd = array_only($offer, ['sku', 'last_updated', 'in_stock_quantity']);
            if ($toAdd['in_stock_quantity']) {
              $toAdd['prices'] = $offer['prices']['USD'];
            }
            $cleanedData[] = $toAdd;
          }
        }
      }
    }

    $this->viewVars['data'] = $cleanedData;

    return view('octoparts/table', $this->viewVars)->render();
  }


  public function guzzle() {

    $client = new \GuzzleHttp\Client();

    $baseurl = "http://octopart.com/api/v3/parts/match?";
    $apikey  = 'apikey=SomeKey';
    $queries = "queries=[{%22mpn%22:%22PMS430E337AHFD%22}]";
    $datasht = "include[]=datasheets";
    $pretty  = "pretty_print=true";

    $url = $baseurl . '&' . $apikey . '&' . $queries . '&' . $pretty;
    var_dump($url);

    $res = $client->request('GET', $url);

    echo '<pre>' . $res->getBody();

  }


}
