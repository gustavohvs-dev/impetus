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
                            <h3 id="page-title">#</h3>
                        </div>
                        <div class="col-auto ms-auto text-end mt-n1">
                            <button id="pessoasPrintButton" type="button" class="btn btn-primary" onclick="imprimir()">Imprimir</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Content Row -->
                                    <div class="row">
                                        <div class="col">
                                            
                                            <!-- Tabs -->
                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">Informações</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="arquivos-tab" data-bs-toggle="tab" data-bs-target="#arquivos" type="button" role="tab" aria-controls="profile" aria-selected="false">Arquivos</button>
                                                </li>
                                                <!--<li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="visitas-tab" data-bs-toggle="tab" data-bs-target="#visitas" type="button" role="tab" aria-controls="contact" aria-selected="false">Visitas</button>
                                                </li>-->
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="observacoes-tab" data-bs-toggle="tab" data-bs-target="#observacoes" type="button" role="tab" aria-controls="contact" aria-selected="false">Observações</button>
                                                </li>
                                            </ul>
                                            <!-- Content -->
                                            <div class="tab-content" id="myTabContent">

                                                <!-- Dados básicos -->
                                                <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                                    <div class="my-4">
                                                        <div class="row mb-2 mb-xl-3">
                                                            <div class="col-auto d-none d-sm-block">
                                                                <h4>Informações</h4>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        
                                                        <div class="row">
                                                            <div class="form-group col-md-2 mb-2">
                                                                <label for="form-view-id">ID</label>
                                                                <input type="text" id="form-view-id" class="form-control" value="" readonly>
                                                            </div>

                                                            <div class="form-group col-md-2 mb-2">
                                                                <label for="form-view-status">Status</label>
                                                                <input type="text" id="form-view-status" class="form-control" value="" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3 mb-2">
                                                                <label for="form-view-tipoDocumento">Tipo de Documento</label>
                                                                <input type="text" id="form-view-tipoDocumento" class="form-control" value="" readonly>
                                                            </div>

                                                            <div class="form-group col-md-5 mb-2">
                                                                <label for="form-view-documento">Documento</label>
                                                                <input type="text" id="form-view-documento" class="form-control" value="" readonly>
                                                            </div>

                                                            <div class="form-group col-md-12 mb-2">
                                                                <label for="form-view-nome">Nome</label>
                                                                <input type="text" id="form-view-nome" class="form-control" value="" readonly>
                                                            </div>

                                                            <div id="show-nomeFantasia" class="form-group col-md-12 mb-2">
                                                                <label for="form-view-nomeFantasia">Nome Fantasia</label>
                                                                <input type="text" id="form-view-nomeFantasia" class="form-control" value="" readonly>
                                                            </div>

                                                            <b><p class="my-3">Endereço</p></b><hr>

                                                            <div class="form-group col-md-2 mb-2">
                                                                <label for="form-view-enderecoCep">CEP</label>
                                                                <input type="text" id="form-view-enderecoCep" class="form-control" value="" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3 mb-2">
                                                                <label for="form-view-enderecoLogradouro">Logradouro</label>
                                                                <input type="text" id="form-view-enderecoLogradouro" class="form-control" value="" readonly>
                                                            </div>

                                                            <div class="form-group col-md-1 mb-2">
                                                                <label for="form-view-enderecoNumero">Número</label>
                                                                <input type="text" id="form-view-enderecoNumero" class="form-control" value="" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3 mb-2">
                                                                <label for="form-view-enderecoBairro">Bairro</label>
                                                                <input type="text" id="form-view-enderecoBairro" class="form-control" value="" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3 mb-2">
                                                                <label for="form-view-enderecoComplemento">Complemento</label>
                                                                <input type="text" id="form-view-enderecoComplemento" class="form-control" value="" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3 mb-2">
                                                                <label for="form-view-enderecoCidade">Cidade</label>
                                                                <input type="text" id="form-view-enderecoCidade" class="form-control" value="" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3 mb-2">
                                                                <label for="form-view-enderecoEstado">Estado</label>
                                                                <input type="text" id="form-view-enderecoEstado" class="form-control" value="" readonly>
                                                            </div>

                                                            <div class="form-group col-md-3 mb-2">
                                                                <label for="form-view-enderecoPais">País</label>
                                                                <input type="text" id="form-view-enderecoPais" class="form-control" value="" readonly>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Arquivos -->
                                                <div class="tab-pane fade" id="arquivos" role="tabpanel" aria-labelledby="arquivos-tab">
                                                    <div class="my-4">
                                                        <div class="row mb-2 mb-xl-3">
                                                            <div class="col-auto d-none d-sm-block">
                                                                <h4>Arquivos</h4>
                                                            </div>

                                                            <div class="col-auto ms-auto text-end mt-n1">
                                                                <button type="button" class="btn btn-primary" onclick="createArquivos()">Anexar arquivo</button>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="table-responsive">
                                                            <table id="datatable-arquivos" class="dataTable table table-sm table-striped table-hover w-100">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>Arquivo</th>
                                                                        <th>Usuário</th>
                                                                        <th>Tipo</th>
                                                                        <th>Vencimento</th>
                                                                        <th>Ações</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="datatable-body-arquivos"></tbody>
                                                            </table>
                                                            <div id="datatable-error-arquivos"></div>
                                                            <nav id="datatable-pagination-arquivos" class="mt-5" aria-label="paginations">
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

                                                <!-- Visitas -->
                                                <!--<div class="tab-pane fade" id="visitas" role="tabpanel" aria-labelledby="visitas-tab">
                                                    <div class="my-4">
                                                        <div class="row mb-2 mb-xl-3">
                                                            <div class="col-auto d-none d-sm-block">
                                                                <h4>Visitas</h4>
                                                            </div>

                                                            <div class="col-auto ms-auto text-end mt-n1">
                                                                
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p>Em breve, essa funcionalidade será adicionada ao sistema</p>
                                                    </div>
                                                </div>-->

                                                <!-- Observações -->
                                                <div class="tab-pane fade" id="observacoes" role="tabpanel" aria-labelledby="observacoes-tab">
                                                    <div class="my-4">
                                                        <div class="row mb-2 mb-xl-3">
                                                            <div class="col-auto d-none d-sm-block">
                                                                <h4>Observações</h4>
                                                            </div>

                                                            <div class="col-auto ms-auto text-end mt-n1">
                                                                <button type="button" class="btn btn-primary" onclick="createObservacoes()">Cadastrar observação</button>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="table-responsive">
                                                            <table id="datatable-observacoes" class="dataTable table table-sm table-striped table-hover w-100">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>Observação</th>
                                                                        <th>Usuário</th>
                                                                        <th>Data</th>
                                                                        <th>Ações</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="datatable-body-observacoes"></tbody>
                                                            </table>
                                                            <div id="datatable-error-observacoes"></div>
                                                            <nav id="datatable-pagination-observacoes" class="mt-5" aria-label="paginations">
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
                        </div>
                    </div>

                </div>
            </main>

            <!-- Create modal -->
			<div class="modal fade" id="create-observacao" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Cadastrar observação</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form id="form-create">
								<div class="row">
		
                                    <input type="text" id="form-create-entidadeId" class="form-control" value="" hidden>
									<div class="form-group col-md-12 mb-2">
										<label for="form-create-texto">Observação</label>
                                        <textarea id="form-create-texto" class="form-control"></textarea>
									</div>
        
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
							<button type="button" class="btn btn-primary" onclick="storeObservacoesPessoas()">Cadastrar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- Create modal -->

            <!-- Delete modal -->
            <div class="modal fade" id="delete-observacao" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Deletar observação</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Você tem certeza que quer apagar este registro?</p>
                            <form id="form-delete">
                                <div class="row">
                                    <input type="text" id="form-delete-observacao-id" class="form-control" value="" hidden>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger" onclick="destroyObservacoes()">Deletar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Delete modal -->

            <!-- Create Arquivo -->
			<div class="modal fade" id="create-arquivo" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Enviar arquivo</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form id="form-create">
								<div class="row">
		
                                    <input type="text" id="form-create-arquivoId" class="form-control" value="" hidden>
									<div class="form-group col-md-12 mb-2">
										<label for="form-create-texto">Nome do arquivo</label>
                                        <input id="form-create-nomearquivo" type="text" class="form-control">
									</div>
                                    <div class="form-group col-md-12 mb-2">
										<label for="form-create-texto">Tipo do arquivo</label>
                                        <select class="form-control" id="form-create-tipoarquivo">
                                            <option value="ARQUIVO">ARQUIVO</option>
                                            <option value="CERTIFICADO/ALVARA">CERTIFICADO/ALVARA</option>
                                        </select>
									</div>
                                    <div class="form-group col-md-12 mb-2">
										<label for="form-create-texto">Vencimento</label>
                                        <input id="form-create-vencimentoarquivo" type="date" class="form-control">
									</div>
                                    <div class="form-group col-md-12 mb-2">
										<label for="form-create-texto">Arquivo</label>
                                        <input id="form-create-arquivo" type="file" class="form-control">
									</div>
        
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
							<button type="button" class="btn btn-primary" onclick="storeArquivos()">Cadastrar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- Create modal -->

            <!-- Delete Arquivo -->
            <div class="modal fade" id="delete-arquivo" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Deletar arquivo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Você tem certeza que quer apagar este arquivo?</p>
                            <form id="form-delete">
                                <div class="row">
                                    <input type="text" id="form-delete-arquivo-id" class="form-control" value="" hidden>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger" onclick="destroyArquivos()">Deletar</button>
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
    <script src="app\controllers\pessoas\painel-script.js"></script>
    <script src="app\controllers\observacoes\observacoes-pessoas-script.js"></script>
    <script src="app\controllers\arquivos\arquivos-pessoas-script.js"></script>

</body>

</html>