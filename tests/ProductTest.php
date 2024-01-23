<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Product;
use App\Entity\Wallet;

class ProductTest extends TestCase
{
    public function testConstruct(): void
    {
        $name = 'RJ45';
        $price = array('EUR' => 9.95);
        $type = 'tech';
        $product = new Product($name, $price, $type);

        // Verification des valeurs fournis 
        $this->assertEquals($name, $product->getName()); 
        $this->assertEquals($type, $product->getType());
        $this->assertEquals($price, $product->getPrices());
    }

    public function testCurrencies(): void
    {
        $name = 'RJ45';
        $price = array('EUR' => 9.95);
        $type = 'tech';
        $product = new Product($name, $price, $type);

        // Vérification du bon fonctionnement de la fonction
        $this->assertEquals(["EUR"], $product->listCurrencies()); 

        $product->setPrices(
            array(
                'EUR' => 9.95,
                'USD' => 7.95,
                'YEN' => 110.70
            ),
        );
        $this->assertEquals(["EUR", "USD"], $product->listCurrencies()); 
    }

    public function testTVA(): void
    {
        $name = 'RJ45';
        $price = ['EUR' => 9.95];
        $type = 'tech';
        $product = new Product($name, $price, $type);

        // Vérifie lorsque le type n'est pas 'food' que la TVA est égal à 0,2
        $this->assertEquals(0.2, $product->getTVA()); 

        $product->setType('alcohol');
        $this->assertEquals(0.2, $product->getTVA()); 

        $product->setType('other');
        $this->assertEquals(0.2, $product->getTVA()); 
        
        // Vérifie lorsque le type est 'food' que la TVA est égal à 0,1
        $product->setType('food');
        $this->assertEquals(0.1, $product->getTVA()); 
    }

    public function testPrices(): void
    {
        $name = 'RJ45';
        $type = 'tech';
        $price = array(
            'EUR' => 9.95,
            'EUR' => 19.99,
            'YEN' => 9.95,
            'USD' => 9.95
        );
        $product = new Product($name, $price, $type);

        // Vérification du bon fonctionnement de la fonction
        $this->assertEquals(2, count($product->getPrices())); 
        $this->assertEquals(array(
            'EUR' => 19.99,
            'USD' => 9.95
        ), $product->getPrices());

        // Vérifie que le tableau à bien était modifier avec la fonction setPrices
        $product->setPrices(array('EUR' => 9.95));
        $this->assertNotEquals(array(
            'EUR' => 19.99,
            'USD' => 9.95
        ), $product->getPrices()); 
    }
}