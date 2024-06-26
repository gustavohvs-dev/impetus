

$(document).ready(function () {
    //Carregar tabela
    listDatatableArquivos()
})

listDatatableArquivos = (currentPage = 1) => {

    var urlParams = new URLSearchParams(window.location.search);

    //Limpar tabela
    $("#datatable-body-arquivos").empty();
    $("#datatable-error-arquivos").empty();
    $("#datatable-pagination-arquivos").empty();

    //Carregando tabela
    var trHTML = '<p class="text-center">Carregando tabela...</p>';
    $('#datatable-error-arquivos').append(trHTML);

    //Buscar dados
    axios.get($("#webservicePath").val() + `arquivos/list`, {
        params: {
            currentPage : currentPage,
            dataPerPage : 10,
            entidadeId: urlParams.get('id'),
            entidade: "pessoas",
            status: 'ACTIVE'
        },
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then((response) => {
        if (response.data.status == 0 && typeof response.data.data == 'undefined') {
            $("#datatable-error-arquivos").empty();
            var trHTML = '<p class="text-center">Nenhum resultado encontrado</p>';
            $('#datatable-error-arquivos').append(trHTML);
        } else if (response.data.status == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Falha ao carregar tabela',
            })
        } else {
            //Renderiza os dados da tabela  
            var trHTML = '';
            $.each(response.data.data, function (i, item) {
                trHTML += '<tr>';
                trHTML += '<td>' + item.id + '</td>';
                trHTML += '<td><a href="'+$("#webservicePath").val()+'../storage/'+item.path+'" target="_blank">'+item.nome+'</a></td>';
                trHTML += '<td>' + item.name + '</td>';
                trHTML += '<td>' + item.tipo + '</td>';
                trHTML += '<td>' + item.vencimento + '</td>';
        
                trHTML += `<td class="table-action">
                                <a href="#"><i class="align-middle" data-feather="trash"
                                        onclick="deleteArquivos(` + item.id + `)"></i></a>
                            </td>`;
                trHTML += '</tr>';
            });
            $('#datatable-body-arquivos').append(trHTML);
            $("#datatable-error-arquivos").empty();
            feather.replace();

            //Atualiza a paginação
            var pagination = '';
            var numberOfPages = response.data.numberOfPages;
            var disabledFirstPage = null;
            var disabledLastPage = null;
            if(currentPage == 1){
                disabledFirstPage = 'disabled';
            }
            if(currentPage == numberOfPages){
                disabledLastPage = 'disabled';
            }
            pagination += '<ul class="pagination justify-content-center">';
            pagination += '<li class="page-item"><a class="page-link '+disabledFirstPage+'" href="#" onClick="listDatatable(1)">Primeira</a></li>';
            if(currentPage == numberOfPages && currentPage - 2 > 0){
                pagination += '<li class="page-item"><a class="page-link" href="#" onClick="listDatatable('+(currentPage - 2)+')">'+(currentPage - 2)+'</a></li>';
            }
            if(currentPage - 1 > 0){
                pagination += '<li class="page-item"><a class="page-link" href="#" onClick="listDatatable('+(currentPage - 1)+')">'+(currentPage - 1)+'</a></li>';
            }
            pagination += '<li class="page-item active"><a class="page-link" href="#" onClick="listDatatable('+currentPage+')">'+currentPage+'</a></li>';
            if(currentPage + 1 <= numberOfPages){
                pagination += '<li class="page-item"><a class="page-link" href="#" onClick="listDatatable('+(currentPage + 1)+')">'+(currentPage + 1)+'</a></li>';
            }
            if(currentPage == 1 && currentPage + 2 <= numberOfPages){
                pagination += '<li class="page-item"><a class="page-link" href="#" onClick="listDatatable('+(currentPage + 2)+')">'+(currentPage + 2)+'</a></li>';
            }
            pagination += '<li class="page-item"><a class="page-link '+disabledLastPage+'" href="#" onClick="listDatatable('+numberOfPages+')">Última</a></li>';
            pagination += '</ul>';
            $('#datatable-pagination-arquivos').append(pagination);
        }
    }).catch((error) => {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Falha ao carregar tabela',
        })
    });
    
}

