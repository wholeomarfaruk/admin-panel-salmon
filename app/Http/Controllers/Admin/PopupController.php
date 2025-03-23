<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ModelType;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\PopupPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PopupController extends Controller
{
    public function index(){
        $popups = PopupPage::paginate(10);

        return view('admin.popup.index', compact('popups'));
    }
    public function add(){
        return view('admin.popup.popup-add');
    }

    public function store(Request $request){
        $request->validate([
            'is_popup_show' => 'required',
            'url_type' => 'required',
        ]);
        $popup = new PopupPage();
        $popup->is_popup_show = $request->is_popup_show;
        $popup->url_type = $request->url_type ?? null;
        $popup->url = $request->url ?? null;
        $popup->save();

          // Update image
          if ($request->hasFile('image')) {


            $path = 'uploads/popup/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileSize = $request->file('image')->getSize();

            $fileName = "popup" . "_" . Str::uuid() . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($path, $fileName);

            FileHelper::uploadFile($path . $fileName, 'image', ModelType::Popup->value, $popup->id, [
                'name' => "popup",
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }

        return redirect()->route('admin.popup.list')->with('success', 'Popup created successfully');
    }
    public function edit($id){

        $popup = PopupPage::find($id);

        return view('admin.popup.popup-edit', compact('popup'));
    }
    public function update(Request $request, $id)
    {
        // return $request->all();

        $request->validate([
            'is_popup_show' => 'required',
            'url_type' => 'required',
        ]);
        $popup = PopupPage::find($id);
        $popup->is_popup_show = $request->is_popup_show;
        $popup->url_type = $request->url_type;
        $popup->url = $request->url ?? null;
        $popup->save();

        // Update image
        if ($request->hasFile('image')) {
            $oldThumbnail = $popup->image;
            if ($oldThumbnail) {
                FileHelper::deleteFile($oldThumbnail->id);
            }

            $path = 'uploads/popup/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileSize = $request->file('image')->getSize();

            $fileName = "popup" . "_" . Str::uuid() . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($path, $fileName);

            FileHelper::uploadFile($path . $fileName, 'image', ModelType::Popup->value, $popup->id, [
                'name' => "popup",
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }
        return redirect()->route('admin.popup.list')->with('success', 'Popup updated successfully');
    }
    public function delete($id){
        $popup = PopupPage::find($id);
        if (!$popup) {
            return redirect()->back()->with('error', 'Popup not found');
        }

        $files = $popup->files;
        // return $project->files;
        foreach ($files as $file) {
            FileHelper::deleteFile($file->id);
        }

        $popup->delete();
        return redirect()->route('admin.popup.list')->with('success', 'Popup deleted successfully');
    }
}
