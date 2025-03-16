<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\helper\MediaController;
use App\Http\Requests\MemberRequest;

class MemberController extends Controller
{


    public function members(){
        $members = Member::with('image')->orderByRaw('`order` IS NULL, `order` ASC')->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Members found',
            'data' => $members,
        ], 200);
    }

    public function member($id){
        $member = Member::with('image')->find($id);

        if(!$member){
            return response()->json([
                'status' => 'error',
                'message' => 'Member not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Member found',
            'data' => $member,
        ], 200);
    }


}