createArquivos = () => {
    //$(".form-control").val(null)
    $("#form-create-arquivo").toBase64()
    $('#create-arquivo').modal('show')
    file_base64.file = null;
}

storeArquivos = () => {
    axios.post($("#webservicePath").val() + `arquivos/create`, {
    
        entidade: "pessoas",      
        entidadeId: $("#form-create-arquivoId").val(),
        nome: $("#form-create-nomearquivo").val(),
        path: "propostas/" + $("#form-create-arquivoId").val(),
        arquivo: file_base64.file,
        tipo: $("#form-create-tipoarquivo").val(),
        vencimento: $("#form-create-vencimentoarquivo").val(),
    
    }, {
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then(function (response) {
        if(response.data.status == 1){
            Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: response.data.info,
            })
            $('#create-arquivo').modal('hide')
            listDatatableArquivos()
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: response.data.info,
            })
        }
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: error.response.data.info,
        })
    });
}

/*readArquivos = (id) => {
    axios.get($("#webservicePath").val() + `arquivos/get`, {
        params: {
            id: id
        },
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then((response) => {
        if (response.data.status == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Algo deu errado!',
            })
        } else {
            $("#form-view-id").val(response.data.data.id)
            
    $("#form-view-status").val(response.data.data.status)
    
    $("#form-view-entidade").val(response.data.data.entidade)
    
    $("#form-view-entidadeId").val(response.data.data.entidadeId)
    
    $("#form-view-nome").val(response.data.data.nome)
    
    $("#form-view-path").val(response.data.data.path)
    
    $("#form-view-usuarioId").val(response.data.data.usuarioId)
    
            $("#view-items").modal("show")
        }
    }).catch((error) => {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Algo deu errado!',
        })
    });
}*/

/*editArquivos = (id) => {
    setTimeout(() => {
        axios.get($("#webservicePath").val() + `arquivos/get`, {
            params: {
                id: id
            },
            headers: { 
                Authorization : `Bearer ` + $("#sessionToken").val()
            }
        }).then((response) => {
            if (response.data.status == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Algo deu errado!',
                })
            } else {
                $("#form-edit-id").val(response.data.data.id)
                
    $("#form-edit-status").val(response.data.data.status)
    
    $("#form-edit-entidade").val(response.data.data.entidade)
    
    $("#form-edit-entidadeId").val(response.data.data.entidadeId)
    
    $("#form-edit-nome").val(response.data.data.nome)
    
    $("#form-edit-path").val(response.data.data.path)
    
    $("#form-edit-usuarioId").val(response.data.data.usuarioId)
    
                $("#edit-items").modal("show")
            }
        }).catch((error) => {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Algo deu errado!',
            })
        });
    }, 100);
}*/

/*updateArquivos = () => {
    axios.put($("#webservicePath").val() + `arquivos/update`, {
        id: $("#form-edit-id").val(),
        
    status: $("#form-edit-status").val(),
    
    entidade: $("#form-edit-entidade").val(),
    
    entidadeId: $("#form-edit-entidadeId").val(),
    
    nome: $("#form-edit-nome").val(),
    
    path: $("#form-edit-path").val(),
    
    usuarioId: $("#form-edit-usuarioId").val(),
    
    }, {
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then(function (response) {
        if(response.data.status == 1){
            Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: response.data.info,
            })
            $('#edit-items').modal('hide')
            listDatatable()
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: response.data.info,
            })
        }
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: error.response.data.info,
        })
    });
}*/

deleteArquivos = (id) => {
    $("#form-delete-arquivo-id").val(id)
    $('#delete-arquivo').modal('show')
}

destroyArquivos = () => {
    axios.delete($("#webservicePath").val() + `arquivos/delete`, { 
        params: {
            id: $("#form-delete-arquivo-id").val()
        },
        headers: { 
            Authorization : `Bearer ` + $("#sessionToken").val()
        }
    }).then(function (response) {
        if(response.data.status == 1){
            Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: response.data.info,
            })
            $('#delete-items').modal('hide')
            listDatatableArquivos()
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: response.data.info,
            })
        }
    }).catch(function (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: error.response.data.info,
        })
    });
}

