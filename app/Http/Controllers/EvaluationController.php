<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvaluationController extends Controller
{
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function evaluate() {
        $contents = $this->request->contents;
        Storage::disk('base')->put('resources/views/eval/codes.blade.php', $contents);
        $contents = view('eval.codes');

        return $contents;
    }
}
