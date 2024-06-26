<?php

namespace app\components;

class Core
{

    static function credentials($userData)
    {
        require "./config.php";
        echo '<input type="hidden" id="webservicePath" value="' . $systemConfig['webservicePath'] . '">';
        echo '<input type="hidden" id="sessionToken" value="' . $_SESSION['sessionToken'] . '">';
        echo '<input type="hidden" id="userPermission" value="' . $userData->permission . '">';
    }

    static function header($headerParams = [])
    {

        $defaultTitle = "Impetus Framework";

        isset($headerParams['title']) ? $headerParamsTitle = $headerParams['title'] : $headerParamsTitle = $defaultTitle;
        isset($headerParams['author']) ? $headerParamsAuthor = $headerParams['author'] : $headerParamsAuthor = "Cybercode Sistemas";
        isset($headerParams['description']) ? $headerParamsDescription = $headerParams['description'] : $headerParamsDescription = "Viagens preventivas";
        isset($headerParams['keywords']) ? $headerParamsKeywords = $headerParams['keywords'] : $headerParamsKeywords = "RCV, viagens, preventivas, seguro, viagem";

        echo '
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="' . $headerParamsDescription . '">
        <meta name="keywords" content="' . $headerParamsKeywords . '">
        <meta name="author" content="' . $headerParamsAuthor . '">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

        <title>' . $headerParamsTitle . '</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="app/public/assets/favicon.png"> 

        <!-- Custom fonts for this template-->
        <link href="app/public/fonts/fonts.googleapis.com_css2_family=Inter_wght@300;400;600&display=swap.css" rel="stylesheet">

        <!-- Theme -->
        <link class="js-stylesheet" href="app/public/css/light.css" rel="stylesheet">

        <!-- Select 2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Custom CSS -->
        <link class="js-stylesheet" href="app/public/css/custom.css" rel="stylesheet">
        
        ';
    }

    static function headerLogin($headerParams = [])
    {

        $defaultTitle = "Impetus Framework";

        isset($headerParams['title']) ? $headerParamsTitle = $headerParams['title'] : $headerParamsTitle = $defaultTitle;
        isset($headerParams['author']) ? $headerParamsAuthor = $headerParams['author'] : $headerParamsAuthor = "Cybercode Sistemas";
        isset($headerParams['description']) ? $headerParamsDescription = $headerParams['description'] : $headerParamsDescription = "Viagens preventivas";
        isset($headerParams['keywords']) ? $headerParamsKeywords = $headerParams['keywords'] : $headerParamsKeywords = "RCV, viagens, preventivas, seguro, viagem";

        echo '
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="' . $headerParamsDescription . '">
        <meta name="keywords" content="' . $headerParamsKeywords . '">
        <meta name="author" content="' . $headerParamsAuthor . '">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

        <title>' . $headerParamsTitle . '</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="app/public/assets/favicon.png"> 

        <!-- Theme -->
        <link rel="stylesheet" media="screen" href="app/public/css/login.css">

        <script>
            (function () {
                window.onload = function () {
                const preloader = document.querySelector(".page-loading");
                preloader.classList.remove("active");
                setTimeout(function () {
                    preloader.remove();
                }, 1000);
                };
            })();
        </script>
        
        ';
    }

    static function scriptsJs()
    {
        echo '
        <!-- App -->
        <script src="app/public/js/app.js"></script>

        <!-- Axios library -->
        <script src="app/public/vendor/axios/axios.min.js"></script>

        <!-- Sweet Alert 2 -->
        <script src="app/public/vendor/sweetAlert2/sweetalert.js"></script>

        <!-- Select 2 -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Funções Gerais -->
        <script src="app/controllers/general.js"></script>
        ';
    }

