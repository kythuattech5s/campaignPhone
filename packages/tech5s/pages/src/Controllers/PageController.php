<?php

namespace Tech5s\PageGrapes\Controllers;

use Tech5s\PageGrapes\Models\Page;
use Illuminate\Http\Request;
use vanhenry\manager\controller\BaseAdminController;

class PageController extends BaseAdminController
{
    const ROOT = 'page-custom/';
    public function __construct()
    {
        parent::__construct();
        $this->middleware('h_users');
    }

    public function edit(Request $request)
    {
        $currentItem = Page::find($request->input('id'));
        session()->put('page-edit-current', $currentItem);
        return view('pg::edit', compact('currentItem'));
    }

    public function loadPage(Request $request, $id)
    {
        $page = session()->get('page-edit-current');
        $folder = $page->url;
        if (!file_exists(public_path(self::ROOT)) && !is_dir(public_path(self::ROOT))) {
            mkdir(public_path(self::ROOT), 0777, true);
        }
        if (!file_exists(public_path(self::ROOT . $page->url))) {
            file_put_contents(public_path(str_replace('/', '\\', self::ROOT . $folder)), '');
        }
        return file_get_contents(public_path(str_replace('/', '\\', self::ROOT . $folder)));
    }

    public function savePage(Request $request, $id)
    {
        $page = session()->get('page-edit-current');
        $folder = $page->url;
        if (!file_exists(public_path(self::ROOT)) && !is_dir(public_path(self::ROOT))) {
            mkdir(public_path(self::ROOT), 0777, true);
        }
        if (!file_exists(public_path(self::ROOT . $page->url))) {
            file_put_contents(public_path(str_replace('/', '\\', self::ROOT . $folder)), '');
        }
        $data = $request->all();
        $content = json_encode($data, true);
        file_put_contents(public_path(str_replace('/', '\\', self::ROOT . $folder)), $content);
        $render = view('pg::render', compact('data'))->render();
        file_put_contents(public_path(str_replace('/', '\\', self::ROOT . $folder) . '.html'), $render);
        return true;
    }


    public function view(Request $request, $route, $link)
    {
        $page = Page::where('slug', $link)->first();
        if (!file_exists(public_path(self::ROOT . $page->url . '.html'))) {
            return redirect()->to('/');
        }
        $content = file_get_contents(public_path(self::ROOT . $page->url . '.html'));
        return view('pg::index', compact('content'));
    }
}
