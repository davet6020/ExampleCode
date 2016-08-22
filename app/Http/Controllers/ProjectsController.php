<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Project;
use App\Services\FileManager;

class ProjectsController extends Controller
{
    /**
     * list a single project or all projects for that user
     * @param  string $id Project ID
     * @return array      JSON view for angular
     */
    public function apiList($id = null)
    {
        if ($id) {
            $project = Project::with('User')->find($id);
        }
        if (!$this->user->hasAccess('admin')) {
            if ($id && $project->user->id !== $this->user->id) {
                return $this->abort('auth.denied');
            }
        }

        if (!empty($project)) {
            $project->files = $project->getFiles();
            // json decode the design files
            $project->design = json_decode($project->design);
            $this->viewVars['project'] = $project;
        } else {
            if (!$this->user->hasAccess('admin')) {
                $projects = $this->getPagination(Project::with('User'))->where('user_id', $this->user->id)->get();
            } else {
                $projects = $this->getPagination(Project::with('User'))->get();
            }

            // parse the project design information
            $projects = $projects->toArray();
            foreach ($projects as $key => $project) {
                $projects[$key] = $this->decodeDesign($project);
            }
            $this->viewVars['data'] = $projects;
        }

        return $this->json();
    }

    /**
     * Create a project endpoint
     * @return array json response
     */
    public function apiCreate()
    {
        $project = new Project();
        $project->fill($this->request->input());
        if (empty($project->user_id)) {
            $project->user_id = $this->user->id;
        }
        if (!$project->save()) {
            $this->viewVars['errors'] = $this->formatValidationErrors($project->getErrors());
            return $this->json(400);
        }
        $this->viewVars['data'] = $project->toArray();
        return $this->json();
    }

    /**
     * update a project endpoint, mostly used for assigning BOM
     * @return array json response
     */
    public function apiUpdate($id)
    {
        $project = Project::find($id);

        if (request()->input('bom') !== $project->bom) {
            $project->bom_processed = false;
            $project->bom_data = '';
        }

        $project->fill(request()->input());

        // if the request came in with a non string value for $project->design, json encode it
        if (!is_string($project->design)) {
            $project->design = json_encode($project->design);
        }

        if (!$project->save()) {
            $this->viewVars['errors'] = $this->formatValidationErrors($project->getErrors());
            return $this->json(400);
        }

        $project = $this->decodeDesign($project);
        $this->viewVars['data'] = $project->toArray();
        return $this->json();
    }

    /**
     * Upload a file endpoint
     * @param  integer $id project ID
     * @return array     json response
     */
    public function apiFileUpload($id)
    {
        // Create a project hash for foldername
        $userId = request()->input('user_id') ? : $this->user->id;

        $project = \App\Project::with('user')->find($id);

        if (empty($project)) {
            return $this->abort('project.missing');
        }

        if ($this->user->id !== $project->user->id && !$this->user->hasAccess('admin')) {
            return $this->abort('auth.denied');
        }

        $file = request()->file('file');
        if (empty($file)) {
            return $this->abort('Missing File');
        }

        $fileManager = new FileManager('projects');
        $saved = $fileManager->save($file, $project->hash());

        // @TODO if one of the files uploaded was the same as the BOM, clear the bom data
        // if (request()->input('bom') !== $project->bom) {
        //     $project->bom_processed = false;
        //     $project->bom_data = '';
        // }

        // Fetch all files for this project and return the listing
        // $ret = $project->getFiles();
        $project->files = $project->getFiles();
        $project = $this->decodeDesign($project);
        $this->viewVars['data'] = $project;
        return $this->json();
    }

    /**
     * return an json representation of the Bom
     * @param  integer $id Project ID
     * @return array     JSON object for API
     */
    public function apiParseCSV($id)
    {
        $project = Project::find($id);
        if (empty($project)) {
            return $this->abort('project.missing');
        }
        if (empty($project->bom)) {
            return $this->abort('Missing BOM File');
        }

        try {
            $this->viewVars['data'] = $this->getBom($project);
        } catch (Exception $e) {
            return $this->abort($e->getMessage());
        }

        return $this->json();
    }

