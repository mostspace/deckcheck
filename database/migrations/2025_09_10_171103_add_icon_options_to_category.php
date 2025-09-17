<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $values = [
            'fa-fire-extinguisher','fa-fire','fa-bell','fa-helmet-safety','fa-pump-medical',
            'fa-kit-medical','fa-heart-pulse','fa-prescription-bottle-medical',
            'fa-water-ladder','fa-bottle-water','fa-arrow-up-from-ground-water','fa-life-ring','fa-anchor',
            'fa-ship','fa-ferry','fa-bolt','fa-battery-full','fa-helicopter','fa-tower-broadcast',
            'fa-walkie-talkie','fa-phone-volume','fa-toolbox','fa-screwdriver-wrench','fa-gear','fa-oil-can',
            'fa-mask-ventilator','fa-mask-face','fa-location-dot','fa-lightbulb','fa-car-battery','fa-plug',
            'fa-vest','fa-weight-hanging','fa-person-through-window','fa-door-closed','fa-dharmachakra',
            'fa-smog','fa-suitcase','fa-flag','fa-bullhorn','fa-boxes-stacked','fa-box','fa-user-astronaut',
            'fa-water','fa-person-drowning','fa-gauge-simple','fa-sim-card','fa-compass-drafting',
            'fa-binoculars','fa-circle-half-stroke','fa-satellite-dish','fa-rocket','fa-calendar','fa-print',
            'fa-globe','fa-clipboard','fa-tag','fa-compass','fa-copy','fa-network-wired',
            'fa-triangle-exclamation','fa-gas-pump','fa-bucket','fa-faucet-drip','fa-dumpster-fire',
            'fa-display','fa-hard-drive','fa-fan','fa-arrow-up-from-water-pump','fa-key',
        ];

        // pick a safe fallback that exists in $values
        $fallback = 'fa-tag';

        // 1) Pre-normalize existing data to avoid truncation on ALTER
        DB::table('categories')->whereNull('icon')->update(['icon' => $fallback]);
        DB::table('categories')->whereNotIn('icon', $values)->update(['icon' => $fallback]);

        // For SQLite, we can't modify the column type, so we just ensure data integrity
        // The application will handle validation of icon values
    }

    public function down(): void
    {
        // For SQLite, we can't easily rollback the column modification
        // This is a no-op rollback for SQLite compatibility
    }
};
