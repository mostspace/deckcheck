<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Deficiency;
use App\Models\WorkOrder;
use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the reports index page with all reports and my reports tabs
     */
    public function index()
    {
        $vessel = currentVessel();
        
        // Get all available reports
        $allReports = $this->getAllReports($vessel);
        $myReports = $this->getUserReports(auth()->user(), $vessel);
        
        return view('v2.crew.reports.index', compact('allReports', 'myReports'));
    }

    /**
     * Display analytics tab content
     */
    public function analytics()
    {
        $vessel = currentVessel();
        
        // Get analytics data
        $analytics = $this->getAnalyticsData($vessel);
        
        return view('v2.crew.reports.analytics.index', compact('analytics'));
    }

    /**
     * Display exports tab content
     */
    public function exports()
    {
        $vessel = currentVessel();
        
        // Get available export options
        $exportOptions = $this->getExportOptions($vessel);
        
        return view('v2.crew.reports.exports.index', compact('exportOptions'));
    }

    /**
     * Display my reports
     */
    public function myReports()
    {
        $vessel = currentVessel();
        $user = auth()->user();
        
        // Get user's custom reports
        $reports = $this->getUserReports($user, $vessel);
        
        return view('v2.crew.reports.my-reports.index', compact('reports'));
    }

    /**
     * Display all reports
     */
    public function allReports()
    {
        $vessel = currentVessel();
        
        // Get all available reports
        $reports = $this->getAllReports($vessel);
        
        return view('v2.crew.reports.all-reports.index', compact('reports'));
    }

    /**
     * Generate a specific report
     */
    public function generate(Request $request, $reportType)
    {
        $vessel = currentVessel();
        
        $validated = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'required|in:pdf,excel,csv',
            'filters' => 'nullable|array'
        ]);

        try {
            $report = $this->generateReport($vessel, $reportType, $validated);
            
            return response()->download($report['path'], $report['filename']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    /**
     * Get vessel statistics
     */
    private function getVesselStats($vessel)
    {
        $equipmentCount = Equipment::where('vessel_id', $vessel->id)->count();
        $operationalCount = Equipment::where('vessel_id', $vessel->id)
            ->where('status', 'In Service')
            ->count();
        $deficienciesCount = Deficiency::whereHas('equipment', function($q) use ($vessel) {
            $q->where('vessel_id', $vessel->id);
        })->where('status', 'open')->count();
        $workOrdersCount = WorkOrder::whereHas('equipmentInterval.equipment', function($q) use ($vessel) {
            $q->where('vessel_id', $vessel->id);
        })->where('status', 'scheduled')->count();

        return [
            'equipment_total' => $equipmentCount,
            'equipment_operational' => $operationalCount,
            'deficiencies_open' => $deficienciesCount,
            'work_orders_scheduled' => $workOrdersCount,
            'operational_percentage' => $equipmentCount > 0 ? round(($operationalCount / $equipmentCount) * 100, 1) : 0
        ];
    }

    /**
     * Get analytics data
     */
    private function getAnalyticsData($vessel)
    {
        // Equipment status distribution
        $equipmentStatus = Equipment::where('vessel_id', $vessel->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Deficiencies by age
        $deficienciesByAge = Deficiency::whereHas('equipment', function($q) use ($vessel) {
            $q->where('vessel_id', $vessel->id);
        })
        ->where('status', 'open')
        ->selectRaw('
            CASE 
                WHEN DATEDIFF(NOW(), created_at) < 30 THEN "Under 30 days"
                WHEN DATEDIFF(NOW(), created_at) BETWEEN 30 AND 90 THEN "30-90 days"
                ELSE "Over 90 days"
            END as age_group,
            COUNT(*) as count
        ')
        ->groupBy('age_group')
        ->get();

        // Work orders by status
        $workOrdersByStatus = WorkOrder::whereHas('equipmentInterval.equipment', function($q) use ($vessel) {
            $q->where('vessel_id', $vessel->id);
        })
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get();

        return [
            'equipment_status' => $equipmentStatus,
            'deficiencies_by_age' => $deficienciesByAge,
            'work_orders_by_status' => $workOrdersByStatus
        ];
    }

    /**
     * Get export options
     */
    private function getExportOptions($vessel)
    {
        return [
            'equipment' => [
                'name' => 'Equipment Inventory',
                'description' => 'Export complete equipment inventory with all details',
                'formats' => ['pdf', 'excel', 'csv']
            ],
            'deficiencies' => [
                'name' => 'Deficiencies Report',
                'description' => 'Export all deficiencies with status and age information',
                'formats' => ['pdf', 'excel', 'csv']
            ],
            'work_orders' => [
                'name' => 'Work Orders Report',
                'description' => 'Export work orders with completion status and assignments',
                'formats' => ['pdf', 'excel', 'csv']
            ],
            'maintenance_schedule' => [
                'name' => 'Maintenance Schedule',
                'description' => 'Export upcoming maintenance schedule by category',
                'formats' => ['pdf', 'excel', 'csv']
            ]
        ];
    }

    /**
     * Get user's custom reports
     */
    private function getUserReports($user, $vessel)
    {
        // TODO: Implement custom reports functionality
        return collect([
            [
                'id' => 1,
                'name' => 'Weekly Equipment Status',
                'description' => 'Custom report for weekly equipment status review',
                'created_at' => now()->subDays(3),
                'last_run' => now()->subDays(1)
            ],
            [
                'id' => 2,
                'name' => 'Monthly Deficiencies Summary',
                'description' => 'Monthly summary of all deficiencies and their status',
                'created_at' => now()->subWeeks(2),
                'last_run' => now()->subWeeks(1)
            ]
        ]);
    }

    /**
     * Get all available reports
     */
    private function getAllReports($vessel)
    {
        return collect([
            [
                'id' => 'equipment_inventory',
                'name' => 'Equipment Inventory',
                'description' => 'Complete equipment inventory with specifications and status',
                'category' => 'Inventory',
                'last_updated' => now()->subHours(2)
            ],
            [
                'id' => 'deficiencies_report',
                'name' => 'Deficiencies Report',
                'description' => 'All deficiencies with status, age, and resolution tracking',
                'category' => 'Maintenance',
                'last_updated' => now()->subHours(1)
            ],
            [
                'id' => 'work_orders_summary',
                'name' => 'Work Orders Summary',
                'description' => 'Summary of all work orders with completion status',
                'category' => 'Maintenance',
                'last_updated' => now()->subMinutes(30)
            ],
            [
                'id' => 'maintenance_schedule',
                'name' => 'Maintenance Schedule',
                'description' => 'Upcoming maintenance schedule by equipment category',
                'category' => 'Planning',
                'last_updated' => now()->subHours(4)
            ]
        ]);
    }

    /**
     * Generate a specific report
     */
    private function generateReport($vessel, $reportType, $validated)
    {
        $dateFrom = $validated['date_from'] ?? now()->subMonth();
        $dateTo = $validated['date_to'] ?? now();
        $format = $validated['format'];
        
        $filename = "{$reportType}_report_" . now()->format('Y-m-d_H-i-s') . ".{$format}";
        $path = storage_path("app/reports/{$filename}");
        
        // Ensure reports directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        // TODO: Implement actual report generation based on $reportType
        // This is a placeholder - you would implement actual report generation here
        
        return [
            'path' => $path,
            'filename' => $filename
        ];
    }
}
