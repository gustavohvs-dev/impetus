<?php

use app\components\Core;
use app\middlewares\Auth;

$userData = Auth::validateSession(['admin']);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<?php Core::header(); ?>
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">

		<?php Core::sidebar($userData); ?>

		<div class="main">

			<?php Core::topbar(); ?>

			<main class="content">
				<div class="container-fluid p-0">

					<div class="row mb-2 mb-xl-3">
						<div class="col-auto d-none d-sm-block">
							<h3>Empresas</h3>
						</div>

						<div class="col-auto ms-auto text-end mt-n1">
							<?php
							if ($userData->permission == 'admin') {
								echo '<button type="button" class="btn btn-primary" onclick="createItems()">Cadastrar</button>';
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
												<button class="btn btn-primary" type="button" data-bs-toggle="collapse"
													data-bs-target="#datatable-filters" aria-expanded="false"
													aria-controls="#datatable-filters">
													<i class="align-middle" data-feather="search"></i>
												</button>
											</p>
											<form id="datatable-filters" class="collapse">
												<div class="row">
													<div class="col-md-3 col-xl-2">
														<label for="filter-document">Nome</label>
														<input class="form-control" type="text" id="filter-corporateName" value="">
													</div>
													<div class="col-md-3 col-xl-2">
														<label for="filter-status">Status</label>
														<select class="form-control" id="filter-status">
															<option value="ACTIVE" selected>Ativo</option>
															<option value="INACTIVE">Inativo</option>
															<option value="">Todos</option>
														</select>
													</div>
												</div>
												<button class="btn btn-primary btn-sm mt-3" type="button" onClick="listDatatable()">Aplicar filtros</button>
												<hr>
											</form>
											<div class="table-responsive">
												<table id="datatable"
													class="dataTable table table-sm table-striped table-hover w-100">
													<thead>
														<tr>
															<th>Razão social</th>
															<th>Nome fantasia</th>
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

			<!-- View modal -->
			<div class="modal fade" id="view-items" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Visualizar</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form id="form-view-items">
								<div class="row">
									<div class="form-group col-md-6 mb-2">
										<label for="form-view-item-corporateName">Razão social</label>
										<input type="text" id="form-view-item-corporateName" class="form-control"
											value="" readonly>
									</div>
									<div class="form-group col-md-6 mb-2">
										<label for="form-view-item-name">Nome fantasia</label>
										<input type="text" id="form-view-item-name" class="form-control" value=""
											readonly>
									</div>
									<div class="form-group col-md-12 mb-2">
										<label for="form-view-item-document">Documento</label>
										<input type="text" id="form-view-item-document" class="form-control" value=""
											readonly>
									</div>        
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- View modal -->

			<!-- Create modal -->
			<div class="modal fade" id="create-items" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Cadastrar</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form id="form-create-items">
								<div class="row">
									<div class="form-group col-md-6 mb-2">
										<label for="form-create-item-corporateName">Razão social</label>
										<input type="text" id="form-create-item-corporateName" class="form-control"
											value="">
									</div>
									<div class="form-group col-md-6 mb-2">
										<label for="form-create-item-name">Nome fantasia</label>
										<input type="text" id="form-create-item-name" class="form-control"
											value="">
									</div>
									<div class="form-group col-md-12 mb-2">
										<label for="form-create-item-document">Documento</label>
										<input type="text" id="form-create-item-document" class="form-control"
											value="">
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
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Editar</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form id="form-edit-items">
								<div class="row">
									<input type="text" id="form-edit-item-id" class="form-control" value="" hidden>
									<div class="form-group col-md-6 mb-2">
										<label for="form-edit-item-corporateName">Razão social</label>
										<input type="text" id="form-edit-item-corporateName" class="form-control"
											value="">
									</div>
									<div class="form-group col-md-6 mb-2">
										<label for="form-edit-item-name">Nome fantasia</label>
										<input type="text" id="form-edit-item-name" class="form-control" value="">
									</div>
                                    <div class="form-group col-md-12 mb-2">
										<label for="form-edit-item-document">Documento</label>
										<input type="text" id="form-edit-item-document" class="form-control" value="">
									</div>    
									<div class="form-group col-md-12 mb-2">
										<label for="form-edit-item-status">Status</label>
										<select id="form-edit-item-status" class="form-control">
											<option value="ACTIVE">ATIVO</option>
											<option value="INACTIVE">INATIVO</option>
										</select>
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
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Deletar empresa</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<p>Você tem certeza que quer apagar este registro?</p>
							<form id="form-delete-item">
								<div class="row">
									<input type="text" id="form-delete-item-id" class="form-control" value="" hidden>
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

	<!-- Users controller -->
	<script src="app\controllers\companies\script.js"></script>

</body>

</html>