<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Entities\User;

class UserController extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Display user list
     */
    public function index()
    {
        // Get all users with their groups
        $users = $this->userModel->findAll();

        // Add group info to each user
        foreach ($users as $user) {
            $user->groups = $user->getGroups();
        }

        $data = [
            'title' => 'Manajemen User',
            'users' => $users
        ];

        return view('admin/user/index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah User',
            'validation' => $this->validation,
            'groups' => $this->getAvailableGroups()
        ];

        return view('admin/user/create', $data);
    }

    /**
     * Store new user
     */
    public function store()
    {
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'group'    => 'required|in_list[admin,user]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            // Create new user entity
            $user = new User([
                'username' => $this->request->getPost('username'),
                'active'   => $this->request->getPost('active') ? 1 : 0,
            ]);

            // Insert user
            $this->userModel->insert($user);
            $userId = $this->userModel->getInsertID();

            // Retrieve the user
            $user = $this->userModel->findById($userId);

            // Set email and password
            $user->setEmail($this->request->getPost('email'));
            $user->setPassword($this->request->getPost('password'));

            // Save identity
            $this->userModel->save($user);

            // Add to selected group
            $group = $this->request->getPost('group');
            $user->addGroup($group);

            return redirect()->to('/admin/user')->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            log_message('error', 'Error creating user: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            return redirect()->to('/admin/user')->with('error', 'User tidak ditemukan');
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'userGroups' => $user->getGroups(),
            'validation' => $this->validation,
            'groups' => $this->getAvailableGroups()
        ];

        return view('admin/user/edit', $data);
    }

    /**
     * Update user
     */
    public function update($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            return redirect()->to('/admin/user')->with('error', 'User tidak ditemukan');
        }

        // Check if email changed
        $emailChanged = $user->email !== $this->request->getPost('email');

        $rules = [
            'username' => "required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username,id,{$id}]",
            'group'    => 'required|in_list[admin,user]',
        ];

        // Only validate email uniqueness if it changed
        if ($emailChanged) {
            $rules['email'] = 'required|valid_email|is_unique[auth_identities.secret]';
        } else {
            $rules['email'] = 'required|valid_email';
        }

        // Password is optional on update
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[8]';
            $rules['password_confirm'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            // Update basic user info
            $user->username = $this->request->getPost('username');
            $user->active = $this->request->getPost('active') ? 1 : 0;

            // Update email if changed
            if ($emailChanged) {
                $user->setEmail($this->request->getPost('email'));
            }

            // Update password if provided
            if ($this->request->getPost('password')) {
                $user->setPassword($this->request->getPost('password'));
            }

            // Save user
            $this->userModel->save($user);

            // Update group - remove from all groups first
            $currentGroups = $user->getGroups();
            foreach ($currentGroups as $group) {
                $user->removeGroup($group);
            }

            // Add to new group
            $newGroup = $this->request->getPost('group');
            $user->addGroup($newGroup);

            return redirect()->to('/admin/user')->with('success', 'User berhasil diperbarui');
        } catch (\Exception $e) {
            log_message('error', 'Error updating user: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri'
            ]);
        }

        try {
            // Delete user (Shield will handle related data)
            $this->userModel->delete($id, true);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleActive($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        // Prevent deactivating own account
        if ($user->id === auth()->id()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak dapat menonaktifkan akun sendiri'
            ]);
        }

        try {
            $user->active = $user->active ? 0 : 1;
            $this->userModel->save($user);

            return $this->response->setJSON([
                'success' => true,
                'message' => $user->active ? 'User berhasil diaktifkan' : 'User berhasil dinonaktifkan',
                'active' => $user->active
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengubah status user'
            ]);
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        $newPassword = $this->request->getPost('new_password');

        if (!$newPassword || strlen($newPassword) < 8) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Password minimal 8 karakter'
            ]);
        }

        try {
            $user->setPassword($newPassword);
            $this->userModel->save($user);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Password berhasil direset'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mereset password'
            ]);
        }
    }

    /**
     * Get available groups
     */
    protected function getAvailableGroups(): array
    {
        return [
            'admin' => [
                'title' => 'Administrator',
                'description' => 'Full access ke semua fitur'
            ],
            'user' => [
                'title' => 'User',
                'description' => 'Akses terbatas untuk stock opname'
            ]
        ];
    }

    /**
     * Get user data for DataTables
     */
    public function getData()
    {
        $request = service('request');
        $draw = $request->getPost('draw');
        $start = $request->getPost('start');
        $length = $request->getPost('length');
        $searchValue = $request->getPost('search')['value'];

        $db = \Config\Database::connect();

        // Build base query
        $builder = $db->table('users u');
        $builder->select('u.id, u.username, u.active, u.created_at, u.last_active, ai.secret as email, agu.group');
        $builder->join('auth_identities ai', 'ai.user_id = u.id AND ai.type = "email_password"', 'left');
        $builder->join('auth_groups_users agu', 'agu.user_id = u.id', 'left');

        // Total records
        $totalRecords = $db->table('users')->countAllResults();

        // Search
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('u.username', $searchValue)
                ->orLike('ai.secret', $searchValue)
                ->orLike('agu.group', $searchValue)
                ->groupEnd();
        }

        // Filtered records
        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults(false);

        // Get data
        $builder->orderBy('u.created_at', 'DESC');
        $users = $builder->limit($length, $start)->get()->getResultArray();

        $data = [];
        $no = $start + 1;
        foreach ($users as $user) {
            $statusBadge = $user['active']
                ? '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Aktif</span>'
                : '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Nonaktif</span>';

            $groupBadge = $user['group'] === 'admin'
                ? '<span class="px-2 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">Admin</span>'
                : '<span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">User</span>';

            $lastActive = $user['last_active']
                ? date('d M Y H:i', strtotime($user['last_active']))
                : 'Belum pernah';

            $row = [
                'no' => $no++,
                'username' => esc($user['username']),
                'email' => esc($user['email'] ?? '-'),
                'group' => $groupBadge,
                'status' => $statusBadge,
                'last_active' => $lastActive,
                'action' => $this->getActionButtons($user)
            ];

            $data[] = $row;
        }

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Generate action buttons HTML
     */
    protected function getActionButtons(array $user): string
    {
        $currentUserId = auth()->id();
        $isOwnAccount = $user['id'] == $currentUserId;

        $buttons = '
            <div class="flex space-x-2">
                <a href="' . base_url('admin/user/edit/' . $user['id']) . '" 
                   class="px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700" 
                   title="Edit">
                    <i class="fas fa-edit"></i>
                </a>';

        if (!$isOwnAccount) {
            $buttons .= '
                <button type="button" 
                        class="px-2 py-1 text-xs font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600 btn-toggle-active" 
                        data-id="' . $user['id'] . '"
                        data-active="' . $user['active'] . '"
                        title="' . ($user['active'] ? 'Nonaktifkan' : 'Aktifkan') . '">
                    <i class="fas fa-' . ($user['active'] ? 'ban' : 'check') . '"></i>
                </button>
                <button type="button" 
                        class="px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 btn-delete" 
                        data-id="' . $user['id'] . '"
                        data-username="' . esc($user['username']) . '"
                        title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>';
        }

        $buttons .= '</div>';

        return $buttons;
    }
}
