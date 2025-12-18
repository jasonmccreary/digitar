<?php
namespace Database\Seeders;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('UserTableSeeder');
        $this->call('FoldersTableSeeder');
        $this->call('OrganizationsTableSeeder');
        $this->call('FolderrightsTableSeeder');
        $this->call('CloudsTableSeeder');
    }
}
