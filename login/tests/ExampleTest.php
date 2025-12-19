<?php

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     */
    public function test_basic_example(): void
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertTrue($this->client->getResponse()->isOk());
    }
}
