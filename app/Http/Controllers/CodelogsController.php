<?php

namespace App\Http\Controllers;

use App\CodeLog;
use App\Snippet;
use Illuminate\Http\Request;

class CodelogsController extends Controller
{
    protected $log;
    protected $snippet;
    protected $request;

    public function __construct() {
        $this->log = app(CodeLog::class);
        $this->request = app(Request::class);
        $this->snippet = app(Snippet::class);
    }

    public function update()
    {
        $this->log = $this->snippet->orderBy('updated_at', 'desc')->first();
        $this->log->codes = $this->request->codes;
        $this->log->updated_at = now();
        $this->log->save();

        exit(settings(true)->keep_last_codes);
    }

    public function getCodes() {
        $comment = "Start coding here";

        $codes = optional($this->snippet->orderBy('updated_at', 'desc')->first());
        $code = str_replace($comment, "Continue coding", $codes->contents);

        if (settings(true)->keep_last_codes == 'yes') {
            return response()->json([
                'codes' => htmlspecialchars_decode($code),
                'id' => $codes->id
            ]);
        }

        exit("not-allowed");
    }

}
