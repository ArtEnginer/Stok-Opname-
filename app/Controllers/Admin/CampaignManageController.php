<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CampaignModel;
use App\Models\CategoryModel;

class CampaignManageController extends BaseController
{
    protected $campaignModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->campaignModel = new CampaignModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $perPage = 15;
        $page = $this->request->getVar('page') ?? 1;

        $campaigns = $this->campaignModel
            ->select('campaigns.*, categories.name as category_name')
            ->join('categories', 'categories.id = campaigns.category_id')
            ->orderBy('campaigns.created_at', 'DESC')
            ->paginate($perPage, 'default', $page);

        $pager = $this->campaignModel->pager;

        $data = [
            'title' => 'Kelola Campaign',
            'campaigns' => $campaigns,
            'pager' => $pager,
        ];

        return view('admin/campaigns/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Campaign',
            'categories' => $this->categoryModel->getActiveCategories(),
        ];

        return view('admin/campaigns/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'category_id' => 'required|integer',
            'title' => 'required|min_length[10]',
            'short_description' => 'required',
            'description' => 'required',
            'target_amount' => 'required|numeric',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
            'organizer_name' => 'required',
            'organizer_email' => 'permit_empty|valid_email',
            'image' => 'uploaded[image]|max_size[image,2048]|is_image[image]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Upload main image
        $image = $this->request->getFile('image');
        $imageName = null;

        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = time() . '_' . $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/campaigns', $imageName);
        }

        // Upload additional images
        $additionalImages = [];
        $additionalFiles = $this->request->getFileMultiple('additional_images');

        if ($additionalFiles) {
            foreach ($additionalFiles as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    // Validate file
                    if ($file->getSize() <= 2048000 && in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png'])) {
                        $fileName = time() . '_' . $file->getRandomName();
                        $file->move(WRITEPATH . 'uploads/campaigns', $fileName);
                        $additionalImages[] = $fileName;
                    }
                }
            }
        }

        $slug = url_title($this->request->getPost('title'), '-', true) . '-' . time();

        $campaignData = [
            'category_id' => $this->request->getPost('category_id'),
            'title' => $this->request->getPost('title'),
            'slug' => $slug,
            'short_description' => $this->request->getPost('short_description'),
            'description' => $this->request->getPost('description'),
            'target_amount' => $this->request->getPost('target_amount'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'organizer_name' => $this->request->getPost('organizer_name'),
            'organizer_phone' => $this->request->getPost('organizer_phone'),
            'organizer_email' => $this->request->getPost('organizer_email'),
            'image' => $imageName,
            'images' => !empty($additionalImages) ? json_encode($additionalImages) : null,
            'status' => $this->request->getPost('status') ?? 'draft',
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_urgent' => $this->request->getPost('is_urgent') ? 1 : 0,
        ];

        if ($this->campaignModel->insert($campaignData)) {
            return redirect()->to('/admin/campaigns')->with('success', 'Campaign berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan campaign');
    }

    public function edit($id)
    {
        $campaign = $this->campaignModel->find($id);

        if (!$campaign) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Campaign',
            'campaign' => $campaign,
            'categories' => $this->categoryModel->getActiveCategories(),
        ];

        return view('admin/campaigns/edit', $data);
    }

    public function update($id)
    {
        $campaign = $this->campaignModel->find($id);

        if (!$campaign) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validation = \Config\Services::validation();

        $rules = [
            'category_id' => 'required|integer',
            'title' => 'required|min_length[10]',
            'short_description' => 'required',
            'description' => 'required',
            'target_amount' => 'required|numeric',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
            'organizer_name' => 'required',
            'organizer_email' => 'permit_empty|valid_email',
            'image' => 'permit_empty|max_size[image,2048]|is_image[image]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $campaignData = [
            'category_id' => $this->request->getPost('category_id'),
            'title' => $this->request->getPost('title'),
            'short_description' => $this->request->getPost('short_description'),
            'description' => $this->request->getPost('description'),
            'target_amount' => $this->request->getPost('target_amount'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'organizer_name' => $this->request->getPost('organizer_name'),
            'organizer_phone' => $this->request->getPost('organizer_phone'),
            'organizer_email' => $this->request->getPost('organizer_email'),
            'status' => $this->request->getPost('status') ?? 'draft',
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_urgent' => $this->request->getPost('is_urgent') ? 1 : 0,
        ];

        // Upload main image if provided
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Delete old image
            if ($campaign['image'] && file_exists(WRITEPATH . 'uploads/campaigns/' . $campaign['image'])) {
                unlink(WRITEPATH . 'uploads/campaigns/' . $campaign['image']);
            }

            $imageName = time() . '_' . $image->getRandomName();
            $image->move(WRITEPATH . 'uploads/campaigns', $imageName);
            $campaignData['image'] = $imageName;
        }

        // Handle additional images
        $existingImages = !empty($campaign['images']) ? json_decode($campaign['images'], true) : [];

        // Handle deleted images
        $deletedImages = $this->request->getPost('deleted_images');
        if ($deletedImages) {
            foreach ($deletedImages as $deletedImg) {
                // Remove from array
                $existingImages = array_diff($existingImages, [$deletedImg]);
                // Delete physical file
                if (file_exists(WRITEPATH . 'uploads/campaigns/' . $deletedImg)) {
                    unlink(WRITEPATH . 'uploads/campaigns/' . $deletedImg);
                }
            }
        }

        // Upload new additional images
        $additionalFiles = $this->request->getFileMultiple('additional_images');
        if ($additionalFiles) {
            foreach ($additionalFiles as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    // Validate file
                    if ($file->getSize() <= 2048000 && in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png'])) {
                        $fileName = time() . '_' . $file->getRandomName();
                        $file->move(WRITEPATH . 'uploads/campaigns', $fileName);
                        $existingImages[] = $fileName;
                    }
                }
            }
        }

        // Update images field
        $campaignData['images'] = !empty($existingImages) ? json_encode(array_values($existingImages)) : null;

        if ($this->campaignModel->update($id, $campaignData)) {
            return redirect()->to('/admin/campaigns')->with('success', 'Campaign berhasil diupdate');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate campaign');
    }

    public function delete($id)
    {
        $campaign = $this->campaignModel->find($id);

        if (!$campaign) {
            return redirect()->back()->with('error', 'Campaign tidak ditemukan');
        }

        // Delete main image
        if ($campaign['image'] && file_exists(WRITEPATH . 'uploads/campaigns/' . $campaign['image'])) {
            unlink(WRITEPATH . 'uploads/campaigns/' . $campaign['image']);
        }

        // Delete additional images
        if (!empty($campaign['images'])) {
            $additionalImages = json_decode($campaign['images'], true);
            if (is_array($additionalImages)) {
                foreach ($additionalImages as $img) {
                    if (file_exists(WRITEPATH . 'uploads/campaigns/' . $img)) {
                        unlink(WRITEPATH . 'uploads/campaigns/' . $img);
                    }
                }
            }
        }

        if ($this->campaignModel->delete($id)) {
            return redirect()->to('/admin/campaigns')->with('success', 'Campaign berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Gagal menghapus campaign');
    }
}
