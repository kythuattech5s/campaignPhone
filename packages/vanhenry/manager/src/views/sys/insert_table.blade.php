@extends('vh::master')
@section('content')
    <div id="maincontent">
        <div class="listcontent">
            <div class="tab-content ">
                @if(session()->has('success'))
                <p style="line-height: 40px;background: #e96a0c;text-align: center;color: #fff;">
                    {{session()->get('success')}}
                </p>
                @endif
                <form action="{{ $admincp }}/inserttableview" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row boxedit m0" style="margin-top: 8px">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Bảng<span class="count"></span></p>
                                <select class="form-control" name="table_map">
                                    @foreach ($listTableNotInsert as $table)
                                        <option value="{{ $table->name }}">{{ $table->cmt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Tên bảng<span class="count"></span></p>
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Là bảng dịch của bảng<span class="count"></span>
                                </p>
                                <select class="form-control" name="translation_of">
                                    <option value="">--Đây không phải là bảng dịch--</option>
                                    @foreach ($listTableNotTranslations as $table)
                                        <option value="{{ $table->name }}">{{ $table->cmt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Ghi chú<span class="count"></span></p>
                                <input type="text" name="note" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Bảng cha<span class="count"></span></p>
                                <select class="form-control" name="table_parent">
                                    <option value="">Không xác định</option>
                                    @foreach ($listTables as $table)
                                        <option value="{{ $table->name }}">{{ $table->cmt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Bảng con<span class="count"></span></p>
                                <select class="form-control" name="table_child">
                                    <option value="">Không xác định</option>
                                    @foreach ($listTables as $table)
                                        <option value="{{ $table->name }}">{{ $table->cmt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Là Category<span class="count"></span></p>
                                <input type="text" name="is_category" value="0" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Controller<span class="count"></span></p>
                                <input type="text" name="controller" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Model <span style="color: red"> (*)</span> </p>
                                <input type="text" name="model" class="form-control" required="models" value="\App\Models\_MODEL_">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Số bản ghi phân trang Admin<span
                                        class="count"></span></p>
                                <input type="text" name="rpp_admin" value="10" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Số bản ghi Phân trang Frontend<span
                                        class="count"></span></p>
                                <input type="text" name="rpp_frontend" value="10" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Có Insert<span class="count"></span></p>
                                <input type="text" name="has_insert" value="1" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Có Update<span class="count"></span></p>
                                <input type="text" name="has_update" value="1" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Có Delete<span class="count"></span></p>
                                <input type="text" name="has_delete" value="1" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Có Copy<span class="count"></span></p>
                                <input type="text" name="has_copy" value="1" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Có Help<span class="count"></span></p>
                                <input type="text" name="has_help" value="1" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Có Quick Post<span class="count"></span></p>
                                <input type="text" name="has_quickpost" value="0" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Có Tìm kiếm<span class="count"></span></p>
                                <input type="text" name="has_search" value="1" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Kiểu hiển thị<span class="count"></span></p>
                                <select name="type_show" class="form-control">
                                    <option value="_normal">Dạng bình thường</option>
                                    <option value="_config">Dạng cấu hình</option>
                                    <option value="_menu">Dạng Menu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <p class="form-title" for="">Group module<span class="count"></span></p>
                                <select name="group_module" class="form-control">
                                    @foreach ($listGroupModule as $table)
                                        <option value="{{ $table->id }}">{{ $table->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <button type="submit" style="    background: #00923f;text-transform: uppercase;color: #fff;"
                                    class="form-control">Thêm mới</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('vh::static.footer')
    <script type="text/javascript">
        $(function() {
            $("select[name=table_map]").change(function(event) {
                $("input[name=name]").val($(this).find("option:selected").text());
                $("input[name=controller]").val($(this).val() + ".view");
            });
        });
    </script>
@stop