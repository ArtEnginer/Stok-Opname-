<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Entities\User;

class ProfileController extends BaseController
{
    protected $userProvider;

    public function __construct()
    {
        $this->userProvider = auth()->getProvider();
    }

    public function index()
    {
        $user = auth()->user();

        $data = [
            'title' => 'Profile Saya',
            'user' => $user,
        ];

        return view('pages/admin/profile/index', $data);
    }

    public function update()
    {
        $userId = auth()->id();
        $user = $this->userProvider->findById($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        // Validation rules
        $rules = [
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[3]|max_length[30]|regex_match[/\A[a-zA-Z0-9\.]+\z/]|is_unique[users.username,id,' . $userId . ']',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'min_length' => 'Username minimal 3 karakter',
                    'max_length' => 'Username maksimal 30 karakter',
                    'regex_match' => 'Username hanya boleh berisi huruf, angka, dan titik',
                    'is_unique' => 'Username sudah digunakan',
                ],
            ],
            'name' => [
                'label' => 'Nama Lengkap',
                'rules' => 'permit_empty|max_length[255]',
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update user data
        $user->username = $this->request->getPost('username');
        $user->name = $this->request->getPost('name');

        if ($this->userProvider->save($user)) {
            return redirect()->to('/admin/profile')->with('success', 'Profile berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profile');
    }

    public function updateEmail()
    {
        $userId = auth()->id();
        $user = $this->userProvider->findById($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        // Validation rules
        $rules = [
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[auth_identities.secret,user_id,' . $userId . ']',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Format email tidak valid',
                    'is_unique' => 'Email sudah digunakan',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get current email identity
        $identity = $user->getEmailIdentity();

        if ($identity) {
            // Update email using the identity model
            $identityModel = model('CodeIgniter\Shield\Models\UserIdentityModel');

            $identityModel->update($identity->id, [
                'secret' => $this->request->getPost('email')
            ]);

            return redirect()->to('/admin/profile')->with('success', 'Email berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui email');
    }

    public function updatePassword()
    {
        $userId = auth()->id();
        $user = $this->userProvider->findById($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        // Validation rules
        $rules = [
            'current_password' => [
                'label' => 'Password Saat Ini',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password saat ini harus diisi',
                ],
            ],
            'new_password' => [
                'label' => 'Password Baru',
                'rules' => 'required|min_length[4]|max_length[255]',
                'errors' => [
                    'required' => 'Password baru harus diisi',
                    'min_length' => 'Password minimal 4 karakter',
                    'max_length' => 'Password maksimal 255 karakter',
                ],
            ],
            'confirm_password' => [
                'label' => 'Konfirmasi Password',
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Konfirmasi password harus diisi',
                    'matches' => 'Konfirmasi password tidak cocok',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Verify current password
        $currentPassword = $this->request->getPost('current_password');
        $identity = $user->getEmailIdentity();

        if (!$identity || !service('passwords')->verify($currentPassword, $identity->secret2)) {
            return redirect()->back()->with('error', 'Password saat ini tidak sesuai');
        }

        // Update password
        $newPassword = $this->request->getPost('new_password');
        $user->password = $newPassword;

        if ($this->userProvider->save($user)) {
            return redirect()->to('/admin/profile')->with('success', 'Password berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui password');
    }
}
