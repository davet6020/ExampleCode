<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected $viewVars = [];
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;

        if (!empty(\Request::get('active_user'))) {
            $this->user = \Request::get('active_user');
        }
    }

    protected function render($view) {
        return view($view, $this->viewVars);
    }

    protected function json($status = 200) {
        return \Response::json($this->viewVars, $status);
    }

    protected function abort($error = '', $status = 400) {
        $errors = (!empty($this->viewVars['errors'])) ? $this->viewVars['errors'] : [];
        if (!is_array($errors)) {
            $errors = [$errors];
        }
        if (!empty($error)) {
            $errors[] = $error;
        }
        $ret = [];
        foreach ($errors as $err) {
            $ret[] = [
                'status' => $status,
                'id' => str_random(10),
                'detail' => trans($err),
            ];
        }
        $this->viewVars['errors'] = $ret;
        return $this->json($status);
    }

    protected function success($msg) {
        $this->viewVars['successes'][] = [
            'id' => str_random(10),
            'detail' => trans($msg)
        ];
    }

    protected function warning($msg, $status = 0) {
        $this->viewVars['warnings'][] = [
            'status' => $status,
            'id' => str_random(10),
            'detail' => trans($msg)
        ];
    }

    // Angular route for display, pulls errors and flash messages out of the session
    public function index() {
        $error = $this->request->session()->pull('error');
        if (!empty($error)) {
            $this->viewVars['errors'] = [[
                'status' => 400,
                'id' => str_random(10),
                'detail' => trans($error)
            ]];
        }
        $success = $this->request->session()->pull('success');
        if (!empty($success)) {
            $this->viewVars['successes'] = [[
                'status' => 400,
                'id' => str_random(10),
                'detail' => trans($success)
            ]];
        }
        return $this->render('index');
    }

    protected function formatValidationErrors($errors) {
        $retErr = [];
        foreach ($errors->keys() as $key) {
            $retErr[] = [
                'id' => str_random(10),
                'status' => 400,
                'detail' => $errors->first($key),
                'field' => $key,
                'type' => 'validation'
            ];
        }
        return $retErr;
    }

    protected function getPagination($builder)
    {
        $query = request()->query();
        if (!empty($query['draw'])) { // this request came from datatables
            $this->viewVars['recordsTotal'] = $builder->count();

            $searchVal = array_get($query, 'search.value');
            if ($searchVal) {
                $first = true;
                foreach ($query['columns'] as $key => $column) {
                    if (!empty($column['searchable']) && $column['searchable'] == 'true') {
                        if (!$first) {
                            $builder = $builder->orWhere($column['data'], 'LIKE', $searchVal . '%');
                        } else {
                            $builder = $builder->where($column['data'], 'LIKE', $searchVal . '%');
                            $first = false;
                        }
                    }
                }
            }

            $orderColumn = array_get($query, 'order.0.column');
            $orderColumn = array_get($query, 'columns.' . $orderColumn . '.data');
            $orderDir = array_get($query, 'order.0.dir');

            $builder = $builder->orderBy($orderColumn, $orderDir);

            // return the draw
            $this->viewVars['draw'] = (integer) $query['draw'];
            // return the number of records after filter

            $this->viewVars['recordsFiltered'] = $builder->count();

            if (!empty($query['length'])) {
                $builder = $builder->take((integer) $query['length']);
            }

            if (!empty($query['start'])) {
                $builder = $builder->skip((integer) $query['start']);
            }
        }
        return $builder;
    }
}
