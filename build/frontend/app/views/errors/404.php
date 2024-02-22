<?php

use app\components\Core;

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<?php Core::header(); ?>
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">

		<div class="main">

			<main class="d-flex w-100 h-100">
				<div class="container d-flex flex-column">
					<div class="row vh-100">
						<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
							<div class="d-table-cell align-middle">

								<div class="text-center">
									<h1 class="display-1 fw-bold">404</h1>
									<p class="h2">Página não encontrada</p>
									<p class="lead fw-normal mt-3 mb-4">A página que você está tentando acessar não existe</p>
									<a href="index" class="btn btn-primary btn-lg">Voltar para o sistema</a>
								</div>

							</div>
						</div>
					</div>
				</div>
			</main>

		</div>
	</div>

	<?php Core::scriptsJs(); ?>

</body>

</html>