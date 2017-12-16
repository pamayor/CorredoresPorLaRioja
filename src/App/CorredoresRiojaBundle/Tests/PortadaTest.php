<?php

namespace App\CorredoresRiojaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PortadaTest extends WebTestCase {

    //La portada muestra al menos una carrera activa
    public function testLaPortadaMuestraAlMenosUnaCarreraActiva() {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/corredores/es/');
        $carrerasActivas = $crawler->filter('html:contains("Inscríbete")')->count();
        $this->assertGreaterThan(0, $carrerasActivas, 'La portada muestra al menos una carrera para inscribirse');
    }

    //El detalle de una carrera muestra un solo boton para inscribirse
    public function testElDetalleMuestraUnBoton() {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/corredores/es/hood-to-coast-relay');
        $carrerasActivas = $crawler->filter('html:contains("Inscríbete")')->count();
        $this->assertEquals(1, $carrerasActivas, 'El detalle de una carrera muestra un solo boton para inscribirse');
    }
}