    static function sidebar($userData)
    {

        $menuJson = file_get_contents('app/components/menu/menu.json');
        $menu = json_decode($menuJson);

        echo '<nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="index">
                <span class="sidebar-brand-text align-middle">
                <img src="app/public/assets/sidebarLogo.png" alt="Logo" width="200"/>
                </span>
            </a>
            <ul class="sidebar-nav">';

        foreach($menu as $header)
        {
            if(isset($header->permissions) && in_array($userData->permission, $header->permissions) && $header->children){

                echo '
                <li class="sidebar-header">'.$header->title.'</li>';

                foreach($header->children as $menuItem){
                    if(isset($menuItem->permissions) && in_array($userData->permission, $menuItem->permissions)){
                        if(isset($menuItem->pathname)){
                            echo '<li class="sidebar-item">
                            <a class="sidebar-link" href="'. $menuItem->pathname.'">
                                <i class="align-middle" data-feather="'. $menuItem->icon.'"></i> <span class="align-middle">'. $menuItem->name.'</span>
                            </a>
                            </li>';
                        }elseif(isset($menuItem->children)){
                            $tempToken = uniqid();
                            echo '<li class="sidebar-item">
                            <a data-bs-target="#p-'.$tempToken.'" data-bs-toggle="collapse" class="sidebar-link collapsed">
                                <i class="align-middle" data-feather="'. $menuItem->icon.'"></i> <span class="align-middle">'. $menuItem->name.'</span>
                            </a>
                            <ul id="p-'.$tempToken.'" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">';
                            foreach($menuItem->children as $subMenuItem){
                                if(isset($subMenuItem->permissions) && in_array($userData->permission, $subMenuItem->permissions)){
                                    echo '<li class="sidebar-item"><a class="sidebar-link" href="'.$subMenuItem->pathname.'">'.$subMenuItem->name.'</a></li>';
                                }
                            }
                            echo '</ul></li>';
                        }
                    }
                }

            }

        }
        
        echo '</ul>

            </div>
        </nav>
        ';
    }

    static function topbar()
    {
        echo '
        <nav class="navbar navbar-expand navbar-light navbar-bg">
            <a class="sidebar-toggle js-sidebar-toggle">
                <i class="hamburger align-self-center"></i>
            </a>

            <div class="navbar-collapse collapse">
                <ul class="navbar-nav navbar-align">
                    <li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle" href="#" id="notificationsDropdown" data-bs-toggle="dropdown">
                            <div class="position-relative">
                                <i class="align-middle" data-feather="bell"></i>
                                <span id="notifications-count" class="indicator">*</span>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="notificationsDropdown">
                            <div class="dropdown-menu-header">
                                Notificações
                            </div>
                            <div id="notications-alert-list" class="list-group">
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <i class="text-info" data-feather="alert-circle"></i>
                                        </div>
                                        <div class="col-10">
                                            <div class="text-dark">Notificações em desenvolvimento</div>
                                            <div class="text-muted small mt-1">Em breve o sistema de notificações ao usuário estará disponível.</div>
                                            <div class="text-muted small mt-1">06/09/2023 - 09:31:12</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-menu-footer">
                                <a href="notificacoes" class="text-muted">Ver todas as notificações</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-icon pe-md-0 dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <img src="app/public/assets/avatar.jpg" class="avatar img-fluid rounded" alt="User photo" />
                            <!--<i class="align-middle" data-feather="user"></i>-->
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!--<a class="dropdown-item" href="503"><i class="align-middle me-1" data-feather="user"></i> Perfil</a>
                            <div class="dropdown-divider"></div>-->
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="align-middle me-1" data-feather="log-out"></i> Sair</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Logout -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="logoutModalLabel">Sair</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Deseja encerrar a sessão?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="logout" class="btn btn-danger">Sair</a>
                </div>
                </div>
            </div>
        </div>
        ';
    }

    static function footer()
    {
        echo '
        <footer class="footer">
            <div class="container-fluid">
                <div class="row text-muted">
                    <div class="col-6 text-start">
                        <p class="mb-0">
                            <a href="#" target="_blank" class="text-muted"><strong>Impetus Framework</strong></a> &copy;
                        </p>
                    </div>
                    <div class="col-6 text-end">
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <a class="text-muted" href="log">Log de auditoria</a>
                            </li>
                            <!--<li class="list-inline-item">
                                <a class="text-muted" href="log">Documentação do sistema</a>
                            </li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        ';
    }

