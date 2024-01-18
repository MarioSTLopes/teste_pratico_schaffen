<?php

namespace App\Models;

class Canteiro
{
    private $tamanhoX;
    private $tamanhoY;
    private $canteirosParaIrrigar = [];

    public function __construct($tamanhoX, $tamanhoY)
    {
        $this->tamanhoX = $tamanhoX;
        $this->tamanhoY = $tamanhoY;
    }

    public function setCanteirosParaIrrigar(array $canteiros)
    {
        foreach ($canteiros as $canteiro) {
            $this->adicionarCanteiroParaIrrigar($canteiro['x'], $canteiro['y']);
        }
    }

    public function adicionarCanteiroParaIrrigar($x, $y)
    {
        $this->canteirosParaIrrigar[] = ['x' => $x, 'y' => $y];
    }

    public function getCanteirosParaIrrigar()
    {
        return $this->canteirosParaIrrigar;
    }
}
