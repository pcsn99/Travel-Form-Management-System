<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TravelFormQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        // Travel Request Questions
        DB::table('travel_request_questions')->insert([
            ['question' => 'Purpose', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Destination', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Overseas Form Questions
        DB::table('overseas_form_questions')->insert([
            ['question' => 'Itinerary from the Philippines and back (pls list cities)', 'type' => 'text', 'allow_other' => false, 'status' => 'active', 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Purpose', 'type' => 'text', 'allow_other' => false, 'status' => 'active', 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Who will finance?', 'type' => 'text', 'allow_other' => false, 'status' => 'active', 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Who will arrange to procure the overseas health insurance?', 'type' => 'text', 'allow_other' => false, 'status' => 'active', 'order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Who will pay for the overseas health insurance?', 'type' => 'text', 'allow_other' => false, 'status' => 'active', 'order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'How can you be contacted abroad? (phone,email)', 'type' => 'text', 'allow_other' => false, 'status' => 'active', 'order' => 6, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Local Form Questions
        DB::table('local_form_questions')->insert([
            ['question' => 'Destination', 'type' => 'text', 'allow_other' => false, 'choices' => null, 'status' => 'active', 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Purpose of travel', 'type' => 'choice', 'allow_other' => true, 'choices' => json_encode(['Province work', 'XU work', 'Personal']), 'status' => 'active', 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Financing', 'type' => 'choice', 'allow_other' => true, 'choices' => json_encode(['Sponsoring Institute', 'Community', 'Personal']), 'status' => 'active', 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Place to stay', 'type' => 'text', 'allow_other' => false, 'choices' => null, 'status' => 'active', 'order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Contact Details (while away)', 'type' => 'text', 'allow_other' => false, 'choices' => null, 'status' => 'active', 'order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
