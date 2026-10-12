<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use App\Services\AprioriService;
use Carbon\Carbon;

class Reports extends Component
{
    public string $reportType = 'orders'; // orders, menus, customers, revenue, apriori, weekly, monthly
    public string $startDate = '';
    public string $endDate = '';

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function updatedReportType($value)
    {
        // Otomatis atur rentang tanggal berdasarkan jenis laporan yang dipilih
        if ($value === 'weekly') {
            $this->startDate = now()->subDays(6)->format('Y-m-d'); // 7 hari terakhir
            $this->endDate = now()->format('Y-m-d');
        } elseif ($value === 'monthly') {
            $this->startDate = now()->startOfMonth()->format('Y-m-d');
            $this->endDate = now()->format('Y-m-d');
        }
    }

    public function exportCsv()
    {
        $filename = 'laporan_' . $this->reportType . '_' . now()->format('Ymd_His') . '.csv';
        $data = $this->getReportData();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $csvContent = implode(',', $data['headers']) . "\n";
        foreach ($data['rows'] as $row) {
            $csvContent .= implode(',', array_map(fn($v) => '"' . str_replace('"', '""', $v) . '"', $row)) . "\n";
        }

        return response()->streamDownload(function () use ($csvContent) {
            echo $csvContent;
        }, $filename, $headers);
    }

    public function getReportData(): array
    {
        return match($this->reportType) {
            'orders', 'weekly', 'monthly' => $this->getOrdersReport(),
            'menus' => $this->getMenusReport(),
            'customers' => $this->getCustomersReport(),
            'revenue' => $this->getRevenueReport(),
            'apriori' => $this->getAprioriReport(),
            default => ['headers' => [], 'rows' => []],
        };
    }

    private function getOrdersReport(): array
    {
        // HANYA menampilkan pesanan yang benar-benar SELESAI
        $orders = Order::with('user')
            ->where('status', 'Selesai')
            ->whereBetween('created_at', [$this->startDate, $this->endDate . ' 23:59:59'])
            ->latest()->get();

        $headers = ['Kode Pesanan', 'Customer', 'Tanggal Pesan', 'Total', 'Status'];
        $rows = $orders->map(fn($o) => [
            $o->order_code, 
            $o->user->name ?? '-', 
            optional($o->order_date)->format('d/m/Y') ?? $o->created_at->format('d/m/Y'),
            $o->total_price, 
            $o->status
        ])->toArray();

        return compact('headers', 'rows', 'orders');
    }

    private function getMenusReport(): array
    {
        $menus = Menu::withCount('orderDetails as total_ordered')->with('category')->get();
        $headers = ['Nama Menu', 'Kategori', 'Harga', 'Total Dipesan', 'Favorit'];
        $rows = $menus->map(fn($m) => [
            $m->name, $m->category->name ?? '-', $m->price, $m->total_ordered, $m->is_favorite ? 'Ya' : 'Tidak'
        ])->toArray();
        return compact('headers', 'rows', 'menus');
    }

    private function getCustomersReport(): array
    {
        $customers = User::where('role', 'customer')->withCount('orders')->get();
        $headers = ['Nama', 'Email', 'No. Telp', 'Total Pesanan', 'Bergabung'];
        $rows = $customers->map(fn($c) => [
            $c->name, $c->email, $c->phone ?? '-', $c->orders_count, $c->created_at->format('d/m/Y')
        ])->toArray();
        return compact('headers', 'rows', 'customers');
    }

    private function getRevenueReport(): array
    {
        $orders = Order::where('status', 'Selesai')
            ->whereBetween('created_at', [$this->startDate, $this->endDate . ' 23:59:59'])
            ->latest()->get();
            
        $headers = ['Kode Pesanan', 'Customer', 'Tanggal', 'Total', 'Status'];
        $rows = $orders->map(fn($o) => [
            $o->order_code, 
            $o->user->name ?? '-', 
            optional($o->order_date)->format('d/m/Y') ?? $o->created_at->format('d/m/Y'), 
            $o->total_price, 
            $o->status
        ])->toArray();
        return compact('headers', 'rows', 'orders');
    }

    private function getAprioriReport(): array
    {
        $service = new AprioriService();
        $results = $service->run();
        $headers = ['Antecedent (Jika)', 'Consequent (Maka)', 'Support', 'Confidence'];
        $rows = [];
        
        if (!empty($results['rules'])) {
            foreach ($results['rules'] as $rule) {
                $antNames = \App\Models\Menu::whereIn('id', $rule['antecedent'])->pluck('name')->implode(', ');
                $consNames = \App\Models\Menu::whereIn('id', $rule['consequent'])->pluck('name')->implode(', ');
                $rows[] = [$antNames, $consNames, round($rule['support']*100,1).'%', round($rule['confidence']*100,1).'%'];
            }
        }
        return compact('headers', 'rows', 'results');
    }

    public function render()
    {
        $reportData = $this->getReportData();
        $reportType = $this->reportType;
        $startDate = $this->startDate;
        $endDate = $this->endDate;

        return view('livewire.admin.reports', compact('reportData', 'reportType', 'startDate', 'endDate'))->layout('layouts.admin');
    }
}