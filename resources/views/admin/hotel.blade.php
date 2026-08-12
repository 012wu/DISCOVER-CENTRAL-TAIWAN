@extends ('admin.layout')
@section('title','中彰投生活圈/後臺管理/旅宿列表')
@section('content')
{{-- 左上小標 --}}
<div class="admin-breadcrumb">
    <a href="/admin/home">後臺管理</a><span> / </span><a href="#">旅宿列表</a>
</div>
<div class="admin-page-title">旅宿列表</div>
<div class="admin-page-desc">管理所有旅遊旅宿資料</div>
{{-- 下拉式選單＋關鍵字篩選區 --}}
<form method="GET" class="row admin-filter-bar">
    <div class="col-2">
        <select name="city" id="city" class="form-select form-control" onchange="this.form.submit()">
            <option value="全部縣市">全部縣市</option>
            <option value="臺中市" @selected(request('city')==='臺中市' )>臺中市</option>
            <option value="彰化縣" @selected(request('city')==='彰化縣' )>彰化縣</option>
            <option value="南投縣" @selected(request('city')==='南投縣' )>南投縣</option>
        </select>
    </div>
    <div class="col-2">
        <select name="hotelClass" id="hotelClassName2" class="form-select form-control"
            onchange="this.form.submit()">
            <option value="熱門分類">熱門分類</option>
            <option value="旅館" @selected(request('hotelClass')==='旅館' )>旅館</option>
            <option value="民宿" @selected(request('hotelClass')==='民宿' )>民宿</option>
            <option value="其他" @selected(request('hotelClass')==='其他' )>其他</option>
        </select>
    </div>
    {{-- 關鍵字篩選：比對名稱/詳細描述/地區/詳細地址(延遲1秒後即時搜尋) --}}
    <div class="col-2">
        <input type="text" name="keyword" placeholder="搜尋關鍵字" class="form-control" value="{{ request('keyword') }}"
            onkeyup="setTimeout(() => this.form.submit(), 1000)">
    </div>
    {{-- ms-auto自動推到最右側 --}}
    <div class="col-2 ms-auto">
        <select name="rank" id="rank" class="form-select" onchange="this.form.submit()">
            <option value="排序方式">排序方式</option>
            <option value="旅宿編號（小->大）" @selected(request('rank')==='旅宿編號（小->大' )>旅宿編號（小->大）</option>
            <option value="名稱 (筆劃少->多)" @selected(request('rank')==='名稱 (筆劃少->多)' )>名稱 (筆劃少->多)</option>
            <option value="最新上架" @selected(request('rank')==='最新上架' )>最新上架</option>
        </select>
    </div>
</form>
<div class="d-flex">
    {{-- 總共筆數 --}}
    <div class="result-count ms-3 mt-2">
        共 <span id="totalCount">{{ $list->total() }}</span> 筆資料
    </div>
    {{-- 筆數變更 --}}
    <form method="GET">
        <select class="form-select ms-3"
            id="pageSizeSelect"
            name="pageSize"
            style="width: 120px; height: 30px; font-size: 12px"
            onchange="this.form.submit()">

            <option value="15" {{ request('pageSize', 15) == 15 ? 'selected' : '' }}>
                每頁15筆
            </option>

            <option value="30" {{ request('pageSize') == 30 ? 'selected' : '' }}>
                每頁30筆
            </option>

            <option value="50" {{ request('pageSize') == 50 ? 'selected' : '' }}>
                每頁50筆
            </option>

        </select>
    </form>
    {{-- 新增按鈕 --}}
    <button type="button" class="btn btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa-solid fa-plus"></i>
        新增
    </button>
