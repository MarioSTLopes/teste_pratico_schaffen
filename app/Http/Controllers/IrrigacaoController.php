<?php

namespace App\Http\Controllers;

use App\Models\Canteiro;
use App\Models\Robo;
use Illuminate\Http\Request;

class IrrigacaoController extends Controller
{
    public function irrigarHorta(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'tamanho_x' => 'required|numeric|min:0',
            'tamanho_y' => 'required|numeric|min:0',
            'posicao_x' => 'required|numeric|min:0',
            'posicao_y' => 'required|numeric|min:0',
            'direcao'   => 'required|in:N,S,L,O',
            'canteiros' => 'required|array|min:1',
            'canteiros.*.x' => 'required|numeric|min:0',
            'canteiros.*.y' => 'required|numeric|min:0',
        ]);
        $tamanhoX = $request->input('tamanho_x');
        $tamanhoY = $request->input('tamanho_y');
        $posicaoX = $request->input('posicao_x');
        $posicaoY = $request->input('posicao_y');
        $direcao = $request->input('direcao');
        $canteirosParaIrrigar = $request->input('canteiros');

        $canteiro = $this->criarCanteiro($tamanhoX, $tamanhoY, $canteirosParaIrrigar);

        $robo = $this->criarRobo($posicaoX, $posicaoY, $direcao);

        $resultado = $this->irrigarCanteiro($robo, $canteiro);

        return view('welcome')->with([
            'resultado' => $resultado,
            'direcao_final' => $robo->getDirecao(),
        ]);

    }

    private function criarCanteiro($tamanhoX, $tamanhoY, $canteirosParaIrrigar)
    {
        $canteiro = new Canteiro($tamanhoX, $tamanhoY);
        $canteiro->setCanteirosParaIrrigar($canteirosParaIrrigar);
        return $canteiro;
    }

    private function criarRobo($posicaoX, $posicaoY, $direcao)
    {
        return new Robo($posicaoX, $posicaoY, $direcao);
    }

    private function irrigarCanteiro(Robo $robo, Canteiro $canteiro)
    {
        foreach ($canteiro->getCanteirosParaIrrigar() as $canteiroParaIrrigar) {
            $resultados =  $this->calcularMovimentosParaPonto($robo, $canteiroParaIrrigar['x'], $canteiroParaIrrigar['y']);
        }

        return implode(' ', $resultados);
    }

    private function calcularMovimentosParaPonto($robo, $destinoX, $destinoY)
    {
        while ($robo->getPosicaoX() != $destinoX || $robo->getPosicaoY() != $destinoY) {

            $difX = $destinoX - $robo->getPosicaoX();
            $difY = $destinoY - $robo->getPosicaoY();

            if ($difX != 0) {
                $this->virarPara($robo, $difX > 0 ? 'L' : 'O');
            } elseif ($difY != 0) {
                $this->virarPara($robo, $difY > 0 ? 'N' : 'S');
            }

            $this->mover($robo, $destinoX, $destinoY);
        }

        $robo->setMovimentos('I');

        return $robo->getMovimentos();
    }

    private function virarPara($robo, $novaDirecao)
    {
        $direcoes = ['N', 'L', 'S', 'O'];
        while($robo->getDirecao() != $novaDirecao) {
            $atual = array_search($robo->getDirecao(), $direcoes);
            $novo = array_search($novaDirecao, $direcoes);

            $sentidoHorario = ($novo - $atual + 4) % 4;
            $sentidoAntiHorario = ($atual - $novo + 4) % 4;

            if ($sentidoHorario < $sentidoAntiHorario) {
                $this->virarDireita($robo);
            } else {
                $this->virarEsquerda($robo);
            }

        }
    }

    public function virarDireita($robo)
    {
        $direcoes = ['N', 'L', 'S', 'O'];
        $atual = array_search($robo->getDirecao(), $direcoes);
        $novo = ($atual + 1) % 4;
        $direcao = $robo->setDirecao($direcoes[$novo]);
        $movimentos = $robo->setMovimentos('D');
    }

    public function virarEsquerda($robo)
    {
        $direcoes = ['N', 'O', 'S', 'L'];
        $atual = array_search($robo->getDirecao(), $direcoes);
        $novo = ($atual + 1) % 4;
        $direcao = $robo->setDirecao($direcoes[$novo]);
        $movimentos = $robo->setMovimentos('E');
    }

    public function mover($robo, $destinoX, $destinoY)
    {
        $yAtual = $robo->getPosicaoY();
        $xAtual = $robo->getPosicaoX();
        switch ($robo->getDirecao()) {
            case 'N':
                if($yAtual+1 > $destinoY) {
                    break;
                }
                $robo->setPosicaoY($robo->getPosicaoY()+1);
                $robo->setMovimentos('M');
                break;
            case 'S':
                if($yAtual-1 < $destinoY) {
                    break;
                }
                $robo->setPosicaoY($robo->getPosicaoY()-1);
                $robo->setMovimentos('M');
                break;
            case 'L':
                if($xAtual+1 > $destinoX) {
                    break;
                }
                $robo->setPosicaoX($robo->getPosicaoX()+1);
                $robo->setMovimentos('M');
                break;
            case 'O':
                if($xAtual-1 < $destinoX) {
                    break;
                }
                $robo->setPosicaoX($robo->getPosicaoX()-1);
                $robo->setMovimentos('M');
                break;
        }
    }
}
