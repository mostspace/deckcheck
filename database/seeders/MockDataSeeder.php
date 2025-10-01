<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Boarding;
use App\Models\Category;
use App\Models\Deck;
use App\Models\Equipment;
use App\Models\Interval;
use App\Models\Location;
use App\Models\Task;
use App\Models\Vessel;
use Illuminate\Database\Seeder;

class MockDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command?->info('🚀 Starting mock data seeding...');

        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        // Create test data (safe mode - only create if doesn't exist)
        $this->createVessel();
        $this->createBoardings();
        $this->createDecks();
        $this->createLocations();
        $this->createCategories();
        $this->createEquipment();
        $this->createIntervals();
        $this->createTasks();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        $this->command?->info('✅ Mock data seeded successfully!');
    }

    /**
     * Create test vessel
     */
    private function createVessel(): void
    {
        $vesselData = [
            'id' => 1,
            'user_id' => 1,
            'name' => 'Test Yacht',
            'type' => 'MY',
            'flag' => 'Cayman Islands',
            'registry_port' => 'George Town',
            'build_year' => '2020',
            'vessel_make' => 'Lürssen',
            'vessel_size' => 500,
            'vessel_loa' => 50,
            'vessel_lwl' => 45,
            'vessel_beam' => 10,
            'vessel_draft' => 3,
            'official_number' => 'TEST123456',
            'mmsi_number' => '123456789',
            'imo_number' => '987654321',
            'callsign' => 'TEST1',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        Vessel::firstOrCreate(['name' => $vesselData['name']], $vesselData);
    }

    /**
     * Create boarding records (vessel access)
     */
    private function createBoardings(): void
    {
        $boardings = [
            [
                'id' => 1,
                'user_id' => 1,
                'vessel_id' => 1,
                'status' => 'active',
                'is_primary' => true,
                'is_crew' => true,
                'access_level' => 'owner',
                'department' => 'management',
                'role' => 'Owner',
                'crew_number' => 1,
                'joined_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'vessel_id' => 1,
                'status' => 'active',
                'is_primary' => false,
                'is_crew' => true,
                'access_level' => 'admin',
                'department' => 'bridge',
                'role' => 'Captain',
                'crew_number' => 2,
                'joined_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'vessel_id' => 1,
                'status' => 'active',
                'is_primary' => false,
                'is_crew' => true,
                'access_level' => 'crew',
                'department' => 'engineering',
                'role' => 'Chief Engineer',
                'crew_number' => 3,
                'joined_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($boardings as $boarding) {
            Boarding::create($boarding);
        }
    }

    /**
     * Create decks
     */
    private function createDecks(): void
    {
        $decks = [
            ['id' => 1, 'vessel_id' => 1, 'name' => 'Main Deck', 'display_order' => 1],
            ['id' => 2, 'vessel_id' => 1, 'name' => 'Upper Deck', 'display_order' => 2],
            ['id' => 3, 'vessel_id' => 1, 'name' => 'Engine Room', 'display_order' => 3],
            ['id' => 4, 'vessel_id' => 1, 'name' => 'Bridge', 'display_order' => 4],
            ['id' => 5, 'vessel_id' => 1, 'name' => 'Galley', 'display_order' => 5],
            ['id' => 6, 'vessel_id' => 1, 'name' => 'Crew Quarters', 'display_order' => 6],
        ];

        foreach ($decks as $deck) {
            Deck::create(array_merge($deck, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Create locations
     */
    private function createLocations(): void
    {
        $locations = [
            // Main Deck Locations
            ['id' => 1, 'deck_id' => 1, 'name' => 'Port Side', 'description' => 'Port side of main deck', 'display_order' => 1],
            ['id' => 2, 'deck_id' => 1, 'name' => 'Starboard Side', 'description' => 'Starboard side of main deck', 'display_order' => 2],
            ['id' => 3, 'deck_id' => 1, 'name' => 'Forward', 'description' => 'Forward section of main deck', 'display_order' => 3],
            ['id' => 4, 'deck_id' => 1, 'name' => 'Aft', 'description' => 'Aft section of main deck', 'display_order' => 4],
            ['id' => 5, 'deck_id' => 1, 'name' => 'Center', 'description' => 'Center section of main deck', 'display_order' => 5],

            // Upper Deck Locations
            ['id' => 6, 'deck_id' => 2, 'name' => 'Port Side', 'description' => 'Port side of upper deck', 'display_order' => 1],
            ['id' => 7, 'deck_id' => 2, 'name' => 'Starboard Side', 'description' => 'Starboard side of upper deck', 'display_order' => 2],
            ['id' => 8, 'deck_id' => 2, 'name' => 'Forward', 'description' => 'Forward section of upper deck', 'display_order' => 3],
            ['id' => 9, 'deck_id' => 2, 'name' => 'Aft', 'description' => 'Aft section of upper deck', 'display_order' => 4],

            // Engine Room Locations
            ['id' => 10, 'deck_id' => 3, 'name' => 'Port Engine', 'description' => 'Port side engine area', 'display_order' => 1],
            ['id' => 11, 'deck_id' => 3, 'name' => 'Starboard Engine', 'description' => 'Starboard side engine area', 'display_order' => 2],
            ['id' => 12, 'deck_id' => 3, 'name' => 'Generator Room', 'description' => 'Generator and electrical equipment area', 'display_order' => 3],
            ['id' => 13, 'deck_id' => 3, 'name' => 'Pump Room', 'description' => 'Pump and hydraulic equipment area', 'display_order' => 4],

            // Bridge Locations
            ['id' => 14, 'deck_id' => 4, 'name' => 'Navigation Station', 'description' => 'Main navigation and communication equipment', 'display_order' => 1],
            ['id' => 15, 'deck_id' => 4, 'name' => 'Chart Table', 'description' => 'Chart table and navigation tools', 'display_order' => 2],
            ['id' => 16, 'deck_id' => 4, 'name' => 'Radar Console', 'description' => 'Radar and electronic navigation equipment', 'display_order' => 3],

            // Galley Locations
            ['id' => 17, 'deck_id' => 5, 'name' => 'Cooking Area', 'description' => 'Main cooking and food preparation area', 'display_order' => 1],
            ['id' => 18, 'deck_id' => 5, 'name' => 'Storage', 'description' => 'Food and equipment storage area', 'display_order' => 2],
            ['id' => 19, 'deck_id' => 5, 'name' => 'Serving Area', 'description' => 'Food serving and dining preparation area', 'display_order' => 3],

            // Crew Quarters Locations
            ['id' => 20, 'deck_id' => 6, 'name' => 'Captain Cabin', 'description' => 'Captain accommodation', 'display_order' => 1],
            ['id' => 21, 'deck_id' => 6, 'name' => 'Engineer Cabin', 'description' => 'Chief Engineer accommodation', 'display_order' => 2],
            ['id' => 22, 'deck_id' => 6, 'name' => 'Crew Cabin 1', 'description' => 'Crew accommodation room 1', 'display_order' => 3],
            ['id' => 23, 'deck_id' => 6, 'name' => 'Crew Cabin 2', 'description' => 'Crew accommodation room 2', 'display_order' => 4],
        ];

        foreach ($locations as $location) {
            Location::create(array_merge($location, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Create equipment categories
     */
    private function createCategories(): void
    {
        $categories = [
            ['id' => 1, 'vessel_id' => 1, 'name' => 'Engines', 'type' => 'Other', 'icon' => 'fa-fire-extinguisher'],
            ['id' => 2, 'vessel_id' => 1, 'name' => 'Pumps', 'type' => 'Other', 'icon' => 'fa-fire-extinguisher'],
            ['id' => 3, 'vessel_id' => 1, 'name' => 'Navigation Equipment', 'type' => 'Radio & Nav', 'icon' => 'fa-fire-extinguisher'],
            ['id' => 4, 'vessel_id' => 1, 'name' => 'Safety Equipment', 'type' => 'LSA', 'icon' => 'fa-fire-extinguisher'],
            ['id' => 5, 'vessel_id' => 1, 'name' => 'Fire Fighting Equipment', 'type' => 'FFE', 'icon' => 'fa-fire-extinguisher'],
            ['id' => 6, 'vessel_id' => 1, 'name' => 'Deck Equipment', 'type' => 'Deck', 'icon' => 'fa-fire-extinguisher'],
            ['id' => 7, 'vessel_id' => 1, 'name' => 'Galley Equipment', 'type' => 'Other', 'icon' => 'fa-fire-extinguisher'],
            ['id' => 8, 'vessel_id' => 1, 'name' => 'Electrical Systems', 'type' => 'Other', 'icon' => 'fa-fire-extinguisher'],
            ['id' => 9, 'vessel_id' => 1, 'name' => 'HVAC Systems', 'type' => 'Other', 'icon' => 'fa-fire-extinguisher'],
            ['id' => 10, 'vessel_id' => 1, 'name' => 'Communication Equipment', 'type' => 'Radio & Nav', 'icon' => 'fa-fire-extinguisher'],
        ];

        foreach ($categories as $category) {
            Category::create(array_merge($category, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Create equipment items
     */
    private function createEquipment(): void
    {
        $equipment = [
            // Engine Equipment
            ['id' => 1, 'vessel_id' => 1, 'category_id' => 1, 'deck_id' => 3, 'location_id' => 10, 'internal_id' => 'ENG-001', 'name' => 'Port Main Engine', 'manufacturer' => 'Caterpillar', 'model' => 'C32 ACERT', 'serial_number' => 'CAT123456', 'status' => 'In Service'],
            ['id' => 2, 'vessel_id' => 1, 'category_id' => 1, 'deck_id' => 3, 'location_id' => 11, 'internal_id' => 'ENG-002', 'name' => 'Starboard Main Engine', 'manufacturer' => 'Caterpillar', 'model' => 'C32 ACERT', 'serial_number' => 'CAT123457', 'status' => 'In Service'],
            ['id' => 3, 'vessel_id' => 1, 'category_id' => 1, 'deck_id' => 3, 'location_id' => 12, 'internal_id' => 'GEN-001', 'name' => 'Port Generator', 'manufacturer' => 'Onan', 'model' => 'MDKBL', 'serial_number' => 'ONAN123456', 'status' => 'In Service'],
            ['id' => 4, 'vessel_id' => 1, 'category_id' => 1, 'deck_id' => 3, 'location_id' => 12, 'internal_id' => 'GEN-002', 'name' => 'Starboard Generator', 'manufacturer' => 'Onan', 'model' => 'MDKBL', 'serial_number' => 'ONAN123457', 'status' => 'In Service'],

            // Pump Equipment
            ['id' => 5, 'vessel_id' => 1, 'category_id' => 2, 'deck_id' => 3, 'location_id' => 13, 'internal_id' => 'PMP-001', 'name' => 'Bilge Pump Port', 'manufacturer' => 'Rule', 'model' => '2000GPH', 'serial_number' => 'RULE123456', 'status' => 'In Service'],
            ['id' => 6, 'vessel_id' => 1, 'category_id' => 2, 'deck_id' => 3, 'location_id' => 13, 'internal_id' => 'PMP-002', 'name' => 'Bilge Pump Starboard', 'manufacturer' => 'Rule', 'model' => '2000GPH', 'serial_number' => 'RULE123457', 'status' => 'In Service'],
            ['id' => 7, 'vessel_id' => 1, 'category_id' => 2, 'deck_id' => 3, 'location_id' => 13, 'internal_id' => 'PMP-003', 'name' => 'Fresh Water Pump', 'manufacturer' => 'Jabsco', 'model' => 'Par-Max 4', 'serial_number' => 'JAB123456', 'status' => 'In Service'],
            ['id' => 8, 'vessel_id' => 1, 'category_id' => 2, 'deck_id' => 3, 'location_id' => 13, 'internal_id' => 'PMP-004', 'name' => 'Salt Water Pump', 'manufacturer' => 'Jabsco', 'model' => 'Par-Max 4', 'serial_number' => 'JAB123457', 'status' => 'In Service'],

            // Navigation Equipment
            ['id' => 9, 'vessel_id' => 1, 'category_id' => 3, 'deck_id' => 4, 'location_id' => 14, 'internal_id' => 'NAV-001', 'name' => 'GPS Chartplotter', 'manufacturer' => 'Garmin', 'model' => 'GPSMAP 8616', 'serial_number' => 'GAR123456', 'status' => 'In Service'],
            ['id' => 10, 'vessel_id' => 1, 'category_id' => 3, 'deck_id' => 4, 'location_id' => 14, 'internal_id' => 'NAV-002', 'name' => 'Radar', 'manufacturer' => 'Furuno', 'model' => 'DRS4D-NXT', 'serial_number' => 'FUR123456', 'status' => 'In Service'],
            ['id' => 11, 'vessel_id' => 1, 'category_id' => 3, 'deck_id' => 4, 'location_id' => 15, 'internal_id' => 'NAV-003', 'name' => 'Autopilot', 'manufacturer' => 'Raymarine', 'model' => 'EV-400', 'serial_number' => 'RAY123456', 'status' => 'In Service'],
            ['id' => 12, 'vessel_id' => 1, 'category_id' => 3, 'deck_id' => 4, 'location_id' => 16, 'internal_id' => 'NAV-004', 'name' => 'Depth Sounder', 'manufacturer' => 'Garmin', 'model' => 'GSD 26', 'serial_number' => 'GAR123457', 'status' => 'In Service'],

            // Safety Equipment
            ['id' => 13, 'vessel_id' => 1, 'category_id' => 4, 'deck_id' => 1, 'location_id' => 1, 'internal_id' => 'SAF-001', 'name' => 'Life Raft Port', 'manufacturer' => 'Winslow', 'model' => '6-Person', 'serial_number' => 'WIN123456', 'status' => 'In Service'],
            ['id' => 14, 'vessel_id' => 1, 'category_id' => 4, 'deck_id' => 1, 'location_id' => 2, 'internal_id' => 'SAF-002', 'name' => 'Life Raft Starboard', 'manufacturer' => 'Winslow', 'model' => '6-Person', 'serial_number' => 'WIN123457', 'status' => 'In Service'],
            ['id' => 15, 'vessel_id' => 1, 'category_id' => 4, 'deck_id' => 1, 'location_id' => 3, 'internal_id' => 'SAF-003', 'name' => 'EPIRB', 'manufacturer' => 'McMurdo', 'model' => 'SmartFind G8', 'serial_number' => 'MCM123456', 'status' => 'In Service'],
            ['id' => 16, 'vessel_id' => 1, 'category_id' => 4, 'deck_id' => 1, 'location_id' => 4, 'internal_id' => 'SAF-004', 'name' => 'Emergency Flares', 'manufacturer' => 'Orion', 'model' => '12-Pack', 'serial_number' => 'ORI123456', 'status' => 'In Service'],

            // Fire Fighting Equipment
            ['id' => 17, 'vessel_id' => 1, 'category_id' => 5, 'deck_id' => 1, 'location_id' => 5, 'internal_id' => 'FFE-001', 'name' => 'Fire Extinguisher Port', 'manufacturer' => 'Amerex', 'model' => 'B500', 'serial_number' => 'AMR123456', 'status' => 'In Service'],
            ['id' => 18, 'vessel_id' => 1, 'category_id' => 5, 'deck_id' => 1, 'location_id' => 5, 'internal_id' => 'FFE-002', 'name' => 'Fire Extinguisher Starboard', 'manufacturer' => 'Amerex', 'model' => 'B500', 'serial_number' => 'AMR123457', 'status' => 'In Service'],
            ['id' => 19, 'vessel_id' => 1, 'category_id' => 5, 'deck_id' => 3, 'location_id' => 10, 'internal_id' => 'FFE-003', 'name' => 'Engine Room Fire Suppression', 'manufacturer' => 'Fireboy-Xintex', 'model' => 'FM-200', 'serial_number' => 'FBX123456', 'status' => 'In Service'],

            // Deck Equipment
            ['id' => 20, 'vessel_id' => 1, 'category_id' => 6, 'deck_id' => 1, 'location_id' => 1, 'internal_id' => 'DECK-001', 'name' => 'Windlass', 'manufacturer' => 'Lofrans', 'model' => 'Tigres', 'serial_number' => 'LOF123456', 'status' => 'In Service'],
            ['id' => 21, 'vessel_id' => 1, 'category_id' => 6, 'deck_id' => 1, 'location_id' => 2, 'internal_id' => 'DECK-002', 'name' => 'Anchor Chain', 'manufacturer' => 'ACCO', 'model' => '3/8" G4', 'serial_number' => 'ACC123456', 'status' => 'In Service'],
            ['id' => 22, 'vessel_id' => 1, 'category_id' => 6, 'deck_id' => 1, 'location_id' => 3, 'internal_id' => 'DECK-003', 'name' => 'Mooring Lines', 'manufacturer' => 'New England Ropes', 'model' => '3-Strand', 'serial_number' => 'NER123456', 'status' => 'In Service'],

            // Galley Equipment
            ['id' => 23, 'vessel_id' => 1, 'category_id' => 7, 'deck_id' => 5, 'location_id' => 17, 'internal_id' => 'GAL-001', 'name' => 'Refrigerator', 'manufacturer' => 'Sub-Zero', 'model' => 'BI-48SD', 'serial_number' => 'SUB123456', 'status' => 'In Service'],
            ['id' => 24, 'vessel_id' => 1, 'category_id' => 7, 'deck_id' => 5, 'location_id' => 17, 'internal_id' => 'GAL-002', 'name' => 'Freezer', 'manufacturer' => 'Sub-Zero', 'model' => 'BI-48SD', 'serial_number' => 'SUB123457', 'status' => 'In Service'],
            ['id' => 25, 'vessel_id' => 1, 'category_id' => 7, 'deck_id' => 5, 'location_id' => 17, 'internal_id' => 'GAL-003', 'name' => 'Stove', 'manufacturer' => 'Viking', 'model' => 'VDR5486SS', 'serial_number' => 'VIK123456', 'status' => 'In Service'],

            // Electrical Systems
            ['id' => 26, 'vessel_id' => 1, 'category_id' => 8, 'deck_id' => 3, 'location_id' => 12, 'internal_id' => 'ELEC-001', 'name' => 'Main Electrical Panel', 'manufacturer' => 'Blue Sea Systems', 'model' => 'AC/DC Panel', 'serial_number' => 'BSS123456', 'status' => 'In Service'],
            ['id' => 27, 'vessel_id' => 1, 'category_id' => 8, 'deck_id' => 3, 'location_id' => 12, 'internal_id' => 'ELEC-002', 'name' => 'Battery Bank', 'manufacturer' => 'Lifeline', 'model' => 'GPL-4CT', 'serial_number' => 'LIF123456', 'status' => 'In Service'],

            // HVAC Systems
            ['id' => 28, 'vessel_id' => 1, 'category_id' => 9, 'deck_id' => 1, 'location_id' => 5, 'internal_id' => 'HVAC-001', 'name' => 'Air Conditioning Unit', 'manufacturer' => 'Marine Air', 'model' => '16K BTU', 'serial_number' => 'MAR123456', 'status' => 'In Service'],
            ['id' => 29, 'vessel_id' => 1, 'category_id' => 9, 'deck_id' => 2, 'location_id' => 6, 'internal_id' => 'HVAC-002', 'name' => 'Upper Deck AC', 'manufacturer' => 'Marine Air', 'model' => '12K BTU', 'serial_number' => 'MAR123457', 'status' => 'In Service'],

            // Communication Equipment
            ['id' => 30, 'vessel_id' => 1, 'category_id' => 10, 'deck_id' => 4, 'location_id' => 14, 'internal_id' => 'COMM-001', 'name' => 'VHF Radio', 'manufacturer' => 'Icom', 'model' => 'M506', 'serial_number' => 'ICM123456', 'status' => 'In Service'],
            ['id' => 31, 'vessel_id' => 1, 'category_id' => 10, 'deck_id' => 4, 'location_id' => 14, 'internal_id' => 'COMM-002', 'name' => 'Satellite Phone', 'manufacturer' => 'Iridium', 'model' => '9555', 'serial_number' => 'IRI123456', 'status' => 'In Service'],
        ];

        foreach ($equipment as $item) {
            Equipment::create(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Create maintenance intervals
     */
    private function createIntervals(): void
    {
        $intervals = [
            // Engine Intervals
            ['id' => 1, 'category_id' => 1, 'description' => 'Daily engine checks and monitoring', 'interval' => 'Daily', 'facilitator' => 'Crew'],
            ['id' => 2, 'category_id' => 1, 'description' => 'Weekly engine maintenance and inspection', 'interval' => 'Weekly', 'facilitator' => 'Crew'],
            ['id' => 3, 'category_id' => 1, 'description' => 'Monthly engine service and oil change', 'interval' => 'Monthly', 'facilitator' => 'Service Provider'],
            ['id' => 4, 'category_id' => 1, 'description' => 'Annual engine overhaul and major service', 'interval' => 'Annual', 'facilitator' => 'Service Provider'],

            // Pump Intervals
            ['id' => 5, 'category_id' => 2, 'description' => 'Daily pump operation check', 'interval' => 'Daily', 'facilitator' => 'Crew'],
            ['id' => 6, 'category_id' => 2, 'description' => 'Weekly pump maintenance and cleaning', 'interval' => 'Weekly', 'facilitator' => 'Crew'],
            ['id' => 7, 'category_id' => 2, 'description' => 'Monthly pump inspection and testing', 'interval' => 'Monthly', 'facilitator' => 'Crew'],

            // Navigation Equipment Intervals
            ['id' => 8, 'category_id' => 3, 'description' => 'Daily navigation equipment check', 'interval' => 'Daily', 'facilitator' => 'Crew'],
            ['id' => 9, 'category_id' => 3, 'description' => 'Monthly navigation equipment calibration', 'interval' => 'Monthly', 'facilitator' => 'Service Provider'],
            ['id' => 10, 'category_id' => 3, 'description' => 'Annual navigation equipment certification', 'interval' => 'Annual', 'facilitator' => 'Service Provider'],

            // Safety Equipment Intervals
            ['id' => 11, 'category_id' => 4, 'description' => 'Monthly safety equipment inspection', 'interval' => 'Monthly', 'facilitator' => 'Crew'],
            ['id' => 12, 'category_id' => 4, 'description' => 'Annual safety equipment certification', 'interval' => 'Annual', 'facilitator' => 'Service Provider'],

            // Fire Fighting Equipment Intervals
            ['id' => 13, 'category_id' => 5, 'description' => 'Monthly fire extinguisher inspection', 'interval' => 'Monthly', 'facilitator' => 'Crew'],
            ['id' => 14, 'category_id' => 5, 'description' => 'Annual fire suppression system service', 'interval' => 'Annual', 'facilitator' => 'Service Provider'],

            // Deck Equipment Intervals
            ['id' => 15, 'category_id' => 6, 'description' => 'Weekly deck equipment inspection', 'interval' => 'Weekly', 'facilitator' => 'Crew'],
            ['id' => 16, 'category_id' => 6, 'description' => 'Monthly deck equipment maintenance', 'interval' => 'Monthly', 'facilitator' => 'Crew'],

            // Galley Equipment Intervals
            ['id' => 17, 'category_id' => 7, 'description' => 'Daily galley equipment check', 'interval' => 'Daily', 'facilitator' => 'Crew'],
            ['id' => 18, 'category_id' => 7, 'description' => 'Monthly galley equipment cleaning', 'interval' => 'Monthly', 'facilitator' => 'Crew'],

            // Electrical Systems Intervals
            ['id' => 19, 'category_id' => 8, 'description' => 'Daily electrical system check', 'interval' => 'Daily', 'facilitator' => 'Crew'],
            ['id' => 20, 'category_id' => 8, 'description' => 'Monthly electrical system inspection', 'interval' => 'Monthly', 'facilitator' => 'Service Provider'],

            // HVAC Systems Intervals
            ['id' => 21, 'category_id' => 9, 'description' => 'Weekly HVAC system check', 'interval' => 'Weekly', 'facilitator' => 'Crew'],
            ['id' => 22, 'category_id' => 9, 'description' => 'Monthly HVAC system maintenance', 'interval' => 'Monthly', 'facilitator' => 'Service Provider'],

            // Communication Equipment Intervals
            ['id' => 23, 'category_id' => 10, 'description' => 'Daily communication equipment check', 'interval' => 'Daily', 'facilitator' => 'Crew'],
            ['id' => 24, 'category_id' => 10, 'description' => 'Monthly communication equipment test', 'interval' => 'Monthly', 'facilitator' => 'Crew'],
        ];

        foreach ($intervals as $interval) {
            Interval::create(array_merge($interval, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Create maintenance tasks
     */
    private function createTasks(): void
    {
        $tasks = [
            // Daily Engine Tasks
            ['id' => 1, 'interval_id' => 1, 'description' => 'Check engine oil level and condition', 'instructions' => 'Check oil level using dipstick and inspect oil condition for contamination', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 2, 'interval_id' => 1, 'description' => 'Check coolant level and temperature', 'instructions' => 'Check coolant level in expansion tank and monitor temperature gauge', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 3, 'interval_id' => 1, 'description' => 'Check fuel system for leaks', 'instructions' => 'Inspect fuel lines, connections, and tank for any signs of leaks', 'applicable_to' => 'All Equipment', 'display_order' => 3],
            ['id' => 4, 'interval_id' => 1, 'description' => 'Check exhaust system for proper operation', 'instructions' => 'Check exhaust system for proper operation and no blockages', 'applicable_to' => 'All Equipment', 'display_order' => 4],

            // Weekly Engine Tasks
            ['id' => 5, 'interval_id' => 2, 'description' => 'Clean air filters', 'instructions' => 'Remove and clean air filters, replace if damaged', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 6, 'interval_id' => 2, 'description' => 'Check belt tension and condition', 'instructions' => 'Check belt tension and inspect for wear or damage', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 7, 'interval_id' => 2, 'description' => 'Inspect engine mounts', 'instructions' => 'Inspect engine mounts for wear and proper alignment', 'applicable_to' => 'All Equipment', 'display_order' => 3],
            ['id' => 8, 'interval_id' => 2, 'description' => 'Check fuel filters', 'instructions' => 'Check fuel filters for contamination and replace if necessary', 'applicable_to' => 'All Equipment', 'display_order' => 4],

            // Monthly Engine Tasks
            ['id' => 9, 'interval_id' => 3, 'description' => 'Change engine oil and filters', 'instructions' => 'Drain old oil, replace oil filter, and refill with recommended oil', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 10, 'interval_id' => 3, 'description' => 'Check valve clearances', 'instructions' => 'Check and adjust valve clearances according to manufacturer specifications', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 11, 'interval_id' => 3, 'description' => 'Inspect cooling system', 'instructions' => 'Inspect cooling system for leaks, corrosion, and proper coolant level', 'applicable_to' => 'All Equipment', 'display_order' => 3],
            ['id' => 12, 'interval_id' => 3, 'description' => 'Test emergency shutdown system', 'instructions' => 'Test emergency shutdown system to ensure proper operation', 'applicable_to' => 'All Equipment', 'display_order' => 4],

            // Daily Pump Tasks
            ['id' => 13, 'interval_id' => 5, 'description' => 'Check pump operation', 'instructions' => 'Start pump and verify it operates smoothly without unusual noises', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 14, 'interval_id' => 5, 'description' => 'Check for leaks and unusual noises', 'instructions' => 'Inspect pump and surrounding area for leaks and listen for unusual noises', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 15, 'interval_id' => 5, 'description' => 'Verify pump pressure readings', 'instructions' => 'Check pressure gauges and verify readings are within normal range', 'applicable_to' => 'All Equipment', 'display_order' => 3],

            // Weekly Pump Tasks
            ['id' => 16, 'interval_id' => 6, 'description' => 'Clean pump strainers', 'instructions' => 'Remove and clean pump strainers, check for debris and damage', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 17, 'interval_id' => 6, 'description' => 'Check pump motor condition', 'instructions' => 'Inspect pump motor for proper operation, check connections and wiring', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 18, 'interval_id' => 6, 'description' => 'Test pump emergency operation', 'instructions' => 'Test emergency pump operation and verify backup systems work', 'applicable_to' => 'All Equipment', 'display_order' => 3],

            // Daily Navigation Tasks
            ['id' => 19, 'interval_id' => 8, 'description' => 'Check GPS signal strength', 'instructions' => 'Check GPS signal strength and verify satellite lock', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 20, 'interval_id' => 8, 'description' => 'Verify chartplotter operation', 'instructions' => 'Test chartplotter display and navigation functions', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 21, 'interval_id' => 8, 'description' => 'Test radar operation', 'instructions' => 'Test radar display and target detection capabilities', 'applicable_to' => 'All Equipment', 'display_order' => 3],
            ['id' => 22, 'interval_id' => 8, 'description' => 'Check autopilot function', 'instructions' => 'Test autopilot operation and course keeping ability', 'applicable_to' => 'All Equipment', 'display_order' => 4],

            // Monthly Safety Equipment Tasks
            ['id' => 23, 'interval_id' => 11, 'description' => 'Inspect life rafts for damage', 'instructions' => 'Inspect life rafts for damage, proper stowage, and service dates', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 24, 'interval_id' => 11, 'description' => 'Check EPIRB battery status', 'instructions' => 'Check EPIRB battery status and test signal transmission', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 25, 'interval_id' => 11, 'description' => 'Verify flare expiration dates', 'instructions' => 'Check flare expiration dates and replace expired flares', 'applicable_to' => 'All Equipment', 'display_order' => 3],
            ['id' => 26, 'interval_id' => 11, 'description' => 'Test emergency lighting', 'instructions' => 'Test emergency lighting systems and backup power', 'applicable_to' => 'All Equipment', 'display_order' => 4],

            // Monthly Fire Fighting Tasks
            ['id' => 27, 'interval_id' => 13, 'description' => 'Check fire extinguisher pressure', 'instructions' => 'Check fire extinguisher pressure gauges and verify proper charge', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 28, 'interval_id' => 13, 'description' => 'Inspect fire suppression system', 'instructions' => 'Inspect fire suppression system for proper operation and maintenance', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 29, 'interval_id' => 13, 'description' => 'Test fire alarms', 'instructions' => 'Test fire alarm systems and verify proper operation', 'applicable_to' => 'All Equipment', 'display_order' => 3],
            ['id' => 30, 'interval_id' => 13, 'description' => 'Check fire doors and hatches', 'instructions' => 'Check fire doors and hatches for proper operation and seals', 'applicable_to' => 'All Equipment', 'display_order' => 4],

            // Weekly Deck Equipment Tasks
            ['id' => 31, 'interval_id' => 15, 'description' => 'Check windlass operation', 'instructions' => 'Test windlass operation and check for proper function', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 32, 'interval_id' => 15, 'description' => 'Inspect anchor chain for wear', 'instructions' => 'Inspect anchor chain for wear, corrosion, and proper stowage', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 33, 'interval_id' => 15, 'description' => 'Check mooring line condition', 'instructions' => 'Check mooring lines for wear, damage, and proper stowage', 'applicable_to' => 'All Equipment', 'display_order' => 3],
            ['id' => 34, 'interval_id' => 15, 'description' => 'Test deck lighting', 'instructions' => 'Test deck lighting systems and verify proper operation', 'applicable_to' => 'All Equipment', 'display_order' => 4],

            // Daily Galley Tasks
            ['id' => 35, 'interval_id' => 17, 'description' => 'Check refrigerator temperature', 'instructions' => 'Check refrigerator temperature and verify proper cooling', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 36, 'interval_id' => 17, 'description' => 'Check freezer temperature', 'instructions' => 'Check freezer temperature and verify proper freezing', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 37, 'interval_id' => 17, 'description' => 'Test stove operation', 'instructions' => 'Test stove operation and verify proper function', 'applicable_to' => 'All Equipment', 'display_order' => 3],
            ['id' => 38, 'interval_id' => 17, 'description' => 'Check water system pressure', 'instructions' => 'Check water system pressure and verify proper flow', 'applicable_to' => 'All Equipment', 'display_order' => 4],

            // Daily Electrical Tasks
            ['id' => 39, 'interval_id' => 19, 'description' => 'Check main electrical panel', 'instructions' => 'Check main electrical panel for proper operation and connections', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 40, 'interval_id' => 19, 'description' => 'Verify battery voltage', 'instructions' => 'Check battery voltage levels and verify proper charging', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 41, 'interval_id' => 19, 'description' => 'Check for electrical leaks', 'instructions' => 'Check for electrical leaks and ground faults', 'applicable_to' => 'All Equipment', 'display_order' => 3],
            ['id' => 42, 'interval_id' => 19, 'description' => 'Test emergency lighting', 'instructions' => 'Test emergency lighting systems and backup power', 'applicable_to' => 'All Equipment', 'display_order' => 4],

            // Weekly HVAC Tasks
            ['id' => 43, 'interval_id' => 21, 'description' => 'Check air conditioning operation', 'instructions' => 'Check air conditioning operation and temperature control', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 44, 'interval_id' => 21, 'description' => 'Clean air filters', 'instructions' => 'Clean air filters and check for proper airflow', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 45, 'interval_id' => 21, 'description' => 'Check temperature settings', 'instructions' => 'Check temperature settings and verify proper operation', 'applicable_to' => 'All Equipment', 'display_order' => 3],
            ['id' => 46, 'interval_id' => 21, 'description' => 'Inspect ductwork', 'instructions' => 'Inspect ductwork for proper installation and airflow', 'applicable_to' => 'All Equipment', 'display_order' => 4],

            // Daily Communication Tasks
            ['id' => 47, 'interval_id' => 23, 'description' => 'Test VHF radio operation', 'instructions' => 'Test VHF radio operation and signal strength', 'applicable_to' => 'All Equipment', 'display_order' => 1],
            ['id' => 48, 'interval_id' => 23, 'description' => 'Check satellite phone signal', 'instructions' => 'Check satellite phone signal and test communication', 'applicable_to' => 'All Equipment', 'display_order' => 2],
            ['id' => 49, 'interval_id' => 23, 'description' => 'Verify emergency communication', 'instructions' => 'Verify emergency communication systems and backup options', 'applicable_to' => 'All Equipment', 'display_order' => 3],
            ['id' => 50, 'interval_id' => 23, 'description' => 'Test intercom system', 'instructions' => 'Test intercom system and verify proper operation', 'applicable_to' => 'All Equipment', 'display_order' => 4],
        ];

        foreach ($tasks as $task) {
            Task::create(array_merge($task, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
