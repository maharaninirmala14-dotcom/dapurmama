<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Sebelum menghapus atau mengubah data, pastikan tidak ada relasi menu yang terhalang 
        // atau tangani update nama kategori lama terlebih dahulu agar aman bagi data menu.
        Category::where('name', 'Prasmanan')->update([
            'name' => 'Snack Basah',
            'slug' => 'snack-basah',
        ]);

        Category::where('name', 'Snack & Dessert')->update([
            'name' => 'Snack Kering',
            'slug' => 'snack-kering',
        ]);

        // Daftar kategori yang diizinkan mutlak
        $allowedCategories = ['Nasi Box', 'Tumpeng', 'Snack Basah', 'Snack Kering'];

        // HAPUS SEMUA kategori di luar daftar di atas secara permanen dari database
        Category::whereNotIn('name', $allowedCategories)->delete();

        $categories = [
            [
                'name' => 'Nasi Box',
                'description' => 'Paket praktis dan higienis, sangat cocok untuk konsumsi rapat, seminar, acara kantor, maupun syukuran harian.',
            ],
            [
                'name' => 'Tumpeng',
                'description' => 'Nasi tumpeng tradisional dengan hiasan lengkap dan lauk pomahan pilihan, ideal untuk acara ulang tahun, peresmian, dan perayaan penting.',
            ],
            [
                'name' => 'Snack Basah',
                'description' => 'Aneka jajanan pasar tradisional dan kue basah pilihan yang lezat, pas untuk teman santai atau konsumsi coffee break.',
            ],
            [
                'name' => 'Snack Kering',
                'description' => 'Berbagai pilihan kue kering dan camilan gurih berkualitas tinggi, cocok untuk oleh-oleh atau hidangan hari raya.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description']
                ]
            );
        }
    }
}