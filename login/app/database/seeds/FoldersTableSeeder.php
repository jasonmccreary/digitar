<?php

class FoldersTableSeeder extends Seeder
{
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        // DB::table('folders')->truncate();

        $folders = [
            [
                'uid' => 2,
                'name' => 'Inkoop en kosten',
                'color' => '#ff3434',
                'bookedcheck' => 1,
            ],
            [
                'uid' => 2,
                'name' => 'Verkoopfacturen',
                'color' => '#ff3434',
                'bookedcheck' => 1,
            ],
            [
                'uid' => 2,
                'name' => 'Kasstaten',
                'color' => '#ff3434',
                'bookedcheck' => 0,
            ],
            [
                'uid' => 2,
                'name' => 'Verzekeringen',
                'color' => '#ff3434',
                'bookedcheck' => 0,
            ],
            [
                'uid' => 2,
                'name' => 'Belastingen',
                'color' => '#ff3434',
                'bookedcheck' => 0,
            ],
            [
                'uid' => 2,
                'name' => 'Correspondentie',
                'color' => '#ff3434',
                'bookedcheck' => 0,
            ],
        ];

        // Uncomment the below to run the seeder
        DB::table('folders')->insert($folders);
    }
}
