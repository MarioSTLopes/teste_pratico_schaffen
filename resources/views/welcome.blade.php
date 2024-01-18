<!-- resources/views/welcome.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Teste Prático - Robô de Irrigação</h1>

        <div class="row">
            <div class="col-md-6">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="post" action="{{ route('irrigar-horta') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label>Tamanho da Horta:</label>
                        <div class="form-row">
                            <div class="col">
                                <input type="number" name="tamanho_x" class="form-control" placeholder="Tamanho X"
                                    required>
                            </div>
                            <div class="col">
                                <input type="number" name="tamanho_y" class="form-control" placeholder="Tamanho Y"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Posição Inicial do Robô:</label>
                        <div class="form-row">
                            <div class="col">
                                <input type="number" name="posicao_x" class="form-control" placeholder="Posição X"
                                    required>
                            </div>
                            <div class="col">
                                <input type="number" name="posicao_y" class="form-control" placeholder="Posição Y"
                                    required>
                            </div>
                            <div class="col">
                                <select name="direcao" class="form-control" required>
                                    <option value="N">Norte</option>
                                    <option value="S">Sul</option>
                                    <option value="L">Leste</option>
                                    <option value="O">Oeste</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Canteiros a Irrigar:</label>
                        <div id="canteiros-group">
                            <div class="canteiro mb-3">
                                <div class="form-row">
                                    <div class="col">
                                        <input type="number" name="canteiros[0][x]" class="form-control"
                                            placeholder="Posição X" required>
                                    </div>
                                    <div class="col">
                                        <input type="number" name="canteiros[0][y]" class="form-control"
                                            placeholder="Posição Y" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" onclick="adicionarCanteiro()">Adicionar
                            Canteiro</button>
                        <button type="button" class="btn btn-danger" id="removerCanteiro" onclick="removeCanteiro()"
                            style="display:none;">Remover Canteiro</button>
                    </div>

                    <button type="submit" class="btn btn-primary">Iniciar Irrigação</button>
                </form>
            </div>

            @isset($resultado)
                <div class="col-md-6">
                    <h4 class="mb-3">Caminho:</h4>
                    <ul id="resultados">
                        <li>{{ $resultado }}</li>
                    </ul>
                    <h4 class="mb-3">Orientação final:</h4>
                    <ul id="resultados">
                        <li>{{ $direcao_final }}</li>
                    </ul>
                </div>
            @endisset
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        let contadorCanteiros = 1;

        function adicionarCanteiro() {
            const novoCanteiro = `
                <div class="canteiro mb-3">
                    <div class="form-row">
                        <div class="col">
                            <input type="number" name="canteiros[${contadorCanteiros}][x]" class="form-control" placeholder="Posição X" required>
                        </div>
                        <div class="col">
                            <input type="number" name="canteiros[${contadorCanteiros}][y]" class="form-control" placeholder="Posição Y" required>
                        </div>
                    </div>
                </div>
            `;

            $('#canteiros-group').append(novoCanteiro);
            contadorCanteiros++;

            if (contadorCanteiros > 1) {
                $('#removerCanteiro').show();
            }
        }

        function removeCanteiro() {
            $('#canteiros-group .canteiro:last-child').remove();
            contadorCanteiros--;

            if (contadorCanteiros === 1) {
                $('#removerCanteiro').hide();
            }
        }
    </script>
@endsection
