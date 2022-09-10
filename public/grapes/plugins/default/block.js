export default (editor, opts = {}) => {
    const bm = editor.BlockManager;
    const random = Math.random()
        .toString(36)
        .replace(/[^a-z]+/g, "")
        .substr(0, 5);
    const style = `
        <style>
         .box_table .item_boxgame {
            display: flex;
            justify-content: space-between;
            padding: 0.7rem 0;
            border-bottom: solid thin #aaa9bb;
        }

        .users span.user:before {
            color: #59e0ff;
        }

        .users span.user.busy:before {
            color: #fdda34;
        }

        .users span.user:before {
            content: '\f111';
            font-family: 'FontAwesome';
            margin-right: 0.3571rem;
        }

        .users span {
            display: block;
        }

        .box_table .item_boxgame .arrow {
            font-size: 1.7143rem;
        }

        .users span.active:before {
            color: #64c93b;
        }

        .users span.busy:before {
            color: #ffd832;
        }

        .users span.off:before {
            color: #5ae1ff;
        }

        .box_table .item_boxgame .id,
        .box_table .item_boxgame .time {
            flex: 1;
        }

        .box_table .item_boxgame .users {
            flex: 2;
        }

        .box_table .item_boxgame .arorw {
            flex: 1;
        }

        .box_rank .rank {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.3rem;
        }

        .frm_sendchat {
            background: #fff;
            border-radius: 0;
            display: flex;
            margin-top: 1rem;
            align-items: center;
            justify-content: space-between;
            padding: 0.3rem;
        }

        .box_chat {
            max-height: 14rem;
        }

        .box_chat .item_chat {
            margin-bottom: 1.5px;
        }

        .frm_sendchat * {
            border: transparent;
            background: transparent;
            height: 1.8rem;
        }

        .tabs-link {
            border-bottom: transparent;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }
        </style>
    `;
    bm.add(opts.name, {
        label: `<i class="fa fa-arrows-h"></i>
            <div class="gjs-block-label">
                ${opts.label}
            </div>
        `,
        category: opts.category,
        content: `
        <div class="name_game d-flex flex-wrap justify-content-between">
        <div class="icon">
            <img src="theme/frontend/images/co-tuong.png" class="img-fluid" alt="">
            <span class="name">Cờ tướng</span>
        </div>
        <div class="action">
            <a href="" title="" class="d-inline-block smooth btn_all">Mở bàn mới</a>
            <select name="" class="current_server">
                <option value="">#hn-001</option>
            </select>
        </div>
    </div>
    <ul class="nav nav-tabs tabs-link mt-lg-3 mt-2" id="myTab" role="tablist">
        <li class="nav-item mb-md-0 mb-2" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                role="tab" aria-controls="home" aria-selected="true">Các bàn chơi</button>
        </li>
        <li class="nav-item mb-md-0 mb-2" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                role="tab" aria-controls="profile" aria-selected="false">Liên hệ (12)</button>
        </li>
        <li class="nav-item mb-md-0 mb-2" role="presentation">
            <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button"
                role="tab" aria-controls="messages" aria-selected="false">Trò chuyện (14)</button>
        </li>
        <li class="nav-item mb-md-0 mb-2" role="presentation">
            <button class="nav-link" id="tournaments-tab" data-bs-toggle="tab" data-bs-target="#tournaments"
                type="button" role="tab" aria-controls="settings" aria-selected="false">Các giải đấu</button>
        </li>
        <li class="nav-item mb-md-0 mb-2" role="presentation">
            <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button"
                role="tab" aria-controls="settings" aria-selected="false">Tùy chỉnh</button>
        </li>
        <li class="nav-item mb-md-0 mb-2" role="presentation">
            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button"
                role="tab" aria-controls="settings" aria-selected="false">Tiểu sử</button>
        </li>
        <li class="nav-item mb-md-0 mb-2" role="presentation">
            <button type="button" class="btn btn-primary">#1234</button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="challenge text-center my-lg-3 my-2">
                <div class="welcome">
                    <p class="hello">Bạn phongtran012[1209] Mời bạ vào bàn #1234 (7p) Chấp nhận không?</p>
                    <div class="d-flex flex-wrap aswer mt-2">
                        <a href="" class="btn_all yes" title="">Có</a>
                        <a href="" class="btn_all no" title="">Không</a>
                    </div>
                </div>
            </div>
            <div class="row gx-lg-2">
                <div class="col mb-md-0 mb-3">
                    <div class="box_table">
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user busy">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow"><i class="fa fa-angle-right"
                                    aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>

                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                        <a class="item_boxgame" href="#" title="#180">
                            <div class="id">#180</div>
                            <div class="time">7p</div>
                            <div class="users">
                                <span class="user active">vinh01</span>
                                <span class="user off">bdw4234</span>
                            </div>
                            <span title="" class="d-inline-block smooth arrow align-self-center"><i
                                    class="fa fa-angle-right" aria-hidden="true"></i></span>
                        </a>
                    </div>
                </div>
                <div class="col">
                    <div class="box_rank users">
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user busy">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user busy">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user busy">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user busy">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user busy">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                        <a href="#" class="rank" title="vinh01" data-bs-toggle="modal" data-bs-target="#chat">
                            <span class="user active">vinh01</span>
                            <span class="point">15656</span>
                        </a>
                    </div>
                    <div class="wrapper_chat">
                        <div class="wrapper_list">
                            <div class="box_chat mt-lg-4 mt-3">
                                <span class="close">
                                    <img src="theme/frontend/images/cancel.svg" alt="Đóng">
                                </span>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                            </div>
                        </div>
                        <form action="" class="frm_sendchat" accept-charset="utf-8">
                            <input type="text" placeholder="" name="">
                            <button type="submit">Gửi</button>
                        </form>
                    </div>
                    <span class="icon-chat">
                        <img src="theme/frontend/images/chat.svg" alt="Chat">
                    </span>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <form action="" class="frm_template box my-lg-3 my-2 py-1" accept-charset="utf-8" method=""
                autocomplete="off">
                <input type="text" placeholder="" name="fullname" class="mb-0">
                <button class="btn_all" type="submit">Thêm bạn</button>
            </form>
            <div class="row gx-lg-3 gx-2">
                <div class="col-md-6 col-12">
                    <label for="User01" class="item_block">
                        <span class="checkbox me-2"></span>
                        <span class="avatar me-3">
                            <img src="theme/frontend/images/block-user.jpg" alt="User">
                        </span>
                        <span class="name_user me-2">vinh01</span>
                        <div class="status on">
                            <span>Đang online</span>
                        </div>
                        <a href="" title="" class="mes">
                            <img src="theme/frontend/images/mes.png" alt="Tin nhắn">
                        </a>
                    </label>
                </div>
                <div class="col-md-6 col-12">
                    <label for="User03" class="item_block">
                        <span class="checkbox me-2"></span>
                        <span class="avatar me-3">
                            <img src="theme/frontend/images/block-user.jpg" alt="User">
                        </span>
                        <span class="name_user me-2">vinh01</span>
                        <div class="status off">
                            <span>Lần cuối đăng nhập</span>
                            <span class="time">2021 - 11 - 02 - 4:37</span>
                        </div>
                        <a href="" title="" class="mes">
                            <img src="theme/frontend/images/mes.png" alt="Tin nhắn">
                        </a>
                    </label>
                </div>
                <div class="col-md-6 col-12">
                    <label for="User04" class="item_block">
                        <span class="checkbox me-2"></span>
                        <span class="avatar me-3">
                            <img src="theme/frontend/images/block-user.jpg" alt="User">
                        </span>
                        <span class="name_user me-2">vinh01</span>
                        <div class="status off">
                            <span>Lần cuối đăng nhập</span>
                            <span class="time">2021 - 11 - 02 - 4:37</span>
                        </div>
                        <a href="" title="" class="mes">
                            <img src="theme/frontend/images/mes.png" alt="Tin nhắn">
                        </a>
                    </label>
                </div>
                <div class="col-md-6 col-12">
                    <label for="User05" class="item_block">
                        <span class="checkbox me-2"></span>
                        <span class="avatar me-3">
                            <img src="theme/frontend/images/block-user.jpg" alt="User">
                        </span>
                        <span class="name_user me-2">vinh01</span>
                        <div class="status off">
                            <span>Lần cuối đăng nhập</span>
                            <span class="time">2021 - 11 - 02 - 4:37</span>
                        </div>
                        <a href="" title="" class="mes">
                            <img src="theme/frontend/images/mes.png" alt="Tin nhắn">
                        </a>
                    </label>
                </div>
                <div class="col-md-6 col-12">
                    <label for="User06" class="item_block">
                        <span class="checkbox me-2"></span>
                        <span class="avatar me-3">
                            <img src="theme/frontend/images/block-user.jpg" alt="User">
                        </span>
                        <span class="name_user me-2">vinh01</span>
                        <div class="status on">
                            <span>Đang online</span>
                        </div>
                        <a href="" title="" class="mes">
                            <img src="theme/frontend/images/mes.png" alt="Tin nhắn">
                        </a>
                    </label>
                </div>
                <div class="col-md-6 col-12">
                    <label for="User07" class="item_block">
                        <span class="checkbox me-2"></span>
                        <span class="avatar me-3">
                            <img src="theme/frontend/images/block-user.jpg" alt="User">
                        </span>
                        <span class="name_user me-2">vinh01</span>
                        <div class="status on">
                            <span>Đang online</span>
                        </div>
                        <a href="" title="" class="mes">
                            <img src="theme/frontend/images/mes.png" alt="Tin nhắn">
                        </a>
                    </label>
                </div>
                <div class="col-md-6 col-12">
                    <label for="User08" class="item_block">
                        <span class="checkbox me-2"></span>
                        <span class="avatar me-3">
                            <img src="theme/frontend/images/block-user.jpg" alt="User">
                        </span>
                        <span class="name_user me-2">vinh01</span>
                        <div class="status on">
                            <span>Đang online</span>
                        </div>
                        <a href="" title="" class="mes">
                            <img src="theme/frontend/images/mes.png" alt="Tin nhắn">
                        </a>
                    </label>
                </div>
                <div class="col-md-6 col-12">
                    <label for="User09" class="item_block">
                        <span class="checkbox me-2"></span>
                        <span class="avatar me-3">
                            <img src="theme/frontend/images/block-user.jpg" alt="User">
                        </span>
                        <span class="name_user me-2">vinh01</span>
                        <div class="status on">
                            <span>Đang online</span>
                        </div>
                        <a href="" title="" class="mes">
                            <img src="theme/frontend/images/mes.png" alt="Tin nhắn">
                        </a>
                    </label>
                </div>
                <div class="col-md-6 col-12">
                    <label for="User10" class="item_block">
                        <span class="checkbox me-2"></span>
                        <span class="avatar me-3">
                            <img src="theme/frontend/images/block-user.jpg" alt="User">
                        </span>
                        <span class="name_user me-2">vinh01</span>
                        <div class="status on">
                            <span>Đang online</span>
                        </div>
                        <a href="" title="" class="mes">
                            <img src="theme/frontend/images/mes.png" alt="Tin nhắn">
                        </a>
                    </label>
                </div>
                <div class="col-md-6 col-12">
                    <label for="User11" class="item_block">
                        <span class="checkbox me-2"></span>
                        <span class="avatar me-3">
                            <img src="theme/frontend/images/block-user.jpg" alt="User">
                        </span>
                        <span class="name_user me-2">vinh01</span>
                        <div class="status on">
                            <span>Đang online</span>
                        </div>
                        <a href="" title="" class="mes">
                            <img src="theme/frontend/images/mes.png" alt="Tin nhắn">
                        </a>
                    </label>
                </div>
                <div class="col-md-6 col-12">
                    <label for="User12" class="item_block">
                        <span class="checkbox me-2"></span>
                        <span class="avatar me-3">
                            <img src="theme/frontend/images/block-user.jpg" alt="User">
                        </span>
                        <span class="name_user me-2">vinh01</span>
                        <div class="status on">
                            <span>Đang online</span>
                        </div>
                        <a href="" title="" class="mes">
                            <img src="theme/frontend/images/mes.png" alt="Tin nhắn">
                        </a>
                    </label>
                </div>
                <div class="col-md-6 col-12">
                    <label for="User13" class="item_block">
                        <span class="checkbox me-2"></span>
                        <span class="avatar me-3">
                            <img src="theme/frontend/images/block-user.jpg" alt="User">
                        </span>
                        <span class="name_user me-2">vinh01</span>
                        <div class="status on">
                            <span>Đang online</span>
                        </div>
                        <a href="" title="" class="mes">
                            <img src="theme/frontend/images/mes.png" alt="Tin nhắn">
                        </a>
                    </label>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
            <div class="row talk mt-lg-3 mt-2">
                <div class="col-md-6 mb-md-0 mb-3">
                    <div class="wrapper_chat">
                        <div class="wrapper_list">
                            <div class="box_chat">
                                <span class="close">
                                    <img src="theme/frontend/images/cancel.svg" alt="Đóng">
                                </span>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                                <div class="item_chat">+ bạn bfoi165 (1191) đã vào bàn</div>
                            </div>
                        </div>
                        <form action="" class="frm_sendchat" accept-charset="utf-8">
                            <input type="text" placeholder="" name="">
                            <button type="submit">Gửi</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 mb-md-0 mb-3">
                    <div class="list">
                        <a href="" title="" class="chat_me current_box_chat">
                            <span class="name value">vinh01</span>
                            <span class="status">Đang online</span>
                            <span class="delete">
                                <img src="theme/frontend/images/cancel.svg" alt="Đóng">
                            </span>
                        </a>
                        <a href="" title="" class="chat_me">
                            <span class="name value">vinh02</span>
                            <span class="time_off">2021 - 12 - 05 - 4:37</span>
                            <span class="delete">
                                <img src="theme/frontend/images/cancel.svg" alt="Đóng">
                            </span>
                        </a>
                        <a href="" title="" class="chat_me">
                            <span class="name value">vinh03</span>
                            <span class="status">Đang online</span>
                            <span class="delete">
                                <img src="theme/frontend/images/cancel.svg" alt="Đóng">
                            </span>
                        </a>
                        <a href="" title="" class="chat_me">
                            <span class="name value">vinh04</span>
                            <span class="status">Đang online</span>
                            <span class="delete">
                                <img src="theme/frontend/images/cancel.svg" alt="Đóng">
                            </span>
                        </a>
                    </div>
                    <a href="" title="" class="d-block show_all_chat">
                        Tất cả cuộc trò chuyện
                        <img src="theme/frontend/images/de.png" alt="Hiển thị">
                    </a>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="tournaments" role="tabpanel" aria-labelledby="tournaments-tab">
            <div class="row tabs_in_tab align-items-center mt-lg-3 mt-2 justify-content-between">
                <div class="col-md-4 mb-md-0 mb-2">
                    <ul class="nav nav-tabs tabs-link mr-md-auto mx-lg-0 mx-auto" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="join-tab" data-bs-toggle="tab" data-bs-target="#join"
                                type="button" role="tab" aria-controls="join" aria-selected="true">Sắp diễn ra</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="create-tab" data-bs-toggle="tab"
                                data-bs-target="#create" type="button" role="tab" aria-controls="profile"
                                aria-selected="false">Tổ
                                chức</button>
                        </li>
                    </ul>

                </div>
                <div class="col-md-4 mb-md-0 mb-2">
                    <div class="time_start text-center">
                        Thời gian hiện tại: 19:06
                    </div>
                </div>
                <div class="col-md-4 mb-md-0 mb-2 text-md-end text-center">
                    <a href="" class="create_tournament" title="">Tạo giải đấu</a>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade" id="join" role="tabpanel" aria-labelledby="join-tab">
                    <div class="box_gameplay">
                        <div class="list_tournaments_table mt-lg-3 mt-2">
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>4v, 7p +3gi*</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>4v, 7p +3gi*</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>4v, 7p +3gi*</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>4v, 7p +3gi*</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>4v, 7p +3gi*</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>4v, 7p +3gi*</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>4v, 7p +3gi*</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>4v, 7p +3gi*</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>4v, 7p +3gi*</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>4v, 7p +3gi*</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>4v, 7p +3gi*</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>4v, 7p +3gi*</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                            <div class="row row-cols-md-4 gx-0">
                                <div class="col"><span class="value">2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span>T3.2021-0-12 19:06</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="pagination">
                        <a href="">1</a>
                        <a href="">2</a>
                        <strong>3</strong>
                    </div>
                </div>
                <div class="tab-pane show active" id="create" role="tabpanel" aria-labelledby="create-tab">
                    <div class="box_gameplay">
                        <div class="list_tournaments_table mt-lg-3 mt-2">
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                            <div class="row row-cols-4 gx-0">
                                <div class="col"><span>2021 - 19 - 02</span></div>
                                <div class="col"><span>caubetihon</span></div>
                                <div class="col"><span>11-0-2</span></div>
                                <div class="col"><span class="value">ketqua(13)</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="pagination">
                        <a href="">1</a>
                        <a href="">2</a>
                        <strong>3</strong>
                    </div>
                </div>
            </div>

        </div>
        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
            <div class="row mt-lg-3 mt-2">
                <div class="col-md-6">
                    <form action="" class="frm_checkbox mt-2" accept-charset="utf-8" method="">
                        <label for="option1">
                            <input type="checkbox" name="" id="option1">
                            <span>Từ chối nói chuyện riêng</span>
                        </label>
                        <label for="option2">
                            <input type="checkbox" name="" id="option2">
                            <span>Chỉ nói chuyện với người có tên trong sổ liên lạc</span>
                        </label>
                        <label for="option3">
                            <input type="checkbox" name="" id="option3">
                            <span>Từ chối mọi lời mời</span>
                        </label>
                        <button class="btn_all mt-lg-3 mt-2" type="submit">Đồng ý</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
            <div class="row">
                <div class="col-lg-6 col-sm-8">
                    <form action="" method="get" class="validate pt-lg-4 pt-3" accept-charset="utf-8"
                        autocomplete="off">
                        <label for="avatar" class="mb-2">
                            <img id="output" src="theme/frontend/images/block-user.jpg" alt="Người dùng">
                            <div class="content">
                                <input type="file" id="avatar" class="d-none"
                                    onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])">
                                <span>Chọn file ảnh</span>
                                <p class="show_name">Chưa có file ảnh được chọn</p>
                            </div>
                        </label>
                        <div class="d-flex flex-wrap _row">
                            <input type="text" name="" placeholder="Tên">
                        </div>
                        <div class="d-flex flex-wrap _row">
                            <div class="wrapper w-100">
                                <select name="" class="select_init">
                                    <option value="" selected="" disabled="">Quốc gia</option>
                                    <option value="">Việt Nam</option>
                                    <option value="">Trung Quốc</option>
                                    <option value="">Hàn Quốc</option>
                                </select>
                                <select name="" class="select_init">
                                    <option value="" selected="" disabled="">Năm sinh</option>
                                    <option value="">1992</option>
                                    <option value="">1993</option>
                                    <option value="">1949</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap _row">
                            <input type="text" name="address" placeholder="Địa điểm">
                        </div>
                        <div class="d-flex flex-wrap _row">
                            <input type="text" name="Password" placeholder="Thông tin khác">
                        </div>
                        <button type="submit" class="btn_all mt-lg-3 mt-2">Đồng ý</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        ${style}`,
    });
};
