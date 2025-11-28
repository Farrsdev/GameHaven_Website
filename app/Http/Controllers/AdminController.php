<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Game;
use App\Models\Transactions;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Data statistik real
        $totalUsers = User::count();
        $totalGames = Game::count();
        $totalTransactions = Transactions::count();
        
        // Total revenue dari semua transaksi
        $totalRevenue = Transactions::sum('total_price');
        
        // Revenue bulan ini
        $currentMonthRevenue = Transactions::whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->sum('total_price');
            
        // Revenue bulan lalu
        $lastMonthRevenue = Transactions::whereYear('date', Carbon::now()->subMonth()->year)
            ->whereMonth('date', Carbon::now()->subMonth()->month)
            ->sum('total_price');
            
        // Persentase perubahan revenue
        $revenueChange = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;
            
        // Game terpopuler (berdasarkan jumlah pembelian)
        $popularGames = TransactionDetail::select('game_id', DB::raw('SUM(qty) as total_sold'))
            ->with('game')
            ->groupBy('game_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
            
        // Transaksi terbaru
        $recentTransactions = Transactions::with(['user', 'details.game'])
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();
            
        // Data untuk chart (transaksi 7 hari terakhir)
        $weeklyTransactions = Transactions::select(
                DB::raw('DATE(date) as date'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(total_price) as daily_revenue')
            )
            ->where('date', '>=', Carbon::now()->subDays(7))
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalGames',
            'totalTransactions',
            'totalRevenue',
            'currentMonthRevenue',
            'revenueChange',
            'popularGames',
            'recentTransactions',
            'weeklyTransactions'
        ));
    }
    
    public function getChartData()
    {
        $monthlyData = Transactions::select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(*) as transactions')
            )
            ->where('date', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        return response()->json($monthlyData);
    }
}