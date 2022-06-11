<?php

namespace App\Services;

use App\Models\ChatRoom;
use App\Models\ChatDetail;
use App\Models\User;
use Illuminate\Http\Request;


class ChatServices
{
    public function getRoom($data)
    {
        $rs = ChatRoom::where('user_id_1', auth()->user()->id)
            ->Where('user_id_2',  $data['id'])->first();


        $rs2 = ChatRoom::Where('user_id_2',  auth()->user()->id)
            ->Where('user_id_1', $data['id'])->first();


        if ($rs || $rs2) {
            // dd('1');
            $roomId = [
                'roomId' => $rs->id ?? $rs2->id,
                'user_id_1' => $rs->user_id_1 ?? $rs2->user_id_1,
                'user_id_2' => $rs->user_id_2 ?? $rs2->user_id_2,
            ];
            // dd($roomId);
            return $roomId;
        }
        $roomId = ChatRoom::create(
            [
                'user_id_1' => auth()->user()->id,
                'user_id_2' =>  $data['id'],
                'seed'      => auth()->user()->id,
            ]
        );

        // dd($room);
        return $roomId;
    }

    public function sendMsg($data)
    {
        $rs = $this->roomIdChat($data['room_id']);

        if ($rs->seed == $data['user']) {
            $msg = ChatDetail::create([
                'user_send_1' => $data['user'],
                'content'     => $data['body_msg'],
                'room_id'     => $data['room_id']
            ]);
            return $msg;
        } else {
            $msg = ChatDetail::create([
                'user_send_2' => $data['user'],
                'content'     => $data['body_msg'],
                'room_id'     => $data['room_id']
            ]);
            return $msg;
        }

        return;
    }

    public function roomIdChat($roomId)
    {
        return ChatRoom::find($roomId);
    }

    public function loadingMsg($data)
    {
        $chatDetail = ChatDetail::where('room_id', $data['roomId'])
            ->orderBy('created_at', 'ASC')->get();
        $rs = ChatRoom::find($data['roomId']);

        $messages = $chatDetail->map(function ($chatDetail) use ($rs) {
            $message = $chatDetail->toArray();;
            if ($message['user_send_1'] != null) {
                $userName = User::where('id', $rs->user_id_1)->first();
                if ($message['user_send_1'] == $rs->seed) {
                    return view('user1')->with([
                        'msg' => $message,
                        'user' => $userName
                    ])->toHtml();
                }
            } else {
                $userName = User::where('id', $rs->user_id_2)->first();
                return view('user2')->with([
                    'msg' => $message,
                    'user' => $userName
                ])->toHtml();
            }
        });
        return $messages;
    }
}
