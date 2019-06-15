<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(LocationsTableSeeder::class);
        $this->call(ExpertisesTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(AdminUserTypeSeeder::class);

        $this->call(JobCategoriesTableSeeder::class);
        $this->call(PropertyTypesTableSeeder::class);
        $this->call(AppSettingsTableSeeder::class);
        $this->call(OtherJobRequirementsTableSeeder::class);
        $this->call(JobTimeRequirementsTableSeeder::class);

        Model::reguard();
    }
}
