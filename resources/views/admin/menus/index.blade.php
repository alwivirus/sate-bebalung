@extends('layouts.admin')

@section('title', 'Kelola Menu - Depot Sate Be Ba Lung')
@section('page-title', 'Kelola Menu Restoran (CRUD)')

@section('styles')
<style>
    .menu-table {
        width: 100%;
        border-collapse: collapse;
    }

    .menu-table th {
        background: #F9FAFB;
        padding: 12px 16px;
        font-size: 0.8rem;
        font-weight: 800;
        color: #6B7280;
        text-align: left;
        border-bottom: 2px solid #E5E7EB;
        text-transform: uppercase;
    }

    .menu-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #E5E7EB;
        font-size: 0.88rem;
        vertical-align: middle;
    }

    .menu-thumb-small {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: #F3F4F6;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px solid #E5E7EB;
        font-size: 1.4rem;
    }

    .menu-thumb-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-box {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 480px;
        padding: 24px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-group label {
        display: block;
        font-size: 0.82rem;
        font-weight: 800;
        color: #374151;
        margin-bottom: 4px;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .btn-sm {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        border: none;
        text-decoration: none;
    }

    .btn-edit { background: #DBEAFE; color: #1E40AF; }
    .btn-delete { background: #FEE2E2; color: #991B1B; }
    .btn-toggle { background: #FEF3C7; color: #92400E; }
</style>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <h3 style="font-size: 1.1rem; font-weight: 800;">Daftar Menu & Kategori</h3>
        <p style="font-size: 0.8rem; color: #6B7280;">Tambah, edit harga, dan atur ketersediaan stok makanan/minuman.</p>
    </div>
    <button type="button" class="btn-primary" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i> Tambah Menu Baru
    </button>
</div>

@foreach($categories as $category)
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
        <div style="padding: 14px 20px; background: #F9FAFB; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 1.2rem;">{{ $category->icon }}</span>
            <h4 style="font-size: 0.95rem; font-weight: 800; color: #111827;">{{ $category->name }}</h4>
            <span style="font-size: 0.75rem; background: #E5E7EB; padding: 2px 8px; border-radius: 12px; font-weight: 700;">
                {{ $category->menus->count() }} Menu
            </span>
        </div>

        <table class="menu-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Foto</th>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Status Stok</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($category->menus as $menu)
                    <tr>
                        <td>
                            <div class="menu-thumb-small">
                                <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </td>
                        <td>
                            <strong style="color: #111827;">{{ $menu->name }}</strong>
                            <p style="font-size: 0.78rem; color: #6B7280; margin-top: 2px;">{{ $menu->description ?? '-' }}</p>
                        </td>
                        <td>
                            <strong style="color: #DC2626;">{{ $menu->formatted_price }}</strong>
                        </td>
                        <td>
                            <form action="{{ route('admin.menus.toggle', $menu->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-sm btn-toggle">
                                    @if($menu->is_available)
                                        <i class="fa-solid fa-circle-check" style="color: #059669;"></i> Tersedia
                                    @else
                                        <i class="fa-solid fa-circle-xmark" style="color: #DC2626;"></i> Habis
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 6px;">
                                <button type="button" class="btn-sm btn-edit" onclick='openEditModal(@json($menu))'>
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                
                                <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm btn-delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px; color: #9CA3AF;">Belum ada menu di kategori ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endforeach

<!-- Modal Tambah Menu -->
<div class="modal-backdrop" id="addModal">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 1.1rem; font-weight: 800;">Tambah Menu Baru</h3>
            <button type="button" onclick="closeAddModal()" style="border: none; background: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>

        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" class="form-control" required>
                    @foreach($allCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Sate Kambing Bumbu Kacang" required>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="price" class="form-control" placeholder="Contoh: 45000" required>
            </div>

            <div class="form-group">
                <label>Deskripsi (Opsional)</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi singkat menu..."></textarea>
            </div>

            <div class="form-group">
                <label>Upload Foto (Opsional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 20px;">
                <button type="button" class="btn-sm" style="background: #E5E7EB;" onclick="closeAddModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan Menu</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Menu -->
<div class="modal-backdrop" id="editModal">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 1.1rem; font-weight: 800;">Edit Menu</h3>
            <button type="button" onclick="closeEditModal()" style="border: none; background: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" id="editCategoryId" class="form-control" required>
                    @foreach($allCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" name="name" id="editName" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="price" id="editPrice" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Deskripsi (Opsional)</label>
                <textarea name="description" id="editDescription" class="form-control" rows="2"></textarea>
            </div>

            <div class="form-group">
                <label>Ganti Foto (Opsional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="is_available" id="editIsAvailable" value="1">
                <label for="editIsAvailable" style="margin: 0; cursor: pointer;">Stok Tersedia</label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 20px;">
                <button type="button" class="btn-sm" style="background: #E5E7EB;" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn-primary">Update Menu</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openAddModal() {
        document.getElementById('addModal').style.display = 'flex';
    }

    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }

    function openEditModal(menu) {
        document.getElementById('editForm').action = `/admin/menus/${menu.id}`;
        document.getElementById('editCategoryId').value = menu.category_id;
        document.getElementById('editName').value = menu.name;
        document.getElementById('editPrice').value = Math.floor(menu.price);
        document.getElementById('editDescription').value = menu.description || '';
        document.getElementById('editIsAvailable').checked = menu.is_available ? true : false;
        
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
@endsection
