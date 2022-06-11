<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\ChatDetail;
use App\Models\User;
use App\Services\ChatServices;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $chatServices;

    public function __construct(ChatServices $chatServices)
    {
        $this->middleware('auth');
        $this->chatServices = $chatServices;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::all();
        return view('home', compact('users'));
    }

    public function getRoom(Request $request)
    {
        $data = $request->all();
        $roomId = $this->chatServices->getRoom($data);
        return view('detail_chat', compact('roomId'));

    }

    public function seedMsg(Request $request)
    {
        $data = $request->all();
        $msg = $this->chatServices->sendMsg($data);
        $rs = $this->chatServices->roomIdChat($data['room_id']);
        return response()->json([
            'msg' => $msg,
            'user_seed'=>$rs->seed
        ]);
    }

    // public function loadingMsg(Request $request)
    // {
    //     $data = $request->all();
    //     // dd($roomId);
    //     $chatDetail = ChatDetail::where('room_id', $data['roomId'])
    //         ->orderBy('created_at', 'ASC')->get();
    //     $rs = ChatRoom::find($data['roomId']);

    //     return response()->json(
    //         [
    //             'msg' => $chatDetail,
    //             'user_seed'=>$rs->seed
    //         ]
    //     );
    //     // dd($chatDetail->toJson());
    // }

    public function loadingMsg(Request $request)
    {
        $data = $request->all();

        $chatDetail = $this->chatServices->loadingMsg($data);

        return response()->json(
            [$chatDetail]
        );
        // dd($chatDetail->toJson());
    }

}
