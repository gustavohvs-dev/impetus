<?php

function view($name)
{
    require "build/backend/app/config/config.php";
    echo "\nCriando View ({$name})";

    $snippet = "";

/**
 * Criando View
 */

$snippet.= '<?php

use app\components\Core;
use app\middlewares\Auth;

$userData = Auth::validateSession([\'admin\']);

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
							<h3>'.$name.'</h3>
						</div>

						<div class="col-auto ms-auto text-end mt-n1">
							<?php
							if ($userData->permission == \'admin\') {
								echo \'<button type="button" class="btn btn-primary" onclick="createItems()">Cadastrar</button>\';
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
														<label for="filter-document">Example</label>
														<input class="form-control" type="text" id="filter-example" value="">
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
															<th>Example</th>
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
										<label for="form-view-item-example">Example</label>
										<input type="text" id="form-view-item-example" class="form-control"
											value="" readonly>
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
									<div class="form-group col-md-12 mb-2">
										<label for="form-create-item-example">Example</label>
										<input type="text" id="form-create-item-example" class="form-control"
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
									<div class="form-group col-md-12 mb-2">
										<label for="form-edit-item-example">Example</label>
										<input type="text" id="form-edit-item-example" class="form-control"
											value="">
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
							<h5 class="modal-title">Deletar</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<p>Você tem certeza que quer apagar este registro?</p>
							<form id="form-delete-company">
								<div class="row">
									<input type="text" id="form-delete-users-id" class="form-control" value="" hidden>
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
	<script src="app\controllers\\'.$name.'\script.js"></script>

</body>

</html>
';

    if(!is_dir("build/frontend/app/views/$name")){
        mkdir("build/frontend/app/views/$name", 0751);
        echo "\nPasta 'build/frontend/app/views/$name' criada.";
    }else{
        echo "\nPasta 'build/frontend/app/views/$name' já existente.";
    }

    $arquivo = fopen("build/frontend/app/views/$name/$name.php", 'w');
    if($arquivo == false){
        return "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar View (".$name.")" . "\033[0m";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\033[1;31m"."\n(500 Internal Server Error) Falha ao preencher View (".$name.")" . "\033[0m";
        }else{
            echo "\033[1;32m"."\n(200 OK) View '".$name."' criada com sucesso." . "\033[0m";
        }
    } 

    $routeName = strtolower($name);

    echo "\nCriando rotas ({$name})";

    if(!is_dir("build/frontend/app/routes/") && !file_exists("build/frontend/app/routes/routes.php")){
        echo "\n(404 Not found) Arquivo de rotas não encontrado";
        return null;
    }else{
        $arquivo = fopen ('build/frontend/app/routes/routes.php', 'r');
        $result = [];
        while(!feof($arquivo)){
            $result[] = explode("];",fgets($arquivo));
        }
        fclose($arquivo);

        $snippet = "";
        $rows = count($result);

        foreach($result as $line){
            if (--$rows <= 0) {
                break;
            }
            $snippet.= $line[0];
        }

$snippet .= '    //'.$name.' route
    "'.$routeName.'" => fn() => Router::get("app/views/'.$routeName.'/'.$routeName.'.php"),
];

Router::ImpetusRouter($routes);';

        $arquivo = fopen("build/frontend/app/routes/routes.php", 'w');
        if($arquivo == false){
            echo "\033[1;31m" . "\n(500 Server Internal Error) Falha ao criar arquivo de rotas" . "\033[0m" ;
            return null;
        }else{
            $escrever = fwrite($arquivo, $snippet);
            if($escrever == false){
                echo "\033[1;31m" . "\n(500 Server Internal Error) Falha ao preencher arquivo de rotas" . "\033[0m";
                return null;
            }else{
                echo "\033[1;32m" . "\n(200 OK) Rota criada com sucesso" . "\033[0m";
            }
        } 
    }

/**
 * Criando arquivo controller JS
 */

 echo "\nCriando Controller JS ({$name})";

    $snippet = "";

/**
 * Criando View
 */

$snippet.= '//Init function
$(document).ready(function () {
    listDatatable()
})

//Render datatable */
listDatatable = (currentPage = 1) => {
    console.log("Carregando tabela...")

    //Limpar tabela
    $("#datatable-body").empty();
    $("#datatable-error").empty();
    $("#datatable-pagination").empty();

    //Carregando tabela
    var trHTML = \'<p class="text-center">Carregando tabela...</p>\';
    $(\'#datatable-error\').append(trHTML);

    //Buscar dados
    axios.get($("#endPoint").val() + `example/method`, {
        params: {
            currentPage : currentPage,
            dataPerPage : 10,
            name: $(\'#filter-example\').val(),
            status: $(\'#filter-status\').val(),
        },
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then((response) => {
        if (response.data.status == 0 && typeof response.data.data == \'undefined\') {
            $("#datatable-error").empty();
            var trHTML = \'<p class="text-center">Nenhum resultado encontrado</p>\';
            $(\'#datatable-error\').append(trHTML);
        } else if (response.data.status == 0) {
            Swal.fire({
                icon: \'error\',
                title: \'Erro\',
                text: \'Falha ao carregar tabela\',
            })
        } else {
            console.log(response.data)
            //Renderiza os dados da tabela  
            var trHTML = \'\';
            $.each(response.data.data, function (i, item) {
                trHTML += \'<tr>\';
                trHTML += \'<td>\' + item.username + \'</td>\';
                trHTML += \'<td>\' + item.name + \'</td>\';
                trHTML += \'<td>\' + item.permission + \'</td>\';
                trHTML += `<td class="table-action">
                                <a href="#"><i class="align-middle" data-feather="eye"
                                        onclick="readItems(` + item.id + `)"></i></a>
                                <a href="#"><i class="align-middle" data-feather="edit"
                                        onclick="editItems(` + item.id + `)"></i></a>
                                <a href="#"><i class="align-middle" data-feather="trash"
                                        onclick="deleteItems(` + item.id + `)"></i></a>
                            </td>`;
                trHTML += \'</tr>\';
            });
            $(\'#datatable-body\').append(trHTML);
            $("#datatable-error").empty();
            feather.replace();

            //Atualiza a paginação
            var pagination = \'\';
            var numberOfPages = response.data.numberOfPages;
            var disabledFirstPage = null;
            var disabledLastPage = null;
            if(currentPage == 1){
                disabledFirstPage = \'disabled\';
            }
            if(currentPage == numberOfPages){
                disabledLastPage = \'disabled\';
            }
            pagination += \'<ul class="pagination justify-content-center">\';
            pagination += \'<li class="page-item"><a class="page-link \'+disabledFirstPage+\'" href="#" onClick="listDatatable(1)">Primeira</a></li>\';
            if(currentPage == numberOfPages && currentPage - 2 > 0){
                pagination += \'<li class="page-item"><a class="page-link" href="#" onClick="listDatatable(\'+(currentPage - 2)+\')">\'+(currentPage - 2)+\'</a></li>\';
            }
            if(currentPage - 1 > 0){
                pagination += \'<li class="page-item"><a class="page-link" href="#" onClick="listDatatable(\'+(currentPage - 1)+\')">\'+(currentPage - 1)+\'</a></li>\';
            }
            pagination += \'<li class="page-item active"><a class="page-link" href="#" onClick="listDatatable(\'+currentPage+\')">\'+currentPage+\'</a></li>\';
            if(currentPage + 1 <= numberOfPages){
                pagination += \'<li class="page-item"><a class="page-link" href="#" onClick="listDatatable(\'+(currentPage + 1)+\')">\'+(currentPage + 1)+\'</a></li>\';
            }
            if(currentPage == 1 && currentPage + 2 <= numberOfPages){
                pagination += \'<li class="page-item"><a class="page-link" href="#" onClick="listDatatable(\'+(currentPage + 2)+\')">\'+(currentPage + 2)+\'</a></li>\';
            }
            pagination += \'<li class="page-item"><a class="page-link \'+disabledLastPage+\'" href="#" onClick="listDatatable(\'+numberOfPages+\')">Última</a></li>\';
            pagination += \'</ul>\';
            $(\'#datatable-pagination\').append(pagination);
        }
    }).catch((error) => {
        Swal.fire({
            icon: \'error\',
            title: \'Erro\',
            text: \'Falha ao carregar tabela\',
        })
    });
    
}

//Open create modal
createItems = () => {
    $(".form-control").val(null)
    $(\'#create-items\').modal(\'show\')
}

//Send create request
storeItems = () => {
    axios.post($("#endPoint").val() + `example/method`, {
        name: $("#form-create-item-example").val(),
        status: \'ACTIVE\'
    }, {
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then(function (response) {
        if(response.data.status == 1){
            Swal.fire({
                icon: \'success\',
                title: \'Sucesso\',
                text: response.data.info,
            })
            $(\'#create-items\').modal(\'hide\')
            listDatatable()
        }else{
            Swal.fire({
                icon: \'error\',
                title: \'Erro\',
                text: response.data.info,
            })
        }
    }).catch(function (error) {
        Swal.fire({
            icon: \'error\',
            title: \'Erro\',
            text: error.response.data.info,
        })
    });
}

//Open view modal
readItems = (id) => {
    axios.get($("#endPoint").val() + `example/method`, {
        params: {
            id: id
        },
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then((response) => {
        if (response.data.status == 0) {
            Swal.fire({
                icon: \'error\',
                title: \'Erro\',
                text: \'Algo deu errado!\',
            })
        } else {
            $("#form-view-item-example").val(response.data.data.example)
            $("#view-items").modal("show")
        }
    }).catch((error) => {
        Swal.fire({
            icon: \'error\',
            title: \'Erro\',
            text: \'Algo deu errado!\',
        })
    });
}

//Open edit modal
editItems = (id) => {
    setTimeout(() => {
        axios.get($("#endPoint").val() + `example/method`, {
            params: {
                id: id
            },
            headers: { 
                Authorization : `Bearer ` + $("#sessionToken").val()
            }
        }).then((response) => {
            if (response.data.status == 0) {
                Swal.fire({
                    icon: \'error\',
                    title: \'Erro\',
                    text: \'Algo deu errado!\',
                })
            } else {
                $("#form-edit-item-id").val(response.data.data.id)
                $("#edit-items").modal("show")
            }
        }).catch((error) => {
            Swal.fire({
                icon: \'error\',
                title: \'Erro\',
                text: \'Algo deu errado!\',
            })
        });
    }, 100);
}

//Send update request
updateItems = () => {
    axios.put($("#endPoint").val() + `example/method`, {
        id: $("#form-edit-item-id").val(),
    }, {
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then(function (response) {
        if(response.data.status == 1){
            Swal.fire({
                icon: \'success\',
                title: \'Sucesso\',
                text: response.data.info,
            })
            $(\'#edit-items\').modal(\'hide\')
            listDatatable()
        }else{
            Swal.fire({
                icon: \'error\',
                title: \'Erro\',
                text: response.data.info,
            })
        }
    }).catch(function (error) {
        Swal.fire({
            icon: \'error\',
            title: \'Erro\',
            text: error.response.data.info,
        })
    });
}

//Open delete modal
deleteItems = (id) => {
    $("#form-delete-item-id").val(id)
    $(\'#delete-items\').modal(\'show\')
}

//Send delete request
destroyItems = () => {
    axios.delete($("#endPoint").val() + `example/method`, { 
        params: {
            id: $("#form-delete-item-id").val()
        },
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then(function (response) {
        if(response.data.status == 1){
            Swal.fire({
                icon: \'success\',
                title: \'Sucesso\',
                text: response.data.info,
            })
            $(\'#delete-items\').modal(\'hide\')
            listDatatable()
        }else{
            Swal.fire({
                icon: \'error\',
                title: \'Erro\',
                text: response.data.info,
            })
        }
    }).catch(function (error) {
        Swal.fire({
            icon: \'error\',
            title: \'Erro\',
            text: error.response.data.info,
        })
    });
}
';

    if(!is_dir("build/frontend/app/controllers/$name")){
        mkdir("build/frontend/app/controllers/$name", 0751);
        echo "\nPasta 'build/frontend/app/controllers/$name' criada.";
    }else{
        echo "\nPasta 'build/frontend/app/controllers/$name' já existente.";
    }

    $arquivo = fopen("build/frontend/app/controllers/$name/script.js", 'w');
    if($arquivo == false){
        return "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar Controller JS (".$name.")" . "\033[0m";
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            return "\033[1;31m"."\n(500 Internal Server Error) Falha ao preencher Controller JS(".$name.")" . "\033[0m";
        }else{
            echo "\033[1;32m"."\n(200 OK) Controller JS '".$name."' criado com sucesso." . "\033[0m";
        }
    } 

}