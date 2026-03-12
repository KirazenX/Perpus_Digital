<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class ManajemenPengguna extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = '';

    // Form fields
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $NamaLengkap = '';
    public string $Alamat = '';
    public string $role = 'petugas';

    protected function rules(): array
    {
        $emailRule = $this->editingId
            ? 'required|email|unique:users,email,' . $this->editingId
            : 'required|email|unique:users,email';
        $passwordRule = $this->editingId ? 'nullable|min:8' : 'required|min:8';

        return [
            'name' => 'required|string|max:255',
            'email' => $emailRule,
            'password' => $passwordRule,
            'NamaLengkap' => 'required|string|max:255',
            'Alamat' => 'nullable|string',
            'role' => 'required|in:administrator,petugas,peminjam',
        ];
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->role = 'petugas';
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->NamaLengkap = $user->NamaLengkap ?? '';
        $this->Alamat = $user->Alamat ?? '';
        $this->role = $user->role;
        $this->password = '';
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'NamaLengkap' => $this->NamaLengkap,
            'Alamat' => $this->Alamat,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editingId) {
            User::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Data pengguna berhasil diperbarui.');
        } else {
            $data['email_verified_at'] = now();
            User::create($data);
            session()->flash('success', 'Pengguna baru berhasil ditambahkan.');
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            return;
        }
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('success', 'Status pengguna berhasil diubah.');
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->NamaLengkap = '';
        $this->Alamat = '';
        $this->role = 'petugas';
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::when($this->search, fn($q) =>
                $q->where('NamaLengkap', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
            )
            ->when($this->roleFilter, fn($q) => $q->where('role', $this->roleFilter))
            ->orderBy('role')
            ->paginate(15);

        return view('livewire.admin.manajemen-pengguna', compact('users'));
    }
}