</div>
{{-- 列表 --}}
<div class="admin-table-wrap">
    <table class="admin-table">
        {{-- 標題 --}}
        <thead>
            <tr>
                <th>id</th>
                <th>旅宿編號</th>
                <th>旅宿證編號</th>
                <th>名稱</th>
                <th>分類代碼</th>
                <th>旅宿分類代碼名稱</th>
                <th>旅宿分類代碼名稱_網頁</th>
                <th>緯度</th>
                <th>經度</th>
                <th>郵遞區號</th>
                <th>縣市</th>
                <th>地區</th>
                <th>街道地址</th>
                <th>電話</th>
                <th>圖片</th>
                <th>詳細描述</th>
                <th>房間資訊</th>
                <th>總房間數</th>
                <th>最低價格</th>
                <th>最高價格</th>
                <th>建立時間</th>
                <th>更新時間</th>
                <th>操作</th>
            </tr>
        </thead>
        {{-- 內容 --}}
        <tbody id="hotelList">
            @if ($list->count() > 0)
            @foreach ($list as $item)
            <tr id="row-{{ $item->id }}">
                <td>{{ $item->id }}</td>
                <td>{{ $item->hotelID }}</td>
                <td>{{ $item->hotelLicenseNumber }}</td>
                <td>{{ $item->hotelName }}</td>
                <td>{{ $item->hotelClassNo }}</td>
                <td>{{ $item->hotelClassName }}</td>
                <td>{{ $item->hotelClassName2 }}</td>
                <td>{{ $item->positionLat }}</td>
                <td>{{ $item->positionLon }}</td>
                <td>{{ $item->zipCode }}</td>
                <td>{{ $item->city }}</td>
                <td>{{ $item->town }}</td>
                <td>{{ $item->streetAddress }}</td>
                <td>{{ $item->tel }}</td>
                <td>
                    <img src="{{ $item->img1 }}" class="row-thumb" alt="">
                </td>
                <td title="{{ $item->description }}">
                    {{ $item->description && mb_strlen($item->description) > 20 ? mb_substr($item->description, 0, 20) . '...' : $item->description }}
                </td>
                <td>{{ $item->roomInfo}}</td>
                <td>{{ $item->totalRooms}}</td>
                <td>{{ $item->lowestPrice }}</td>
                <td>{{ $item->ceilingPrice }}</td>
                <td>{{ $item->createTime }}</td>
                <td>{{ $item->updateTime }}</td>
                <td>
                    <button type="button" class="btn btn-row-edit" data-bs-toggle="modal"
                        data-bs-target="#editModal{{ $item->id }}">
                        修改
                    </button>

                    <button type="button" class="btn btn-row-delete"
                        onclick="deleteData({{ $item->id }})">
                        刪除
                    </button>
                </td>
            </tr>
            @endforeach
            @else
            {{-- 沒有搜尋結果 --}}
            <tr>
                <td colspan="19" class="text-center">
                    查無資料
                </td>
            </tr>

            @endif
        </tbody>
    </table>
    @foreach ($list as $item)
    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">修改旅宿</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <form onsubmit="submitEdit(event, {{$item ->id}})">

                        <div class="mb-3">
                            <label class="form-label">旅宿編號</label>
                            <input type="text" name="hotelID" class="form-control"
                                value="{{ $item->hotelID }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">旅宿證編號</label>
                            <input type="text" name="hotelLicenseNumber" class="form-control"
                                value="{{ $item->hotelLicenseNumber }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">名稱</label>
                            <input type="text" name="hotelName" class="form-control"
                                value="{{ $item->hotelName }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">分類代碼</label>
                            <input type="text" name="hotelClassNo" class="form-control"
                                value="{{ $item->hotelClassNo }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">旅宿分類代碼名稱</label>
                            <input type="text" name="hotelClassName" class="form-control"
                                value="{{ $item->hotelClassName }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">旅宿分類代碼名稱_new</label>
                            <input type="text" name="hotelClassName2" class="form-control"
                                value="{{ $item->hotelClassName2 }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">緯度</label>
                            <input type="text" name="positionLat" class="form-control"
                                value="{{ $item->positionLat }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">經度</label>
                            <input type="text" name="positionLon" class="form-control"
                                value="{{ $item->positionLon }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">郵遞區號</label>
                            <input type="text" name="zipCode" class="form-control"
                                value="{{ $item->zipCode }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">縣市</label>
                            <input type="text" name="city" class="form-control"
                                value="{{ $item->city }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">地區</label>
                            <input type="text" name="town" class="form-control"
                                value="{{ $item->town }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">街道地址</label>
                            <input type="text" name="streetAddress" class="form-control"
                                value="{{ $item->streetAddress }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">電話</label>
                            <input type="text" name="tel" class="form-control"
                                value="{{ $item->tel }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">圖片</label>
                            <input type="text" name="img1" class="form-control"
                                value="{{ $item->img1 }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">詳細描述</label>
                            <textarea name="description" class="form-control">{{ $item->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">房間資訊</label>
                            <textarea name="roomInfo" class="form-control">{{ $item->roomInfo }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">總房間數</label>
                            <textarea name="totalRooms" class="form-control">{{ $item->totalRooms }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">最低價格</label>
                            <textarea name="lowestPrice" class="form-control">{{ $item->lowestPrice }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">最高價格</label>
                            <textarea name="ceilingPrice" class="form-control">{{ $item->ceilingPrice }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary me-2">
                            儲存
                        </button>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            取消
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
    @endforeach
    {{--分頁--}}
    {{ $list->links()}}
</div>

{{-- 新增 --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增旅宿</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form onsubmit="submitAdd(event)">
                    <div class="mb-3">
                        <label class="form-label">旅宿編號</label>
                        <input type="text" name="hotelID" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">旅宿證編號</label>
                        <input type="text" name="hotelLicenseNumber" class="form-control"
                            value="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">名稱</label>
                        <input type="text" name="hotelName" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">分類代碼</label>
                        <input type="text" name="hotelClassNo" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">旅宿分類代碼名稱</label>
                        <input type="text" name="hotelClassName" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">旅宿分類代碼名稱_new</label>
                        <input type="text" name="hotelClassName2" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">緯度</label>
                        <input type="text" name="positionLat" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">經度</label>
                        <input type="text" name="positionLon" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">郵遞區號</label>
                        <input type="text" name="zipCode" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">縣市</label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">地區</label>
                        <input type="text" name="town" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">街道地址</label>
                        <input type="text" name="streetAddress" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">電話</label>
                        <input type="text" name="tel" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">圖片</label>
                        <input type="text" name="img1" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">詳細描述</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">房間資訊</label>
                        <textarea name="roomInfo" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">總房間數</label>
                        <textarea name="totalRooms" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">最低價格</label>
                        <textarea name="lowestPrice" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">最高價格</label>
                        <textarea name="ceilingPrice" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary me-2">新增</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                </form>

            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
    // 新增:呼叫 POST /api/hotel
    function submitAdd(event) {
        event.preventDefault();
        const form = event.target;
        const data = Object.fromEntries(new FormData(form));
        console.log(data);

        fetch('/api/hotel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    Swal.fire({
                        title: '新增成功',
                        text: '資料已成功新增',
                        icon: 'success',
                        confirmButtonText: '確定'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('新增失敗', result.message || '請檢查輸入內容', 'error');
                }
            })
            .catch(() => {
                Swal.fire('發生錯誤', '請稍後再試', 'error');
            });
    }

    // 修改:呼叫 PUT /api/hotel/{id}
    function submitEdit(event, id) {
        event.preventDefault();
        const form = event.target;
        const data = Object.fromEntries(new FormData(form));

        fetch(`/api/hotel/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    Swal.fire({
                        title: '修改成功',
                        text: '資料已成功修改',
                        icon: 'success',
                        confirmButtonText: '確定'
                    }).then(() => {
                        location.reload();
                    });

                } else {
                    Swal.fire('更新失敗', result.message || '請檢查輸入內容', 'error');
                }
            })
            .catch(() => {
                Swal.fire('發生錯誤', '請稍後再試', 'error');
            });
    }

    // 刪除:呼叫 DELETE /api/hotel/{id}
    function deleteData(id) {
        Swal.fire({
            title: '確定要刪除嗎？',
            text: '刪除後無法復原',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '確定刪除',
            cancelButtonText: '取消'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/api/hotel/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(result => {
                        if (result.success) {
                            document.getElementById('row-' + id).remove();
                            Swal.fire({
                                title: '刪除成功',
                                text: '資料已成功刪除',
                                icon: 'success',
                                confirmButtonText: '確定'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('刪除失敗', result.message || '請稍後再試', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('發生錯誤', '請稍後再試', 'error');
                    });
            }
        });
    }
</script>
@endpush
@endsection