    /**
     * Delete a project endpoint
     * @param  integer $id Project ID
     * @return array       JSON Object
     */
    public function apiDelete($id)
    {
        $project = \App\Project::find($id);

        if (empty($project)) {
            return $this->abort('project.missing');
        }

        if ($this->user->id !== $project->user_id && !$this->user->hasAccess('admin')) {
            return $this->abort('auth.denied');
        }

        // clear the files associated with this project
        $fileManager = new FileManager('projects', $project->hash());
        $fileManager->deleteDirectory();

        if (!$project->delete()) {
            return $this->abort('Expected Error.');
        }

        $this->success('Project Deleted.');
        return $this->json();
    }

    /**
     * Get prices for the BOM associated with this project
     * @param  integer $id The Project ID
     * @return array       JSON Payload
     */
    public function getPrices($id)
    {
        $project = Project::find($id);
        if (empty($project)) {
            return $this->abort('project.missing');
        }
        if (empty($project->bom)) {
            return $this->abort('Missing BOM File');
        }

        $data = $this->getBom($project);

        $headers = array_shift($data);

        $searchParts = array_map(function ($item) {
            return $item[5];
        }, $data);

        $octoRead = new \App\OctoParts\Client();

        $this->viewVars['data'] = $octoRead->search($searchParts);
        return $this->json();
    }

    /**
     * Endpoint for deleting a file from the API
     * @param  int    $id Project ID
     * @return array      JSON Response
     */
    public function apiDeleteFile(int $id)
    {
        $project = \App\Project::find($id);

        if (empty($project)) {
            return $this->abort('project.missing');
        }

        if ($this->user->id !== $project->user_id && !$this->user->hasAccess('admin')) {
            return $this->abort('auth.denied');
        }

        $fileManager = new FileManager('projects', $project->hash());
        if (!$fileManager->delete(request()->input('file'))) {
            return $this->abort('Unable to delete file.');
        }

        return $this->json();
    }

    /**
     * fetch the BOM array from a project
     * @param  Project $project Project to fetch BOM from
     * @return array            BOM in array format
     */
    protected function getBom($project)
    {
        $fileManager = new FileManager('projects');

        $contents = $fileManager->get($project->bom, $project->hash());
        $tempFile = sys_get_temp_dir() . '/' . str_random(10);
        file_put_contents($tempFile, $contents);

        $data = \Excel::selectSheetsByIndex(0)->load($tempFile, function ($reader) {
            // Before the actual load, see documentation
        })->noHeading()->formatDates(false)->get()->toArray();

        $largestRow = 0;
        foreach ($data as $row => $cols) {
            $cols = array_values($cols);
            if (!empty(array_filter($cols))) {
                $data[$row] = $cols;
            } else {
                unset($data[$row]);
                continue;
            }
            $i = 1;
            $count = count($cols);
            while (empty($cols[$count - $i])) {
                $i++;
            }
            if (($count - $i) > $largestRow) {
                $largestRow = $count - $i;
            }
        }

        $data = array_map(function ($row) use ($largestRow) {
            return array_slice($row, 0, $largestRow + 1);
        }, $data);

        // remove the temp file
        unlink($tempFile);
        return array_values($data);
    }

    // Parse the design item and hand that back
    protected function decodeDesign($project)
    {
        // Determine if the project is an object or an array
        $isArray = !is_object($project);

        if ($isArray) {
            if (empty($project['design'])) {
                return $project;
            }
            $decoded = json_decode($project['design']);
            if ($decoded) {
                $project['design'] = $decoded;
            }
            return $project;
        }

        if (empty($project->design)) {
            return $project;
        }
        $decoded = json_decode($project->design);
        if ($decoded) {
            $project->design = $decoded;
        }
        return $project;
    }
}
