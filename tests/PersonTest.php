<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Product;
use App\Entity\Wallet;
use App\Entity\Person;


class PersonTest extends TestCase
{
    public function testConstruct(): void
    {
        $name = 'Michel';
        $walletCurrency = 'EUR';
        $person = new Person($name, $walletCurrency);

        // Test des valeurs transmises et par défaut
        $this->assertEquals($name, $person->getName());
        $this->assertInstanceOf(Wallet::class, $person->getWallet());
        $this->assertEquals(0, $person->getWallet()->getBalance());
        $this->assertEquals($walletCurrency, $person->getWallet()->getCurrency());
    }

    public function testSetName(): void
    {
        $person = new Person('Michel', 'USD');

        // Vérification de la modification du nom
        $newName = 'Bernard';
        $person->setName($newName);

        $this->assertEquals($newName, $person->getName());
    }

    public function testSetWallet(): void
    {
        $person = new Person('Michel', 'USD');

        // Test de la modification du portfeuille
        $person->setWallet(new Wallet('EUR'));

        $this->assertInstanceOf(Wallet::class, $person->getWallet());
        $this->assertEquals('EUR', $person->getWallet()->getCurrency());
    }

    public function testHasFund(): void
    {
        $personName = 'Michel';
        $walletCurrency = 'EUR';
        $person = new Person($personName, $walletCurrency);

        // Verifiaction de si la fonction renvoie false lorsque la balance est à 0
        $this->assertEquals(0, $person->getWallet()->getBalance());
        $this->assertFalse($person->hasFund());

        // Verifiaction de si la fonction renvoie true lorsque la balance est supérieur 0
        $person->getWallet()->setBalance(9.95);
        $this->assertNotEquals(0, $person->getWallet()->getBalance());
        $this->assertTrue($person->hasFund());
    }

    public function testTransfertFund(): void
    {
        $personName = 'Michel';
        $walletCurrency = 'EUR';
        $person1 = new Person($personName, $walletCurrency);
        $person1->getWallet()->setBalance(20);

        $personName = 'Jean-Pierre';
        $walletCurrency = 'USD';
        $person2 = new Person($personName, $walletCurrency);
        $person2->getWallet()->setBalance(5);
        
        // Test de l'exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Can't give money with different currencies");

        $person1->transfertFund(3, $person2);

        // Test du bon déroulement du processus de la fonction
        $person2->setWallet(new Wallet('EUR'));
        $person1->transfertFund(3, $person2);

        $this->assertEquals((20 - 3), $person1->getWallet()->getBalance());
        $this->assertEquals((5 + 3), $person2->getWallet()->getBalance());
    }

    public function testDivideWallet(): void
    {
        $person1 = new Person("Michel", "EUR");
        $person2 = new Person("Bernard", "EUR");
        $person3 = new Person("Jean-René", "EUR");
        $person4 = new Person("Philippe", "EUR");

        // Sans la personne dans la division
        $person1->getWallet()->setBalance(90);
        $person1->divideWallet([$person2, $person3, $person4]);

        $this->assertEquals(0, $person1->getWallet()->getBalance());
        $this->assertEquals(30, $person2->getWallet()->getBalance());
        $this->assertEquals(30, $person3->getWallet()->getBalance());
        $this->assertEquals(30, $person4->getWallet()->getBalance());

        // Avec la persanne dans la division
        $person1->getWallet()->setBalance(30);
        $person1->divideWallet([$person1, $person2, $person3]);
        
        $this->assertEquals(10, $person1->getWallet()->getBalance());
        $this->assertEquals(10, $person2->getWallet()->getBalance());
        $this->assertEquals(10, $person3->getWallet()->getBalance());
    }

    public function testBuyProduct(): void
    {
        $personName = 'Michel';
        $walletCurrency = 'EUR';
        $person = new Person($personName, $walletCurrency);

        $productName = 'RJ45';
        $productPrice = array('USD' => 9.95);
        $productType = 'tech';
        $product = new Product($productName, $productPrice, $productType);

        // Test de l'exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Can't buy product with this wallet currency");
        
        $person->buyProduct($product);

        // Test du bon fonctionnement de la fonction
        $wallet = new Wallet('USD');
        $wallet->setBalance(9.95);

        $person->setWallet($wallet);
        $this->assertEquals(9.95, $person->getWallet()->getBalance());

        $person->buyProduct($product);
        $this->assertEquals(0, $person->getWallet()->getBalance());
    }
}