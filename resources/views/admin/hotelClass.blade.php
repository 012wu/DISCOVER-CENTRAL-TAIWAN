@extends ('admin.layout')
@section('title', '中彰投生活圈/後臺管理/旅宿分類')
@section('content')
{{-- 左上小標 --}}
<div class="admin-breadcrumb">
    <a href="/admin/home">後臺管理</a><span> / </span><a href="#">旅宿分類</a>
</div>
<div class="admin-page-title">旅宿分類</div>
<div class="admin-page-desc">管理所有旅遊旅宿分類</div>
{{-- 下拉式選單＋關鍵字篩選區 --}}
<form method="GET" class="row admin-filter-bar">
    <div class="col-2">
        <input type="text" name="keyword" placeholder="搜尋關鍵字" class="form-control" value="{{ request('keyword') }}"
            onkeyup="setTimeout(() => this.form.submit(), 1000)">
    </div>
    {{-- ms-auto自動推到最右側 --}}
    <div class="col-2 ms-auto">
        <select name="rank" id="rank" class="form-select form-control" onchange="this.form.submit()">
            <option value="排序方式">排序方式</option>
            <option value="分類代碼（小->大）" @selected(request('rank')==='分類代碼（小->大）' )>分類代碼（小->大）</option>
            <option value="名稱 (筆劃少->多)" @selected(request('rank')==='名稱 (筆劃少->多)' )>名稱 (筆劃少->多)</option>
            <option value="名稱 (筆劃多->少)" @selected(request('rank')==='名稱 (筆劃多->少)' )>名稱 (筆劃多->少)</option>
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
        <select class="form-select  ms-3" id="pageSizeSelect" style="width: 120px;height: 30px; font-size: 12px"
            onchange="this.form.submit()" name="pageSize">
            {{-- 預設15筆一頁 --}}
            <option value="15" {{ request('pageSize', 15) == 15 ? 'selected' : '' }}>每頁15筆</option>
            <option value="30" {{ request('pageSize') == 30 ? 'selected' : '' }}>每頁30筆</option>
            <option value="50" {{ request('pageSize') == 50 ? 'selected' : '' }}>每頁50筆</option>
        </select>
    </form>
    {{-- 新增按鈕 --}}
    <button type="button" class="btn btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa-solid fa-plus"></i>
        新增
    </button>
</div>

{{-- 列表 --}}
{{-- 表格:改用 tbody id 讓 JS 可以動態重繪 --}}
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>id</th>
                <th>分類代碼</th>
                <th>旅宿分類代碼名稱</th>
                <th>旅宿分類代碼名稱_網頁</th>
                <th>建立時間</th>
                <th>更新時間</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody id="hotelClassList">
            @if ($list->count() > 0)
            @foreach ($list as $item)
            <tr id="row-{{ $item->id }}">
                <td>{{ $item->id }}</td>
                <td>{{ $item->hotelClassNo }}</td>
                <td>{{ $item->hotelClassName }}</td>
                <td>{{ $item->hotelClassName2 }}</td>
                <td>{{ $item->createTime }}</td>
                <td>{{ $item->updateTime }}</td>
                <td>
                    <button type="button" class="btn btn-row-edit me-2" data-bs-toggle="modal"
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
            <tr>
                <td colspan="7" class="text-center">查無資料</td>
            </tr>
            @endif
        </tbody>
    </table>
    @foreach ($list as $item)
    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">修改旅宿分類</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <form onsubmit="submitEdit(event, {{ $item->id }})">

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
                            <label class="form-label">
                                旅宿分類代碼名稱_網頁
                            </label>

                            <input type="text" name="hotelClassName2" class="form-control"
                                value="{{ $item->hotelClassName2 }}">
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
                <h5 class="modal-title">新增旅宿分類</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- 同樣改用 onsubmit 呼叫 API --}}
                <form onsubmit="submitAdd(event)">
                    <div class="mb-3">
                        <label class="form-label">分類代碼</label>
                        <input type="text" name="hotelClassNo" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">旅宿分類代碼名稱</label>
                        <input type="text" name="hotelClassName" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">旅宿分類代碼名稱_網頁</label>
                        <input type="text" name="hotelClassName2" class="form-control">
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
    // 新增:呼叫 POST /api/hotelClass
    function submitAdd(event) {
        event.preventDefault();
        const form = event.target;
        const data = Object.fromEntries(new FormData(form));

        fetch('/api/hotelClass', {
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

    // 修改:呼叫 PUT /api/hotelClass/{id}
    function submitEdit(event, id) {
        event.preventDefault();
        const form = event.target;
        const data = Object.fromEntries(new FormData(form));

        fetch(`/api/hotelClass/${id}`, {
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

    // 刪除:呼叫 DELETE /api/hotelClass/{id}
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
                fetch(`/api/hotelClass/${id}`, {
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