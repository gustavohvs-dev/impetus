<?php

use app\components\Core;
use app\middlewares\Auth;

$userData = Auth::validateSession(['admin','comercial']);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php Core::header(); ?>
</head>

<body data-theme="colored" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
    <div class="wrapper">

        <?php Core::sidebar($userData); ?>

        <div class="main">

            <?php Core::topbar(); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <div class="row mb-2 mb-xl-3">
                        <div class="col-auto d-none d-sm-block">
                            <h3>Pessoas físicas e jurídicas</h3>
                        </div>

                        <div class="col-auto ms-auto text-end mt-n1">
                            <?php
                            if ($userData->permission == 'admin') {
                                echo '<button type="button" class="btn btn-primary" onclick="createItems()">Cadastrar</button>';
                                echo '<button id="pessoasSheetButton" type="button" class="btn btn-success mx-1" onclick="extrairPlanilhaDePessoas()">Extrair planilha</button>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Content Row -->
                                    <div class="row">
                                        <div class="col">
                                            <p>
                                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#datatable-filters" aria-expanded="false" aria-controls="#datatable-filters">
                                                    <i class="align-middle" data-feather="search"></i>
                                                </button>
                                            </p>
                                            <form id="datatable-filters" class="collapse">
                                                <div class="row">
                                                    <div class="col-md-3 col-xl-2">
                                                        <label for="filter-id">ID</label>
                                                        <input class="form-control" type="number" id="filter-id" value="">
                                                    </div>
                                                    <div class="col-md-3 col-xl-2">
                                                        <label for="filter-nome">Nome</label>
                                                        <input class="form-control" type="text" id="filter-nome" value="">
                                                    </div>
                                                    <div class="col-md-3 col-xl-2">
                                                        <label for="filter-tipoDocumento">Tipo de documento</label>
                                                        <select class="form-control" id="filter-tipoDocumento">
                                                            <option value=""></option>
                                                            <option value="CPF">CPF</option>
                                                            <option value="CNPJ">CNPJ</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary btn-sm mt-3" type="button" onClick="listDatatable()">Aplicar filtros</button>
                                                <hr>
                                            </form>
                                            <div class="table-responsive">
                                                <table id="datatable" class="dataTable table table-sm table-striped table-hover w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Nome</th>
                                                            <th>Tipo de Documento</th>
                                                            <th>Documento</th>
                                                            <th>Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="datatable-body"></tbody>
                                                </table>
                                                <div id="datatable-error"></div>
                                                <nav id="datatable-pagination" class="mt-5" aria-label="paginations">
                                                    <ul class="pagination justify-content-center">
                                                        <li class="page-item"><a class="page-link" href="#" onClick="listDatatable(1)">Primeira</a></li>
                                                        <li class="page-item"><a class="page-link" href="#" onClick="listDatatable(1)">1</a></li>
                                                        <li class="page-item"><a class="page-link" href="#" onClick="listDatatable(2)">2</a></li>
                                                        <li class="page-item active"><a class="page-link" href="#" onClick="listDatatable(3)">3</a></li>
                                                        <li class="page-item"><a class="page-link" href="#" onClick="listDatatable(4)">4</a></li>
                                                        <li class="page-item"><a class="page-link" href="#" onClick="listDatatable(5)">5</a></li>
                                                        <li class="page-item"><a class="page-link" href="#" onClick="listDatatable(1)">Última</a></li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            <!-- Create modal -->
            <div class="modal fade" id="create-items" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cadastrar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form-create">
                                <div class="row">

                                    <div class="form-group col-md-12 mb-2">
                                        <label for="form-create-tipoDocumento">Tipo de Documento</label>
                                        <select class="form-control" id="form-create-tipoDocumento">
                                            <option value="CPF">CPF</option>
                                            <option value="CNPJ">CNPJ</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12 mb-2">
                                        <label for="form-create-documento">Documento</label>
                                        <input type="text" id="form-create-documento" class="form-control" value="">
                                    </div>

                                    <div class="form-group col-md-12 mb-2">
                                        <label for="form-create-nome">Nome</label>
                                        <input type="text" id="form-create-nome" class="form-control" value="">
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="storeItems()">Cadastrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Create modal -->

            <!-- Edit modal -->
            <div class="modal fade" id="edit-items" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form-edit">
                                <div class="row">
                                    <input type="text" id="form-edit-id" class="form-control" value="" hidden>

                                    <div class="form-group col-md-4 mb-2">
                                        <label for="form-edit-status">Status</label>
                                        <select class="form-control" id="form-edit-status">
                                            <option value="ACTIVE">ACTIVE</option>
                                            <option value="INACTIVE">INACTIVE</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <label for="form-edit-tipoDocumento">Tipo de Documento</label>
                                        <select class="form-control" id="form-edit-tipoDocumento">
                                            <option value="CPF">CPF</option>
                                            <option value="CNPJ">CNPJ</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <label for="form-edit-documento">Documento</label>
                                        <input type="text" id="form-edit-documento" class="form-control" value="">
                                    </div>

                                    <div class="form-group col-md-12 mb-2">
                                        <label for="form-edit-nome">Nome</label>
                                        <input type="text" id="form-edit-nome" class="form-control" value="">
                                    </div>

                                    <div class="form-group col-md-12 mb-2">
                                        <label for="form-edit-nomeFantasia">Nome Fantasia</label>
                                        <input type="text" id="form-edit-nomeFantasia" class="form-control" value="">
                                    </div>

                                    <b><p class="my-3">Endereço</p></b><hr>

                                    <div class="form-group col-md-2 mb-2">
                                        <label for="form-edit-enderecoCep">CEP</label>
                                        <div class="input-group mb-3">
                                            <input type="text" id="form-edit-enderecoCep" class="form-control" value="">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" onclick="getCep()"><i class="align-middle" data-feather="search"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <label for="form-edit-enderecoLogradouro">Logradouro</label>
                                        <input type="text" id="form-edit-enderecoLogradouro" class="form-control" value="">
                                    </div>

                                    <div class="form-group col-md-1 mb-2">
                                        <label for="form-edit-enderecoNumero">Número</label>
                                        <input type="text" id="form-edit-enderecoNumero" class="form-control" value="">
                                    </div>

                                    <div class="form-group col-md-3 mb-2">
                                        <label for="form-edit-enderecoBairro">Bairro</label>
                                        <input type="text" id="form-edit-enderecoBairro" class="form-control" value="">
                                    </div>

                                    <div class="form-group col-md-2 mb-2">
                                        <label for="form-edit-enderecoComplemento">Complemento</label>
                                        <input type="text" id="form-edit-enderecoComplemento" class="form-control" value="">
                                    </div>

                                    <div class="form-group col-md-3 mb-2">
                                        <label for="form-edit-enderecoCidade">Cidade</label>
                                        <input type="text" id="form-edit-enderecoCidade" class="form-control" value="">
                                    </div>

                                    <div class="form-group col-md-3 mb-2">
                                        <label for="form-edit-enderecoEstado">Estado</label>
                                        <select class="form-control" id="form-edit-enderecoEstado">
                                            <option value="Acre">Acre</option>
                                            <option value="Alagoas">Alagoas</option>
                                            <option value="Amapá">Amapá</option>
                                            <option value="Amazonas">Amazonas</option>
                                            <option value="Bahia">Bahia</option>
                                            <option value="Ceará">Ceará</option>
                                            <option value="Distrito Federal">Distrito Federal</option>
                                            <option value="Espirito Santo">Espirito Santo</option>
                                            <option value="Goiás">Goiás</option>
                                            <option value="Maranhão">Maranhão</option>
                                            <option value="Mato Grosso do Sul">Mato Grosso do Sul</option>
                                            <option value="Mato Grosso">Mato Grosso</option>
                                            <option value="Minas Gerais">Minas Gerais</option>
                                            <option value="Pará">Pará</option>
                                            <option value="Paraíba">Paraíba</option>
                                            <option value="Paraná">Paraná</option>
                                            <option value="Pernambuco">Pernambuco</option>
                                            <option value="Piauí">Piauí</option>
                                            <option value="Rio de Janeiro">Rio de Janeiro</option>
                                            <option value="Rio Grande do Norte">Rio Grande do Norte</option>
                                            <option value="Rio Grande do Sul">Rio Grande do Sul</option>
                                            <option value="Rondônia">Rondônia</option>
                                            <option value="Roraima">Roraima</option>
                                            <option value="Santa Catarina">Santa Catarina</option>
                                            <option value="São Paulo">São Paulo</option>
                                            <option value="Sergipe">Sergipe</option>
                                            <option value="Tocantins">Tocantins</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 mb-2">
                                        <label for="form-edit-enderecoPais">País</label>
                                        <input type="text" id="form-edit-enderecoPais" class="form-control" value="">
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="updateItems()">Atualizar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit modal -->

            <!-- Delete modal -->
            <div class="modal fade" id="delete-items" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Deletar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Você tem certeza que quer apagar este registro?</p>
                            <form id="form-delete">
                                <div class="row">
                                    <input type="text" id="form-delete-id" class="form-control" value="" hidden>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger" onclick="destroyItems()">Deletar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Delete modal -->

            <?php Core::footer(); ?>

        </div>
    </div>

    <?php Core::credentials($userData); ?>
    <?php Core::scriptsJs(); ?>

    <!-- Controller -->
    <script src="app\controllers\pessoas\script.js"></script>
    <script src="app\controllers\pessoas\cep-api.js"></script>

</body>

</html>