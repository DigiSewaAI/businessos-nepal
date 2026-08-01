<?php

namespace App\Http\Controllers;

use App\Models\CMSPage;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    public function index()
    {
        $pages = CMSPage::paginate(20);
        return view('admin.cms.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.cms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_pages',
            'content' => 'required|string',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $page = CMSPage::create($request->all());
        return redirect()->route('admin.cms.index')->with('success', "Page '{$page->title}' created.");
    }

    public function show($id)
    {
        $page = CMSPage::findOrFail($id);
        return view('admin.cms.show', compact('page'));
    }

    public function edit($id)
    {
        $page = CMSPage::findOrFail($id);
        return view('admin.cms.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = CMSPage::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_pages,slug,' . $page->id,
            'content' => 'required|string',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $page->update($request->all());
        return redirect()->route('admin.cms.index')->with('success', "Page '{$page->title}' updated.");
    }

    public function destroy($id)
    {
        CMSPage::findOrFail($id)->delete();
        return redirect()->route('admin.cms.index')->with('success', 'Page deleted.');
    }
}