    static function select($title, $idForm, $endpoint, $params, $size = 12, $permissionsAllowed = null, $permission = null)
    {
        $hide = "";
        if ($permissionsAllowed != null) {
            $hide = "hidden";
            foreach ($permissionsAllowed as $permissionCheck) {
                if ($permissionCheck == $permission) {
                    $hide = "";
                }
            }
        }

        $select = $idForm . "-filter";
        $selectItem = "select-item-" . uniqid();
        $selectDropdown = "select-dropdown-" . uniqid();
        $selectShowDropdown = "select-show-dropdown-" . uniqid();
        $functionDropdown = "dropdownShow" . uniqid();
        $functionSelect = "dropdownSelect" . uniqid();

        echo '
            <div class="form-group col-md-' . $size . ' mb-2" ' . $hide . '>
                <label for="' . $idForm . '">' . $title . '</label>
                <input id="' . $idForm . '" type="text" hidden />
                <input id="' . $select . '" placeholder="Digite três caracteres para buscar..." type="text" autocomplete="off" class="form-control" onFocus="' . $functionDropdown . '(1)" onBlur="' . $functionDropdown . '(0)"/>
                <div id="' . $selectShowDropdown . '" class="select-dropDown">
                    <div id="' . $selectDropdown . '" class="select-listDropDown">
                        <p class="' . $selectItem . '">Digite 3 caracteres para realizar a busca<p>
                    </div>
                </div>
            </div>
            <script>
                document.querySelector("#' . $select . '").addEventListener("keyup", function () {
                    document.querySelector("#' . $select . '").value = this.value;
                    selectListDropDown = document.getElementById("' . $selectDropdown . '");
                    if (this.value.length < 3) {
                        selectListDropDown.innerHTML = `<p class="' . $selectItem . '">Digite 3 caracteres para realizar a busca<p>`;
                    } else {
                        selectListDropDown.innerHTML = `<p class="' . $selectItem . '">Carregando...<p>`;
                        axios.get($("#webservicePath").val() + `' . $endpoint . '`, {
                            params: {
                                ' . $params . '
                            },
                            headers: {
                                Authorization: `Bearer ` + $("#sessionToken").val()
                            }
                        }).then((response) => {
                            if (response.data.status == 0) {
                                selectListDropDown.innerHTML = `<p class="' . $selectItem . '">Nenhum resultado encontrado<p>`;
                            } else {
                                selectListDropDown.innerHTML = ``;
                                var htmlData = ""
                                response.data.forEach(function (obj){
                                htmlData += `<div class="' . $selectItem . '" id="' . $selectItem . '-`+obj.id+`" onMouseDown="' . $functionSelect . '(`+obj.id+`)">`+obj.text+`</div>`
                                    console.log(obj);
                                });
                                selectListDropDown.innerHTML = htmlData;
                            }
                        }).catch((error) => {
                            Swal.fire({
                                icon: "error",
                                title: "Erro",
                                text: "Algo deu errado!",
                            })
                        });
                    }
                });
                function ' . $functionDropdown . '(param) {
                    show = ["none", "block"];
                    document.getElementById("' . $selectShowDropdown . '").style.display = show[param]
                }
                function ' . $functionSelect . '(param) {
                    var item = document.getElementById("' . $selectItem . '-" + param).innerHTML;
                    document.getElementById("' . $select . '").value = item;
                    document.getElementById("' . $idForm . '").value = param;
                }
            </script>
            ';
    }

    static function urlParams()
    {
        $urlComponents = parse_url($_SERVER['REQUEST_URI']);
        if (isset($urlComponents['query'])) {
            parse_str($urlComponents['query'], $urlQuery);
            return $urlQuery;
        } else {
            return null;
        }
    }
}
