<?php

namespace App\Taxe;

class CalculatorTaxe {

    public function calculerTVA(float $prixHT): float {
        return $prixHT * $this->getTvaRate();
    }

    public function calculerTTC(float $prixHT): float {
        return $prixHT + $this->calculerTVA($prixHT);
    }

    private function getTvaRate(): float {
        // Vous pouvez ajuster le taux de TVA ici si nécessaire
        return 0.20; // 20% TVA par défaut
    }
}
