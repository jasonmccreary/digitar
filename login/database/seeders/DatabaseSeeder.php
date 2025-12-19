<?php

namespace Database\Seeders;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Eloquent::unguard();

        $this->call('UserTableSeeder');
        $this->call('FoldersTableSeeder');
        $this->call('OrganizationsTableSeeder');
        $this->call('FolderrightsTableSeeder');
        $this->call('CloudsTableSeeder');
    }
}
