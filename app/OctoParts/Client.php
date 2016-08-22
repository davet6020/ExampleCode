<?php

namespace App\OctoParts;

class Client
{
    protected $parts;

    public function __construct($parts = null)
    {
        $this->parts = $parts;
    }

    public function search($parts = null)
    {
        if (!empty($parts)) {
            $this->parts = $parts;
        }

        if (empty($this->parts)) {
            return [];
        }

        $searchParts = array_map(function ($item) {
            return ['mpn' => $item];
        }, $this->parts);

        $query = [
            'apikey' => 'SomeKey',
            'queries' => json_encode($searchParts)
        ];

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

        return $cleanedData;
    }
}
