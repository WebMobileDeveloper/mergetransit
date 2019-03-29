@extends("layout.customerLayout") @section("contents")
<style>
.error {
    color:red;
}
</style>
<script>
	$(function() {
		//Initialize Select2 Elements
		$(".select2").select2();

		$("#role_form").validate({
			rules: {
				new_pass: {
					required: true,
				},
				con_pass: {
					required: true,
					equalTo: "#role_form .newpass"
				}
			},
			messages: {
				new_pass: {
					required: "Please enter valid account.",
				},
				con_pass: {
					required: "Please enter valid confirm account.",
					equalTo: "it must same as account."
				}
			}
		})
	});
</script>

<div class="content-wrapper" style="min-height: 916px;">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Change Password
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<!-- /.box -->

				<div class="box box-info">
					<!-- form start -->

					<form class="form-horizontal" id="role_form" name="role_form" action="{{url('/sadmin/changepw')}}" method="post">
						{{ csrf_field() }}
						<div class="box-body">
							@if($status = Session::get("status"))
							<div class="alert alert-error form-group">
								<span class="help-error">
									<strong>{{$status}}</strong>
								</span>
							</div>
							<br> @endif
							<div class="form-group custom_input">
								<label class="col-sm-2 control-label">Old Password
									<span class="required">*</span>
								</label>
								<div class="col-xs-4">
									<input class="form-control" name="old_pass" type="password" placeholder="Old Password" required>
								</div>
							</div>
							<div class="form-group custom_input">
								<label class="col-sm-2 control-label">New Password
									<span class="required">*</span>
								</label>
								<div class="col-xs-4">
									<input class="form-control newpass" name="new_pass" type="password" placeholder="New Password" required>
								</div>
							</div>
							<div class="form-group custom_input">
								<label class="col-sm-2 control-label">Confirm Password
									<span class="required">*</span>
								</label>
								<div class="col-xs-4">
									<input class="form-control" name="con_pass" type="password" placeholder="Confirm Password" required>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<!--<button type="submit" class="btn btn-default">Cancel</button>-->
							<button type="submit" class="btn btn-info save_button">Update</button>
						</div>
					</form>
				</div>
				<!-- /.col -->
			</div>
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>

@endsection