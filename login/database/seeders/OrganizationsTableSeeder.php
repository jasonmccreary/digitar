<?php
namespace Database\Seeders;


class OrganizationsTableSeeder extends Seeder
{
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        // DB::table('folders')->truncate();

        $orgs = [
            [
                'uid' => null,
                'name' => 'Heinen Accountancy',
                'email' => 'info@digitar.nu',
                'website' => 'www.digitar.u',
                'tell' => '0543-519574',
                'address' => 'Dwarsdijk 6',
                'zipcode' => '7134 PM',
                'city' => 'Vragender',
            ],
        ];

        // Uncomment the below to run the seeder
        DB::table('organizations')->insert($orgs);
    }
}
