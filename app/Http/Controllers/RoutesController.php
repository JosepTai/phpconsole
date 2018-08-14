<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoutesController extends Controller
{
    protected $storage;
    protected $request;
    protected $response = [];


    public function __construct(Request $request, Storage $storage) {
        $this->storage = $storage;
        $this->request = $request;
    }

    public function getContents() {
        $contents = file_get_contents(base_path('routes/defined.php'));
        return $contents;
    }

    public function updateContents() {
        $contents = $this->request->contents;
        $this->response['updated'] = false;
        $this->response['same_contents'] = false;

        $oldContents = file_get_contents(base_path('routes/defined.php'));
        if ($oldContents == $contents) {
            $this->response['same_contents'] = true;
        }
        $updated = $this->storage::disk('base')->put("routes/defined.php", $contents);
        if ($updated) {
            $this->response['updated'] = true;
        }

        return response()->json($this->response);
    }

    public function analyzeRoutesBeforeSave() {
        if (isset($this->request->errorContents[0])) {
            return response()->json($this->request->errorContents[0]);
        }

        return 'no-errors';
    }
}
