@include('header')
@include('side')


				<div class="span9">
					<div class="content">
						<div class="module">
							<div class="module-head">
								<h3>Records for XML file</h3>
							</div>
							<div class="module-body table">
								<table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	 display" width="100%">
									<thead>
										<tr>
											<th>Company Name</th>
											<th>File Name</th>
											<th>Date</th>
											<th>Terminal Code</th>
											<th>Delete</th>
											<th>View</th>
											<th>Export to Nimasa</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($newviewupd as $newviewupds)
										<tr class="odd gradeX">
											<td>{{ $newviewupds->companyname }}</td>
											<td>{{ $newviewupds->fileext }}</td>
											<td>{{ $newviewupds->dateofupload }}</td>
											<td>{{ $newviewupds->Terminalcode }}</td>
											<td><a href="/viewxml/{{$newviewupds->id }}"><i class="menu-icon icon-trash"></i></a></td>
											<td><a href="/viewxmlviewxml/{{$newviewupds->id }}"><i class="menu-icon icon-trash"></i></a></td>
											<td><a href="/Exportxmls/{{$newviewupds->id }}"><i class="menu-icon icon-trash"></i></a></td>
										@endforeach
									</tbody>
								</table> 
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