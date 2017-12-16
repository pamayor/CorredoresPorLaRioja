<?php

namespace App\CorredoresRiojaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CargaPaginasTest extends WebTestCase {

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url) {
        $client = self::createClient();
        $crawler = $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider() {
        return array(
            array('/corredores/es/'),
            array('/corredores/es/carreras'),
            array('/corredores/es/carrera/maraton-walt-disney'),
            array('/corredores/es/organizacion/matute'),
            array('/corredores/es/login'),
            array('/corredores/es/registro'),
            array('/corredores/en/'),
            array('/corredores/en/races'),
            array('/corredores/en/race/maraton-walt-disney'),
            array('/corredores/en/organization/matute'),
            array('/corredores/en/login'),
            array('/corredores/en/registro'),
        );
    }

}
