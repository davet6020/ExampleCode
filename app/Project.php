<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class Project extends Model
{
    use ValidatingTrait;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'name' => 'required|between:5,256'
    ];

    protected $fillable = [
        'user_id', 'name', 'id',
        'name', 'user_id', 'order_number',
        'assembly_status', 'parts_status', 'boards_status',
        'status_id', 'quantity', 'bom', 'design'
    ];

    /**
     * associated to the user model
     * @return CovUser The user assigned to this project
     */
    public function user()
    {
        return $this->belongsTo('\App\Users\CovUser', 'user_id');
    }

    public function hash()
    {
        return md5($this->user_id . '+' . $this->id);
    }

    /**
     * List all files on a project
     * @return Array The files associated to the project
     */
    public function getFiles()
    {
        $files = \Storage::disk('projects')->allFiles($this->hash());

        $retVal = [];
        $directories = [];

        // build the directory structure first
        foreach ($files as $file) {
            $parts = explode('/', $file);
            $projectHash = array_shift($parts);
            $fileName = array_pop($parts);

            $pointer =& $directories;
            if (!empty($parts)) {
                while (count($parts) > 1) {
                    $key = (string) array_shift($parts);
                    if (!isset($pointer[$key]) || !is_array($pointer[$key])) {
                        $pointer[$key] = array();
                    }
                    $pointer =& $pointer[$key];
                }
                $key = (string) array_shift($parts);
                if (!isset($pointer[$key])) {
                    $pointer[$key] = [$fileName];
                } else {
                    $pointer[$key][] = $fileName;
                }
            } else {
                $pointer[] = $fileName;
            }
        }

        return $this->formatFileEntry($directories);
    }

    /**
     * Format all the file entries
     * @param  Array $entry the files associated to a project
     * @return array        Formatted entries
     */
    protected function formatFileEntry($entry)
    {
        $retVal = [];
        foreach ($entry as $key => $item) {
            if (!is_array($item)) {
                $retVal[] = ['path' => $item];
            } else {
                $retVal[] = [
                    'path' => $key,
                    'children' => $this->formatFileEntry($item)
                ];
            }
        }
        return $retVal;
    }
}
