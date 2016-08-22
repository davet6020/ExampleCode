<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\QuickQuoteDefaults;

class QuickQuoteDefaultsController extends Controller
{
  

    /**
     * list the quick quotes defaults endpoint.
     * @return array json response
     */
    public function apiList(Request $request)
    {
        // There is only one default record so get the first one.
        $newDefault = QuickQuoteDefaults::all();
        
        $this->viewVars['data'] = $newDefault->toArray();
        return $this->json();
    }

  /**
     * update the quick quotes default endpoint.
     * Example of what the request will look like:
     *   {
     *     "data":[
     *       {"id":1,"field":"bgas","default":10,"min":1,"max":76},
     *       {"id":2,"field":"bom_lines","default":10,"min":1,"max":77},
     *       {"id":3,"field":"quantity","default":10,"min":1,"max":78},
     *       {"id":4,"field":"smt","default":10,"min":0,"max":79}
     *     ]
     *   }
     * @return array json response
     */
    public function apiUpdate(Request $request)
    {
        /*
          Loop through each value in the request object, 
          update matching record in the table with new values.
        */
        foreach($request->input('data') as $req) {
            QuickQuoteDefaults::where('field', $req['field'])
                              ->update(['default' => $req['default'], 'min' => $req['min'], 'max' => $req['max']]);
        }

        $this->viewVars['data'] = $request->input('data');
        return $this->json();
    }

}
