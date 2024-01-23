<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Wallet;

class WalletTest extends TestCase
{
    public function testBalance(): void
    {
        $wallet = new Wallet('EUR');

        // Vérifie que la valeur par défaut est bien 0
        $this->assertEquals(0, $wallet->getBalance()); 
        
        // Vérifie les changements de valeur  
        $wallet->setBalance(13);
        $this->assertEquals(13, $wallet->getBalance()); 

        $wallet->setBalance(1);
        $this->assertNotEquals(13, $wallet->getBalance()); 

        // Vérification de l'sception lorsque la valeur est < à 0
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid balance");

        $wallet->setBalance(-1);
    }

    public function testCurrency(): void
    {
        $wallet = new Wallet('EUR');

        // Vérifie la valeur transmise
        $this->assertEquals('EUR', $wallet->getCurrency()); 

        // Vérifie les changements de valeur  
        $wallet->setCurrency('USD');
        $this->assertEquals('USD', $wallet->getCurrency()); 

        $wallet->setCurrency('EUR');
        $this->assertNotEquals('USD', $wallet->getCurrency()); 

        // Vérification de l'exception lorsque la valeur n'est pas présente dans le tableau des valeur possible
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid currency");

        $wallet->setCurrency('YEN');
    }

    public function testFund(): void
    {
        $wallet = new Wallet('EUR');
        
        // Vérification de l'exception lors de la supression de fond supérieur à ceux actuel
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Insufficient funds");

        $wallet->removeFund(12.99);

        // Vérification du bon déroulement de l'ajout de fond
        $wallet->addFund(3.99);
        $wallet->addFund(10);
        $this->assertEquals(13.99, $wallet->getBalance()); 

        // Vérification du bon déroulement de la suppression de fond
        $wallet->removeFund(10);
        $this->assertNotEquals(13.99, $wallet->getBalance()); 
        $this->assertEquals(3.99, $wallet->getBalance()); 

        // Vérification de l'exception lors de la supression de fond est inférieur à 0
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid amount");

        $wallet->removeFund(-1);

        // Vérification de l'exception lors de l'ajout de fond est inférieur à 0
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid amount");

        $wallet->addFund(-1);
    }
}