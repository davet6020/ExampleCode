<?php

namespace App\Http\Controllers\Admin;

use App\Admin\AdminSettings;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class AdminSettingsController
 * @package App\Http\Controllers\Admin
 */
class AdminSettingsController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $settings = AdminSettings::where('active', '=', '1')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $settings = false;
        }
        return $this->render('admin.settings.index')->with('data', $settings);
    }

    public function create()
    {
        return $this->render('admin.settings.create');
    }

    public function store()
    {
        $input = $this->request->input();
        $as = new AdminSettings();
        $as->fill($input);
        if (! $as->save()) {
            return redirect()->route('admin.settings.create')
                ->withInput()
                ->withErrors($as->getErrors())
                ->with('error', 'Could not save settings');
        }
        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings created');
    }

    public function edit($id)
    {
        $setting = AdminSettings::find($id);
        if ($setting->active === 0) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'Cannot edit inactive settings');
        }
        return $this->render('admin.settings.edit')->with('data', $setting);
    }

    public function update($id)
    {
        $setting = new AdminSettings();
        $setting->fill($this->request->input());
        if (! $setting->save()) {
            return redirect()->back()
                ->with('error', 'Could not create new settings')
                ->withErrors($setting->getErrors())
                ->withInput();
        }
        $prev = AdminSettings::find($id);
        $prev->active = 0;
        if (! $prev->save()) {
            return redirect()->back()
                ->with('error', 'Could not set previous setting inactive')
                ->withErrors($prev->getErrors())
                ->withInput();
        }
        return redirect()->route('admin.settings.edit', [$setting->id])
            ->with('success', 'New settings created');
    }

}