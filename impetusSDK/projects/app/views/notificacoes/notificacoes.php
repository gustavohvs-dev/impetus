

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

<body data-theme="colored" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">

		<?php Core::sidebar($userData); ?>

		<div class="main">

			<?php Core::topbar(); ?>

			<main class="content">
				<div class="container-fluid p-0">

					<div class="row mb-2 mb-xl-3">
						<div class="col-auto d-none d-sm-block">
							<h3>Notificações</h3>
						</div>

						<div class="col-auto ms-auto text-end mt-n1">
							<button type="button" class="btn btn-danger" onclick="deleteItems()">Limpar notificações</button>
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
														<label for="filter-id">ID</label>
														<input class="form-control" type="number" id="filter-id" value="">
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
                                                            <th>ID</th>
															<th>Título</th>
															<th>Status</th>
															<th>Data</th>
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
							<form id="form-view">
								<div class="row">
									<div class="form-group col-md-4 mb-2">
										<label for="form-view-id">ID</label>
										<input type="text" id="form-view-id" class="form-control"
											value="" readonly>
									</div>
                                    
									<div class="form-group col-md-8 mb-2">
										<label for="form-view-status">Status</label>
										<input type="text" id="form-view-status" class="form-control"
											value="" readonly>
									</div>
									
									<div class="form-group col-md-12 mb-2">
										<label for="form-view-titulo">Título</label>
										<input type="text" id="form-view-titulo" class="form-control"
											value="" readonly>
									</div>
									
									<div class="form-group col-md-12 mb-2">
										<label for="form-view-mensagem">Mensagem</label>
										<textarea name="" id="form-view-mensagem" class="form-control" readonly></textarea>
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

			<!-- Delete modal -->
			<div class="modal fade" id="delete-items" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
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
	<script src="app\controllers\notificacoes\script.js"></script>

</body>

</html>

