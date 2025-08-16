<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Vessel;

class VesselAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(401, 'Unauthenticated');
        }
        
        // Get vessel from route parameter - handle different route types
        $vesselId = $this->extractVesselId($request);
        
        if (!$vesselId) {
            return $next($request);
        }
        
        // Find the vessel
        $vessel = Vessel::find($vesselId);
        
        if (!$vessel) {
            abort(404, 'Vessel not found');
        }
        
        // Check if user has access to this vessel
        if (!$user->hasSystemAccessToVessel($vessel)) {
            abort(403, 'Access denied to this vessel');
        }
        
        return $next($request);
    }
    
    /**
     * Extract vessel ID from different route parameter types
     */
    private function extractVesselId(Request $request): ?int
    {
        // Direct vessel routes
        if ($vesselId = $request->route('vessel')) {
            return $vesselId;
        }
        
        if ($vesselId = $request->route('id')) {
            return $vesselId;
        }
        
        // Work order routes - extract vessel from work order
        if ($workOrder = $request->route('workOrder')) {
            try {
                $workOrderModel = \App\Models\WorkOrder::find($workOrder);
                if ($workOrderModel && $workOrderModel->equipmentInterval && $workOrderModel->equipmentInterval->equipment) {
                    return $workOrderModel->equipmentInterval->equipment->vessel_id;
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to extract vessel ID from work order', [
                    'work_order_id' => $workOrder,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Task routes - extract vessel from task's work order
        if ($task = $request->route('task')) {
            try {
                $taskModel = \App\Models\WorkOrderTask::find($task);
                if ($taskModel && $taskModel->workOrder && $taskModel->workOrder->equipmentInterval && $taskModel->workOrder->equipmentInterval->equipment) {
                    return $taskModel->workOrder->equipmentInterval->equipment->vessel_id;
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to extract vessel ID from task', [
                    'task_id' => $task,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Equipment routes
        if ($equipment = $request->route('equipment')) {
            try {
                $equipmentModel = \App\Models\Equipment::find($equipment);
                if ($equipmentModel) {
                    return $equipmentModel->vessel_id;
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to extract vessel ID from equipment', [
                    'equipment_id' => $equipment,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Equipment interval routes
        if ($interval = $request->route('interval')) {
            try {
                $intervalModel = \App\Models\EquipmentInterval::find($interval);
                if ($intervalModel && $intervalModel->equipment) {
                    return $intervalModel->equipment->vessel_id;
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to extract vessel ID from equipment interval', [
                    'interval_id' => $interval,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Category routes - extract vessel from category
        if ($category = $request->route('category')) {
            try {
                $categoryModel = \App\Models\Category::find($category);
                if ($categoryModel) {
                    return $categoryModel->vessel_id;
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to extract vessel ID from category', [
                    'category_id' => $category,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return null;
    }
}
