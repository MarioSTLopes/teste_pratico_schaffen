<?php

namespace App\Models;

class Robo
{
    private $posicaoX;
    private $posicaoY;
    private $direcao;
    private $movimentos = [];

    public function __construct($posicaoX, $posicaoY, $direcao)
    {
        $this->posicaoX = $posicaoX;
        $this->posicaoY = $posicaoY;
        $this->direcao = $direcao;
    }

    public function getPosicaoX()
    {
        return $this->posicaoX;
    }

    public function setPosicaoX($posicaoX)
    {
        return $this->posicaoX = $posicaoX;
    }

    public function getPosicaoY()
    {
        return $this->posicaoY;
    }

    public function setPosicaoY($posicaoY)
    {
        return $this->posicaoY = $posicaoY;
    }

    public function getDirecao()
    {
        return $this->direcao;
    }

    public function setDirecao($direcao)
    {
        return $this->direcao = $direcao;
    }

    public function getMovimentos()
    {
        return $this->movimentos;
    }

    public function setMovimentos($movimentos)
    {
        return $this->movimentos[] = $movimentos;
    }
}
