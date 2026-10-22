<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:30',
                        Rule::unique('tags', 'name')->where('user_id', auth()->id())],
            'color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/', //validate correct format for hexcode, also REGEX MENTIONED???
            'icon'  => 'required|string',
            'tag_id' => ['nullable', Rule::exists('tags', 'id')->where('user_id', auth()->id())],
        ]);

        Tag::create($validated);

        return back()->with('success', 'Custom tag created successfully!');
    }
}
