

$(document).ready(function () {
    //Carregar tabela
    listObservacoesDatatable()
})

listObservacoesDatatable = (currentPage = 1) => {

    var urlParams = new URLSearchParams(window.location.search);

    //Limpar tabela
    $("#datatable-body-observacoes").empty();
    $("#datatable-error-observacoes").empty();
    $("#datatable-pagination-observacoes").empty();

    //Carregando tabela
    var trHTML = '<p class="text-center">Carregando tabela...</p>';
    $('#datatable-error').append(trHTML);

    //Buscar dados
    axios.get($("#webservicePath").val() + `observacoes/list`, {
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
            $("#datatable-error-observacoes").empty();
            var trHTML = '<p class="text-center">Nenhum resultado encontrado</p>';
            $('#datatable-error-observacoes').append(trHTML);
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
                trHTML += '<td>' + item.texto + '</td>';
                trHTML += '<td>' + item.usuarioNome + '</td>';
                trHTML += '<td>' + item.createdAt + '</td>';
        
                trHTML += `<td class="table-action">
                                <a href="#"><i class="align-middle" data-feather="trash"
                                        onclick="deleteObservacoes(` + item.id + `)"></i></a>
                            </td>`;
                trHTML += '</tr>';
            });
            $('#datatable-body-observacoes').append(trHTML);
            $("#datatable-error-observacoes").empty();
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
            $('#datatable-pagination-observacoes').append(pagination);
        }
    }).catch((error) => {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Falha ao carregar tabela',
        })
    });
    
}

createObservacoes = () => {
    //$(".form-control").val(null)
    $('#create-observacao').modal('show')
}

storeObservacoesPessoas = () => {
    axios.post($("#webservicePath").val() + `observacoes/create`, {
        entidade: "pessoas",
        entidadeId: $("#form-create-entidadeId").val(),     
        texto: $("#form-create-texto").val(),
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
            $('#create-observacao').modal('hide')
            listObservacoesDatatable()
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

readObservacoes = (id) => {
    axios.get($("#webservicePath").val() + `observacoes/get`, {
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
    
    $("#form-view-texto").val(response.data.data.texto)
    
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
}

editObservacoes = (id) => {
    setTimeout(() => {
        axios.get($("#webservicePath").val() + `observacoes/get`, {
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
    
    $("#form-edit-texto").val(response.data.data.texto)
    
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
}

updateObservacoes = () => {
    axios.put($("#webservicePath").val() + `observacoes/update`, {
        id: $("#form-edit-id").val(),
        
    status: $("#form-edit-status").val(),
    
    entidade: $("#form-edit-entidade").val(),
    
    entidadeId: $("#form-edit-entidadeId").val(),
    
    texto: $("#form-edit-texto").val(),
    
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
}

deleteObservacoes = (id) => {
    $("#form-delete-observacao-id").val(id)
    $('#delete-observacao').modal('show')
}

destroyObservacoes = () => {
    axios.delete($("#webservicePath").val() + `observacoes/delete`, { 
        params: {
            id: $("#form-delete-observacao-id").val()
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
            listObservacoesDatatable()
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

