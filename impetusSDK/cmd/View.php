<?php

function view($tableName)
{
    require "build/backend/app/config/config.php";
    echo "\nCriando view ({$tableName})";

    //Busca tabela
    $query = "DESC $tableName";
    $stmt = $conn->prepare($query);
    if($stmt->execute())
    {
        $table = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "\nTabela encontrada...";

        $functionName = ucfirst(strtolower($tableName));

        $pointerCreate = 0;
        $columnNameCreate = [];
        $typeCreate = [];
        $createParams = "";
        $createTabs = "";
        $rules = ""; 
        $rulesTab = "\n\t\t\t\t\t";
        
        $documentation = [];

        foreach($table as $column)
        {
            if($column['Key'] == "PRI"){
                $primaryKey = $column['Field'];
            }

            if($column['Field']<>"id" && $column['Field']<>"createdAt"){
                $columnNameCreate[$pointerCreate] = $column["Field"];

                $columnType = explode("(" , $column["Type"]);
                $columnType = $columnType[0];
                if($columnType == "int"){
                    $typeCreate[$pointerCreate] = "PDO::PARAM_INT";
                }else{
                    $typeCreate[$pointerCreate] = "PDO::PARAM_STR";
                }

                $pointerCreate++;
            }

            if($column['Field']<>"id" && $column['Field']<>"createdAt" && $column['Field']<>"updatedAt"){
                $createParams .= $createTabs . '"'.$column['Field'].'" => $jsonParams->'.$column['Field'].',';
                $createTabs = "\n\t\t\t\t\t\t";

                //Criando regras de validação
                $columnType = $column["Type"];

                if($columnType == 'date' || $columnType == 'datetime'){
                    $type = $columnType;
                    $typeArgs = null;
                }else{
                    $columnType = explode("(", $column["Type"]);
                    $type = $columnType[0];
                    $columnType = explode(")", $columnType[1]);
                    $typeArgs = $columnType[0];
                }

                if($type == "int" || $type == "tinyint" || $type == "smallint" || $type == "mediumint" || $type == "bigint"){
                    $ruleArgs = "'type(int)'";
                    $ruleArgs .= ", 'length(".$typeArgs.")'";
                    array_push($documentation, [$column['Field'], $type, 23]);
                }elseif($type == "float" || $type == "decimal" || $type == "double" || $type == "real" || $type == "bit" || $type == "serial"){
                    $ruleArgs = "'type(number)'";
                    $ruleArgs .= ", 'length(".$typeArgs.")'";
                    array_push($documentation, [$column['Field'], $type, 18.2]);
                }elseif($type == "boolean"){
                    $ruleArgs = "'type(boolean)'";
                    array_push($documentation, [$column['Field'], $type, true]);
                }elseif($type == "date"){
                    $ruleArgs = "'type(date)'";
                    array_push($documentation, [$column['Field'], $type, "2024-01-01"]);
                }elseif($type == "datetime"){
                    $ruleArgs = "'type(datetime)'";
                    array_push($documentation, [$column['Field'], $type, "2024-01-01 21:00:00"]);
                }elseif($type == "tinytext" || $type == "text" || $type == "mediumtext" || $type == "longtext"){
                    $ruleArgs = "'type(string)', 'specialChar'";
                    $ruleArgs .= ", 'length(".$typeArgs.")'";
                    array_push($documentation, [$column['Field'], $type, "lorem ipsum dolor sit amet consectetur adipiscing elit"]);
                }elseif($type == "char" || $type == "varchar"){
                    $ruleArgs = "'type(string)', 'uppercase'";
                    $ruleArgs .= ", 'length(".$typeArgs.")'";
                    array_push($documentation, [$column['Field'], $type, "some string data"]);
                }elseif($type == "enum"){
                    $ruleArgs = "'type(string)'";
                    $typeArgs = str_replace("'", "", $typeArgs);
                    $typeArgs = str_replace(",", "|", $typeArgs);
                    $ruleArgs .= ", 'enum(".$typeArgs.")'";
                    $tempDocumentationEnumExample = explode("|", $typeArgs);
                    array_push($documentation, [$column['Field'], $type, $tempDocumentationEnumExample[0], $typeArgs, $tempDocumentationEnumExample]);
                }else{
                    $ruleArgs = "type(string)";
                    array_push($documentation, [$column['Field'], $type, "some string data"]);
                }

                if($column["Null"]=="YES"){
                    $ruleArgs .= ", 'nullable'";
                }
                
                $rules .= '["'.$column['Field'].'", $jsonParams->'.$column['Field'].', ['.$ruleArgs.']],'.$rulesTab;
            }

        }

        $queryCreateColumns = "";
        $queryCreateBindsTags = "";
        $queryCreateBindsParams = "";
        $queryUpdateColumns = "";
        $queryUpdateBindsParams = "";
        $comma = "";

        for($i = 0; $i < $pointerCreate; $i++)
        {
            if($columnNameCreate[$i] <> "updatedAt"){
                $queryCreateColumns .= $comma . $columnNameCreate[$i];
                $queryCreateBindsTags .= $comma . ":" . strtoupper($columnNameCreate[$i]);
                $queryCreateBindsParams .= '$stmt->bindParam(":'.strtoupper($columnNameCreate[$i]).'", $data["'.$columnNameCreate[$i].'"], '.$typeCreate[$i].');' . "\n\t\t";

                $queryUpdateColumns .= $comma . $columnNameCreate[$i] . " = :" . strtoupper($columnNameCreate[$i]);
                $queryUpdateBindsParams .= '$stmt->bindParam(":'.strtoupper($columnNameCreate[$i]).'", $data["'.$columnNameCreate[$i].'"], '.$typeCreate[$i].');' . "\n\t\t";
                $comma = ", ";
            }else{
                $queryUpdateColumns .= $comma . $columnNameCreate[$i] . " = :" . strtoupper($columnNameCreate[$i]);
                $queryUpdateBindsParams .= '$stmt->bindParam(":'.strtoupper($columnNameCreate[$i]).'", $data["'.$columnNameCreate[$i].'"], '.$typeCreate[$i].');' . "\n\t\t";
                $comma = ", ";
            }
        }

        /**
         * Criar pasta do controller e view
         */
        if(!is_dir("build/frontend/app/views/$tableName")){
            mkdir("build/frontend/app/views/$tableName", 0751);
            echo "\nPasta 'build/frontend/app/views/$tableName' criada.";
        }else{
            /*echo "\nPasta 'build/frontend/app/views/$tableName' já existente.";
            echo "\033[1;31m"."\nOperação cancelada"."\033[0m";
            return;*/
        }

        if(!is_dir("build/frontend/app/controllers/$tableName")){
            mkdir("build/frontend/app/controllers/$tableName", 0751);
            echo "\nPasta 'build/frontend/app/controllers/$tableName' criada.";
        }else{
            /*echo "\nPasta 'build/frontend/app/controllers/$tableName' já existente.";
            echo "\033[1;31m"."\nOperação cancelada"."\033[0m";
            return;*/
        }

     /**
     * View
     */

$jsonBody = "";
$semicolon = ",";
$count = count($documentation);
for($i = 0; $i < $count; $i++){
    if($i+1 < $count){
    $jsonBody .= '"' . $documentation[$i][0] . '" : "' . $documentation[$i][2] . '"' . $semicolon . '
    ';
    }else{
    $jsonBody .= '"' . $documentation[$i][0] . '" : "' . $documentation[$i][2] . '"';
    }
}

/** Presets */
$datatableRequest = "";
$datatableTableView = "";
$viewRequest = "";
$viewForm = "";
$createRequest = "";
$createForm = "";
$editRequest = "";
$editForm = "";
$updateRequest = "";
$deleteRequest = "";
$deleteForm = "";
$countIterations = 0;
foreach($documentation as $item)
{
    $countIterations++;
    $itemFirstCharUppercase = ucfirst($item[0]);

    if($countIterations <= 3){
        $datatableRequest .= "trHTML += '<td>' + item.".$item[0]." + '</td>';
        ";
        $datatableTableView .= "<th>".$itemFirstCharUppercase."</th>
        ";
    }

    $viewForm .= '
    <div class="form-group col-md-4 mb-2">
        <label for="form-view-'.$item[0].'">'.$itemFirstCharUppercase.'</label>
        <input type="text" id="form-view-'.$item[0].'" class="form-control"
            value="" readonly>
    </div>
    ';

    $viewRequest .= '
    $("#form-view-'.$item[0].'").val(response.data.data.'.$item[0].')
    ';

    if(isset($item[4])){
        $tempEnum = $item[4];
    }else{
        $tempEnum = [];
    }
    $createForm .= field($item[0], "create", $item[1], $tempEnum);
    $editForm .= field($item[0], "edit", $item[1], $tempEnum);

    $createRequest .= '
    '.$item[0].': $("#form-create-'.$item[0].'").val(),
    ';

    $editRequest .= '
    $("#form-edit-'.$item[0].'").val(response.data.data.'.$item[0].')
    ';

    $updateRequest .= '
    '.$item[0].': $("#form-edit-'.$item[0].'").val(),
    ';

}

$snippet= '

<?php

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
							<h3>'.$functionName.'</h3>
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
															'.$datatableTableView.'
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
                                    '.$viewForm.'
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
							<form id="form-create">
								<div class="row">
									'.$createForm.'
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
							<form id="form-edit">
								<div class="row">
									<input type="text" id="form-edit-id" class="form-control" value="" hidden>
									 '.$editForm.'                    
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
	<script src="app\controllers\\'.$tableName.'\\script.js"></script>

</body>

</html>

';

    $arquivo = fopen("build/frontend/app/views/$tableName/$tableName.php", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar ".$tableName.".php)". "\033[0m";
        return false;
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            echo "\033[1;31m"."\n(500 Internal Server Error)Falha ao preencher ".$tableName.".php)". "\033[0m";
            return false;
        }else{
            echo "\033[1;32m"."\n(200 OK) View ".$tableName." criada com sucesso.". "\033[0m";
        }
    } 

    /**
     * FIM - View
     */

    /**
     * Controller
     */

$snippet= '

$(document).ready(function () {
    //Carregar tabela
    listDatatable()
})

listDatatable = (currentPage = 1) => {

    //Limpar tabela
    $("#datatable-body").empty();
    $("#datatable-error").empty();
    $("#datatable-pagination").empty();

    //Carregando tabela
    var trHTML = \'<p class="text-center">Carregando tabela...</p>\';
    $(\'#datatable-error\').append(trHTML);

    //Buscar dados
    axios.get($("#endPoint").val() + `'.$tableName.'/list`, {
        params: {
            currentPage : currentPage,
            dataPerPage : 10,
            id: $("#filter-id").val()
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
            //Renderiza os dados da tabela  
            var trHTML = \'\';
            $.each(response.data.data, function (i, item) {
                trHTML += \'<tr>\';
                trHTML += \'<td>\' + item.id + \'</td>\';
                '.$datatableRequest.'
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

createItems = () => {
    $(".form-control").val(null)
    $(\'#create-items\').modal(\'show\')
}

storeItems = () => {
    axios.post($("#endPoint").val() + `'.$tableName.'/create`, {
        '.$createRequest.'
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

readItems = (id) => {
    axios.get($("#endPoint").val() + `'.$tableName.'/get`, {
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
            $("#form-view-id").val(response.data.data.id)
            '.$viewRequest.'
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

editItems = (id) => {
    setTimeout(() => {
        axios.get($("#endPoint").val() + `'.$tableName.'/get`, {
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
                $("#form-edit-id").val(response.data.data.id)
                '.$editRequest.'
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

updateItems = () => {
    axios.put($("#endPoint").val() + `'.$tableName.'/update`, {
        id: $("#form-edit-id").val(),
        '.$updateRequest.'
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

deleteItems = (id) => {
    $("#form-delete-id").val(id)
    $(\'#delete-items\').modal(\'show\')
}

destroyItems = () => {
    axios.delete($("#endPoint").val() + `'.$tableName.'/delete`, { 
        params: {
            id: $("#form-delete-id").val()
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

    $arquivo = fopen("build/frontend/app/controllers/$tableName/script.js", 'w');
    if($arquivo == false){
        echo "\033[1;31m"."\n(500 Internal Server Error) Falha ao criar ".$tableName.".js)". "\033[0m";
        return false;
    }else{
        $escrever = fwrite($arquivo, $snippet);
        if($escrever == false){
            echo "\033[1;31m"."\n(500 Internal Server Error)Falha ao preencher ".$tableName.".js)". "\033[0m";
            return false;
        }else{
            echo "\033[1;32m"."\n(200 OK) Controller ".$tableName." criado com sucesso.". "\033[0m";
        }
    } 

     /**
      * Fim - Controller
      */

    /**
     * Routes
     */

     $routeName = strtolower($tableName);

    echo "\nCriando rotas ({$tableName})";

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

$snippet .= '    //'.$tableName.' View
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
                return null;
            }
        } 
    }
    
  
    }else{
        $error = $stmt->errorInfo();
        $error = $error[2];
        echo "\033[1;31m"."\n(500 Internal Server Error) ". $error ."\033[0m";
        return false;
    }

}

function field($fieldName, $tag, $type, $enum = [])
{
    $firstCharUppercase = ucfirst($fieldName);
    if($type == "int" || $type == "tinyint" || $type == "smallint" || $type == "mediumint" || $type == "bigint"){
        return '
        <div class="form-group col-md-4 mb-2">
            <label for="form-'.$tag.'-'.$fieldName.'">'.$firstCharUppercase.'</label>
            <input type="number" id="form-'.$tag.'-'.$fieldName.'" class="form-control" value="">
        </div>
        ';
    }elseif($type == "float" || $type == "decimal" || $type == "double" || $type == "real" || $type == "bit" || $type == "serial"){
        return '
        <div class="form-group col-md-4 mb-2">
            <label for="form-'.$tag.'-'.$fieldName.'">'.$firstCharUppercase.'</label>
            <input type="number" step="0.01" id="form-'.$tag.'-'.$fieldName.'" class="form-control" value="">
        </div>
        ';
    }elseif($type == "boolean"){
        return '
        <div class="form-group col-md-4 mb-2">
            <label for="form-'.$tag.'-'.$fieldName.'">'.$firstCharUppercase.'</label>
            <select class="form-control" id="form-'.$tag.'-'.$fieldName.'">
                <option value="1">True</option>
                <option value="0">False</option>
            </select>
        </div>
        ';
    }elseif($type == "date"){
        return '
        <div class="form-group col-md-4 mb-2">
            <label for="form-'.$tag.'-'.$fieldName.'">'.$firstCharUppercase.'</label>
            <input type="date" step="0.01" id="form-'.$tag.'-'.$fieldName.'" class="form-control" value="">
        </div>
        ';
    }elseif($type == "datetime"){
        return '
        <div class="form-group col-md-4 mb-2">
            <label for="form-'.$tag.'-'.$fieldName.'">'.$firstCharUppercase.'</label>
            <input type="datetime-local" id="form-'.$tag.'-'.$fieldName.'" class="form-control" value="">
        </div>
        ';
    }elseif($type == "enum"){
        $tempOptions = "";
        foreach($enum as $option){
            $firstCharUppercaseOption = ucfirst($option);
            $tempOptions .= '<option value="'.$option.'">'.$firstCharUppercaseOption.'</option>';
        }
        return '
        <div class="form-group col-md-4 mb-2">
            <label for="form-'.$tag.'-'.$fieldName.'">'.$firstCharUppercase.'</label>
            <select class="form-control" id="form-'.$tag.'-'.$fieldName.'">
                '. $tempOptions.'
            </select>
        </div>
        ';
    }else{
        return '
        <div class="form-group col-md-4 mb-2">
            <label for="form-'.$tag.'-'.$fieldName.'">'.$firstCharUppercase.'</label>
            <input type="text" id="form-'.$tag.'-'.$fieldName.'" class="form-control" value="">
        </div>
        ';
    }
    
}