<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MemberFileTypes;
use App\Enums\ModelType;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helper\MediaController;
use App\Http\Requests\MemberRequest;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MemberController extends Controller
{

    //member list show or index
    public function index()
    {
        $members = Member::orderByRaw('`order` IS NULL, `order` ASC')->paginate(20);

        return view("admin.member.index", compact("members"));
    }

    //member add page show
    public function add()
    {
        return view("admin.member.member-add");
    }

    //member store
    public function store(Request $request)
    {
        $request->validate([
            "name"=> "required",

            'description' => 'required',
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $member = Member::create([
            'name' => $request->name,
            'designation' => $request->designation ?? null,
            'description' => $request->description ?? null,
            'order' => $request->order ?? null,


        ]);

        if ($request->hasFile('image')) {
            $path = 'uploads/members/images/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $fileSize = $request->file('image')->getSize(); // Get size before moving
            $imageRename = Str::slug($request->name) . "_" . Str::uuid() . "_size_." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($path, $imageRename);

            FileHelper::uploadFile($path . $imageRename, MemberFileTypes::image->value, ModelType::Member->value, $member->id, [
                'name' => $request->name,
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }



        return redirect()->route('admin.member.list')->with('success', 'Member added successfully');
    }

    //member edit page show
    public function edit($id)
    {
        $member = Member::find($id);
        return view("admin.member.member-edit", compact("member"));
    }

    //member update
    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required",

            "description" => "required",
        ]);

        $member = Member::find($id);

        if (!$member) {
            return redirect()->back()->with("error", "Member not found");
        }
        try {
            //code...

        $member->name = $request->name;

        if($request->has('designation')){
            $member->designation = $request->designation;
        }

        $member->description = $request->description;
        if($request->has('order')){
            if(empty($request->order)){
            $member->order = null;
            }else{
                $member->order = intval($request->order);
            }
        }

        $member->save();

        // Update image
        if ($request->hasFile('image')) {
            $oldThumbnail = $member->image;
            if ($oldThumbnail) {
                FileHelper::deleteFile($oldThumbnail->id);
            }

            $path = 'uploads/members/images/';
            $fileSize = $request->file('image')->getSize();
            $fileName = Str::slug($request->name) . "_" . Str::uuid() . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($path, $fileName);

            FileHelper::uploadFile($path . $fileName, MemberFileTypes::image->value, ModelType::Member->value, $member->id, [
                'name' => $request->name,
                'type' => 'image',
                'size' => $fileSize,
            ]);
        }
    } catch (\Throwable $th) {
        //throw $th;
        return redirect()->back()->withInput()->with('error', $th->getMessage());
    }


        return redirect()->route('admin.member.list')->with('success', 'Member updated successfully');
    }

    //delete
    public function delete($id)
    {
        $member = Member::find($id);

        if (!$member) {
          return redirect()->back()->error('Member not found');
        }

        $files = $member->files;

        if($files){
            foreach ($files as $file) {

                FileHelper::deleteFile($file->id);

            }
        }


        $member->delete();

        return redirect()->route('admin.member.list')->with('success', 'Member deleted successfully');

    }

}
