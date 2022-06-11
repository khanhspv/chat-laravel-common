@extends('layouts.app')
@section('css')
    <style>
        body {
            background: #eee;
            overflow-x: hidden;
            overflow-y: visible;
        }

        .chat-list {
            padding: 0;
            font-size: .8rem;
        }

        .chat-list li {
            margin-bottom: 10px;
            overflow: auto;
            color: #ffffff;
        }

        .chat-list .chat-img {
            float: left;
            width: 48px;
        }

        .chat-list .chat-img img {
            -webkit-border-radius: 50px;
            -moz-border-radius: 50px;
            border-radius: 50px;
            width: 100%;
        }

        .chat-list .chat-message {
            -webkit-border-radius: 50px;
            -moz-border-radius: 50px;
            border-radius: 50px;
            background: #5a99ee;
            display: inline-block;
            padding: 10px 20px;
            position: relative;
        }

        .chat-list .chat-message:before {
            content: "";
            position: absolute;
            top: 15px;
            width: 0;
            height: 0;
        }

        .chat-list .chat-message h5 {
            margin: 0 0 5px 0;
            font-weight: 600;
            line-height: 100%;
            font-size: .9rem;
        }

        .chat-list .chat-message p {
            line-height: 18px;
            margin: 0;
            padding: 0;
        }

        .chat-list .chat-body {
            margin-left: 20px;
            float: left;
            width: 70%;
        }

        .chat-list .in .chat-message:before {
            left: -12px;
            border-bottom: 20px solid transparent;
            border-right: 20px solid #5a99ee;
        }

        .chat-list .out .chat-img {
            float: right;
        }

        .chat-list .out .chat-body {
            float: right;
            margin-right: 20px;
            text-align: right;
        }

        .chat-list .out .chat-message {
            background: #fc6d4c;
        }

        .chat-list .out .chat-message:before {
            right: -12px;
            border-bottom: 20px solid transparent;
            border-left: 20px solid #fc6d4c;
        }

        .card .card-header:first-child {
            -webkit-border-radius: 0.3rem 0.3rem 0 0;
            -moz-border-radius: 0.3rem 0.3rem 0 0;
            border-radius: 0.3rem 0.3rem 0 0;
        }

        .card .card-header {
            background: #17202b;
            border: 0;
            font-size: 1rem;
            padding: .65rem 1rem;
            position: relative;
            font-weight: 600;
            color: #ffffff;
        }

        .content {
            margin-top: 40px;
        }

        #list-chat {
            height: 500px;
            overflow-x: hidden;
            overflow-y: auto;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"> Chat Room </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- @dd($roomId); --}}

                        <ul class="chat-list" id="list-chat">
                            {{-- <li class="in">
                                <div class="chat-img">
                                    <img alt="Avtar" src="https://bootdey.com/img/Content/avatar/avatar1.png">
                                </div>
                                <div class="chat-body">
                                    <div class="chat-message">
                                        <h5>Jimmy Willams</h5>
                                        <p>Raw denim heard of them tofu master cleanse</p>
                                    </div>
                                </div>
                            </li>

                            <li class="out">
                                <div class="chat-img">
                                    <img alt="Avtar" src="https://bootdey.com/img/Content/avatar/avatar6.png">
                                </div>
                                <div class="chat-body">
                                    <div class="chat-message">
                                        <h5>Serena</h5>
                                        <p>Next level veard</p>
                                    </div>
                                </div>
                            </li> --}}
                        </ul>

                        <div id="formSendMsg">
                            <input type="hidden" name="id" value="{{ $roomId['roomId'] }}">
                            <input type="hidden" name="user" value="{{ auth()->user()->id }}">
                            <input type="text" class="form-control" placeholder="">
                        </div>

                        <button type="button" onclick="sendMsg()" id="seed" class="btn btn-outline-primary">Seed</button>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <script>
        $(document).ready(function() {
            lodingMsg()
            // Thiết lập thời gian thực vòng lặp 1 giây
            setInterval(function() {
                lodingMsg()
            }, 30000);

            $('#list-chat').click(function(){
                $('html, body').animate({scrollTop:$(document).height()}, 'slow');
                return false;
            });

            tobottom();

        });

        function tobottom(){
            $('#list-chat').animate({
            scrollTop: (1E10)
            }, 800);
        }

        function lodingMsg() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: "{{ route('loading-msg') }}?roomId={{ $roomId['roomId'] }}",
                success: function(data) {
                    $('#list-chat').empty();
                    var arr = data
                    $.each(arr, function(date, messageGroup) {
                        $.each(messageGroup, function(key, message) {
                            $('#list-chat').append(message)
                        })
                    })
                }
            });
        }

        function sendMsg() {
            $room_id = $("input[name='id']").val();
            $user = $("input[name='user']").val();
            $body_msg = $('#formSendMsg input[type="text"]').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('seed-msg') }}',
                type: 'POST',
                data: {
                    body_msg: $body_msg,
                    room_id: $room_id,
                    user: $user
                },
                cache: false,
                success: function(data) {
                    lodingMsg()
                    tobottom()
                    $('#formSendMsg input[type="text"]').val(''); // làm trống thanh trò chuyện
                }
            });
        }



        // function sendMsg() {
        //     // Khai ba1oca1c biến trong form
        //     $room_id = $("input[name='id']").val();
        //     $user = $("input[name='user']").val();
        //     $body_msg = $('#formSendMsg input[type="text"]').val();

        //     // Gửi dữ liệu
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: '{{ route('seed-msg') }}',
        //         type: 'POST', // phương thức
        //         // dữ liệu
        //         data: {
        //             body_msg: $body_msg,
        //             room_id: $room_id,
        //             user: $user
        //         },
        //         cache: false,
        //         success: function(data) {
        //             if(data.msg.user_send_1 != null ){ 
        //                 if(data.msg.user_send_1 == data.user_seed){
        //                     $('#list-chat').append(`
    //                         <li class="out">
    //                             <div class="chat-img">
    //                                 <img alt="Avtar" src="https://bootdey.com/img/Content/avatar/avatar6.png">
    //                             </div>
    //                             <div class="chat-body">
    //                                 <div class="chat-message">
    //                                     <h5>Serena</h5>
    //                                     <p> ${data.msg.content} </p>
    //                                 </div>
    //                             </div>
    //                         </li>
    //                     `);
        //                 }else{
        //                     $('#list-chat').append(`
    //                         <li class="in">
    //                             <div class="chat-img">
    //                                 <img alt="Avtar" src="https://bootdey.com/img/Content/avatar/avatar1.png">
    //                             </div>
    //                             <div class="chat-body">
    //                                 <div class="chat-message">
    //                                     <h5>Jimmy Willams</h5>
    //                                     <p> ${data.msg.content} </p>
    //                                 </div>
    //                             </div>
    //                         </li>
    //                     `);
        //                 }

        //             }else{
        //                 if(data.msg.user_send_1 != data.user_seed){
        //                     $('#list-chat').append(`
    //                         <li class="in">
    //                             <div class="chat-img">
    //                                 <img alt="Avtar" src="https://bootdey.com/img/Content/avatar/avatar1.png">
    //                             </div>
    //                             <div class="chat-body">
    //                                 <div class="chat-message">
    //                                     <h5>Jimmy Willams</h5>
    //                                     <p> ${data.msg.content} </p>
    //                                 </div>
    //                             </div>
    //                         </li>
    //                     `);
        //                 }else{
        //                     $('#list-chat').append(`
    //                         <li class="out">
    //                             <div class="chat-img">
    //                                 <img alt="Avtar" src="https://bootdey.com/img/Content/avatar/avatar6.png">
    //                             </div>
    //                             <div class="chat-body">
    //                                 <div class="chat-message">
    //                                     <h5>Serena</h5>
    //                                     <p> ${data.msg.content} </p>
    //                                 </div>
    //                             </div>
    //                         </li>
    //                     `);
        //                 }
        //             }

        //             $('#formSendMsg input[type="text"]').val(''); // làm trống thanh trò chuyện
        //         }
        //     });
        // }

        // function lodingMsg() {
        //     $.ajax({
        //         type: 'GET',
        //         url: "{{ route('loading-msg') }}?roomId={{ $roomId['roomId'] }}",
        //         success: function(data) {
        //             data.msg.forEach(e => {
        //                 if(e.user_send_1 != null){
        //                     if(e.user_send_1 == data.user_seed){
        //                         $('#list-chat').append(`
    //                             <li class="out">
    //                                 <div class="chat-img">
    //                                     <img alt="Avtar" src="https://bootdey.com/img/Content/avatar/avatar6.png">
    //                                 </div>
    //                                 <div class="chat-body">
    //                                     <div class="chat-message">
    //                                         <h5>Serena</h5>
    //                                         <p>${e.content}</p>
    //                                     </div>
    //                                 </div>
    //                             </li>
    //                         `);
        //                     }else{
        //                         $('#list-chat').append(`
    //                             <li class="in">
    //                                 <div class="chat-img">
    //                                     <img alt="Avtar" src="https://bootdey.com/img/Content/avatar/avatar1.png">
    //                                 </div>
    //                                 <div class="chat-body">
    //                                     <div class="chat-message">
    //                                         <h5>Jimmy Willams</h5>
    //                                         <p>${e.content}</p>
    //                                     </div>
    //                                 </div>
    //                             </li>
    //                         `);
        //                     }

        //                 }else{
        //                     if(e.user_send_1 != data.user_seed){
        //                          $('#list-chat').append(`
    //                             <li class="in">
    //                                 <div class="chat-img">
    //                                     <img alt="Avtar" src="https://bootdey.com/img/Content/avatar/avatar1.png">
    //                                 </div>
    //                                 <div class="chat-body">
    //                                     <div class="chat-message">
    //                                         <h5>Jimmy Willams</h5>
    //                                         <p>${e.content}</p>
    //                                     </div>
    //                                 </div>
    //                             </li>
    //                         `);
        //                     }else{
        //                         $('#list-chat').append(`
    //                             <li class="out">
    //                                 <div class="chat-img">
    //                                     <img alt="Avtar" src="https://bootdey.com/img/Content/avatar/avatar6.png">
    //                                 </div>
    //                                 <div class="chat-body">
    //                                     <div class="chat-message">
    //                                         <h5>Serena</h5>
    //                                         <p>${e.content}</p>
    //                                     </div>
    //                                 </div>
    //                             </li>
    //                         `);
        //                     } 
        //                 }
        //             });
        //         }
        //     });
        // }




        // Bắt sự kiện gõ phím enter trong thanh trò chuyện
        $('#formSendMsg input[type="text"]').keypress(function() {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                sendMsg();
            }
        });

        // Bắt sự kiện click vào thanh trò chuyện
        // $('#formSendMsg input[type="text"]').click(function(e) {
        //     // Kéo hết thanh cuộn trình duyệt đến cuối
        //     window.scrollBy(0, $(document).height());
        // });
    </script>
@stop
