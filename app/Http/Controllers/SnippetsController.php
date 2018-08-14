<?php

namespace App\Http\Controllers;

use App\Snippet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SnippetsController extends Controller {

    protected $request;
    protected $snippet;

    public function __construct(Request $request, Snippet $snippet) {
        $this->request = $request;
        $this->snippet = $snippet;
    }

    public function store() {

        $contents = htmlspecialchars($this->request->contents);

        $this->snippet->name = $this->request->name;
        $this->snippet->contents = $contents;
        $this->snippet->save();

        return response()->json([
            'id' => $this->snippet->id,
            'contents' => $contents,
            'name' => $this->snippet->name
        ]);
    }

    public function show() {
        if (isset($this->request->name)) {
            $name = $this->request->name;
            $this->snippet =  $this->snippet::orderBy('id', 'desc')
                ->where('name', 'LIKE', "%{$name}%")
                ->orWhere('contents', 'LIKE', "%{$name}%")
                ->get();
        } else {
            $this->snippet = $this->snippet->orderBy('id', 'desc')->get();
        }

        $html = '';
        $snippets = '';
        foreach($this->snippet as $snippet) {
            $snippets .= $snippet->contents;
        }

        return response()->json([
            'html' => trim($html),
            'all_snippets' => $this->snippet,
            'snippets' => $snippets,
        ]);
    }

    public function loadInEditor() {
        $this->snippet = $this->snippet->find($this->request->snippetId);
        if (!$this->snippet) {
            return response()->json([
                'id' => 0,
                'snippet' => '',
            ]);
        }
        $this->snippet->updated_at = now();
        $this->snippet->save();
        $snippet = htmlspecialchars_decode($this->snippet->contents);

        return response()->json([
            'id' => $this->snippet->id,
            'snippet' => str_replace_last('\n\n', '\n\n', $snippet)
        ]);
    }

    public function update() {
        $this->snippet = $this->snippet->find($this->request->id);
        $snippet = $this->request->contents;
        $this->snippet->contents = htmlspecialchars($snippet);
        $this->snippet->updated_at = now();
        $this->snippet->save();

        return response()->json([
            'id' => $this->snippet->id,
            'contents' => str_replace_first(' ', '', $this->snippet->contents),
        ]);
    }

    public function updateName() {
        $this->snippet = $this->snippet->find($this->request->id);
        $this->snippet->name = $this->request->name;
        $this->snippet->save();

        return response()->json($this->snippet);
    }

    public function destroy() {
        $this->snippet = $this->snippet->find($this->request->id);

        try {
            $response = $this->snippet;
            $this->snippet->delete();
        } catch (\Exception $e) {
            $response = $e;
        }

        return response()->json($this->snippet);
    }
}
