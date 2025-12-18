<?php
namespace Database\Seeders;


class UserTableSeeder extends Seeder
{
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        // DB::table('folders')->truncate();

        $users = [
            [
                'oid' => null,
                'cid' => null,
                'username' => 'digitar',
                'password' => 'eyJpdiI6IjlaT3FcL3ozOW5GRXhZdGpLZUxETTBmQW5ZMUVJcGQ5aklSNzlMYUxLK25nPSIsInZhbHVlIjoiYU5yMHBxb1ZrVUZIN21IbXFMTzhhV0k1XC9cL3dLTlBua085UXV0VkRCbzM0PSIsIm1hYyI6IjFiMTYxYzkyYjk0MTRhYTJhYTYzMjc2MmU3YzU2NGE3MTdlZGRlY2I2NGJhODE4MmZjMjEzODBkNmYxNzdlZjcifQ==',
                'rights' => '5',
                'name' => 'Digitar',
                'email' => 'info@digitar.nu',
                'website' => 'www.digitar.u',
                'tell' => '0314-820990',
                'address' => 'Dorpsstraat 55',
                'zipcode' => '7025 AB',
                'city' => 'Halle',
            ],
            [
                'oid' => '1',
                'cid' => null,
                'username' => 'heinenaccountancy',
                'password' => 'eyJpdiI6IjlaT3FcL3ozOW5GRXhZdGpLZUxETTBmQW5ZMUVJcGQ5aklSNzlMYUxLK25nPSIsInZhbHVlIjoiYU5yMHBxb1ZrVUZIN21IbXFMTzhhV0k1XC9cL3dLTlBua085UXV0VkRCbzM0PSIsIm1hYyI6IjFiMTYxYzkyYjk0MTRhYTJhYTYzMjc2MmU3YzU2NGE3MTdlZGRlY2I2NGJhODE4MmZjMjEzODBkNmYxNzdlZjcifQ==',
                'rights' => '4',
                'name' => 'Heinen Accountancy',
                'email' => 'info@heinenaccountancy.nu',
                'website' => 'www.heinenaccountancy.u',
                'tell' => '',
                'address' => '',
                'zipcode' => '',
                'city' => '',
            ],
            [
                'oid' => '2',
                'cid' => null,
                'username' => 'rollcomm',
                'password' => 'eyJpdiI6IjlaT3FcL3ozOW5GRXhZdGpLZUxETTBmQW5ZMUVJcGQ5aklSNzlMYUxLK25nPSIsInZhbHVlIjoiYU5yMHBxb1ZrVUZIN21IbXFMTzhhV0k1XC9cL3dLTlBua085UXV0VkRCbzM0PSIsIm1hYyI6IjFiMTYxYzkyYjk0MTRhYTJhYTYzMjc2MmU3YzU2NGE3MTdlZGRlY2I2NGJhODE4MmZjMjEzODBkNmYxNzdlZjcifQ==',
                'rights' => '2',
                'name' => 'RollComm',
                'email' => 'info@rollcomm.nu',
                'website' => 'www.rollcomm.u',
                'tell' => '',
                'address' => '',
                'zipcode' => '',
                'city' => '',
            ],
            [
                'oid' => '2',
                'cid' => null,
                'username' => 'digitarbv',
                'password' => 'eyJpdiI6IjlaT3FcL3ozOW5GRXhZdGpLZUxETTBmQW5ZMUVJcGQ5aklSNzlMYUxLK25nPSIsInZhbHVlIjoiYU5yMHBxb1ZrVUZIN21IbXFMTzhhV0k1XC9cL3dLTlBua085UXV0VkRCbzM0PSIsIm1hYyI6IjFiMTYxYzkyYjk0MTRhYTJhYTYzMjc2MmU3YzU2NGE3MTdlZGRlY2I2NGJhODE4MmZjMjEzODBkNmYxNzdlZjcifQ==',
                'rights' => '2',
                'name' => 'Digitar B.V.',
                'email' => 'info@digitar.nu',
                'website' => 'www.digitar.u',
                'tell' => '',
                'address' => '',
                'zipcode' => '',
                'city' => '',
            ],
            [
                'oid' => '1',
                'cid' => '3',
                'username' => 'dirkjan',
                'password' => 'eyJpdiI6IjlaT3FcL3ozOW5GRXhZdGpLZUxETTBmQW5ZMUVJcGQ5aklSNzlMYUxLK25nPSIsInZhbHVlIjoiYU5yMHBxb1ZrVUZIN21IbXFMTzhhV0k1XC9cL3dLTlBua085UXV0VkRCbzM0PSIsIm1hYyI6IjFiMTYxYzkyYjk0MTRhYTJhYTYzMjc2MmU3YzU2NGE3MTdlZGRlY2I2NGJhODE4MmZjMjEzODBkNmYxNzdlZjcifQ==',
                'rights' => '1',
                'name' => 'DirkJan Heinen',
                'email' => 'dirkjan@digitar.nu',
                'website' => 'www.digitar.u',
                'tell' => '',
                'address' => '',
                'zipcode' => '',
                'city' => '',
            ],
            [
                'oid' => '2',
                'cid' => '4',
                'username' => 'wiljon',
                'password' => 'eyJpdiI6IjlaT3FcL3ozOW5GRXhZdGpLZUxETTBmQW5ZMUVJcGQ5aklSNzlMYUxLK25nPSIsInZhbHVlIjoiYU5yMHBxb1ZrVUZIN21IbXFMTzhhV0k1XC9cL3dLTlBua085UXV0VkRCbzM0PSIsIm1hYyI6IjFiMTYxYzkyYjk0MTRhYTJhYTYzMjc2MmU3YzU2NGE3MTdlZGRlY2I2NGJhODE4MmZjMjEzODBkNmYxNzdlZjcifQ==',
                'rights' => '1',
                'name' => 'Wiljon Bolten',
                'email' => 'wiljon@digitar.nu',
                'website' => 'www.digitar.u',
                'tell' => '',
                'address' => '',
                'zipcode' => '',
                'city' => '',
            ],
        ];

        // Uncomment the below to run the seeder
        DB::table('users')->insert($users);
    }
}
