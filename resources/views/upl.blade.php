@include('header')
@include('side')


				<div class="span9">
					<div class="content">

						

						<div class="module">
							<div class="module-head">
								<h3>Select XML File</h3>
	                        	<!-- <form action="fileuploads" method="post" enctype="multipart/form-data"> -->
	                        	<form action="fileuploads" method="post" enctype="multipart/form-data">
	                        			@csrf
									<label>Select a file:</label>
									<input type="input" name="compname" placeholder="Company Name"></br>
									<input type="input" name="Termina" placeholder="Terminal Code"></br>
									<input type="file" name="myfiles">
									<button type="submit" class="btn btn-primary pull-right">Upload</button>
								</form>
								@if(Session::has('error'))
								    <p class="alert alert-danger">{{ Session::get('error') }}</p>
								@endif
								@if(Session::has('success'))
								    <p class="alert alert-success">{{ Session::get('success') }}</p>
								@endif
							</div>
							
						</div><!--/.module-->

					<br />
						
					</div><!--/.content-->
				</div><!--/.span9-->
			</div>
		</div><!--/.container-->
	</div><!--/.wrapper-->

	<div class="footer">
		<div class="container">
			 

			<b class="copyright">&copy; {{ date('Y') }} NCS -  </b> All rights reserved | Powered by Aluu Microsystems
		</div>
	</div>

	<script src="scripts/jquery-1.9.1.min.js"></script>
	<script src="scripts/jquery-ui-1.10.1.custom.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="scripts/datatables/jquery.dataTables.js"></script>
	<script>
		$(document).ready(function() {
			$('.datatable-1').dataTable();
			$('.dataTables_paginate').addClass("btn-group datatable-pagination");
			$('.dataTables_paginate > a').wrapInner('<span />');
			$('.dataTables_paginate > a:first-child').append('<i class="icon-chevron-left shaded"></i>');
			$('.dataTables_paginate > a:last-child').append('<i class="icon-chevron-right shaded"></i>');
		} );
	</script>
</body>


