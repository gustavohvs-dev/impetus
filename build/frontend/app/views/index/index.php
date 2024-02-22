<?php

    use app\components\Core;
    use app\middlewares\Auth;
    
    $userData = Auth::validateSession(['master','admin']);

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

					<!--<div class="row mb-2 mb-xl-3">
						<div class="col-auto d-none d-sm-block">
							<h3>Page title</h3>
						</div>

						<div class="col-auto ms-auto text-end mt-n1">
							<a href="#" class="btn btn-light bg-white me-2">Invite a Friend</a>
							<a href="#" class="btn btn-primary">New Project</a>
						</div>
					</div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Empty card</h5>
                                </div>
                                <div class="card-body">
                                </div>
                            </div>
                        </div>
                    </div>-->
            
				</div>
			</main>

			<?php Core::footer(); ?>
			
		</div>
	</div>

	<?php Core::scriptsJs(); ?>

</body>

</html>