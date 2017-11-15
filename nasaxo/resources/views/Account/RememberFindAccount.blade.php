<!-- start Rfind account -->
@extends('Account.Remember')
@section('content')
<form enctype='multipart/form-data' action="{!! url('remember/type') !!}" name="frmFind" method="POST">
	{{csrf_field()}}	
	<div class="rememver-content">
		<div class="infomation-content">
			<h4>Tìm tài khoản của bạn</h4>
			<div class="find-content">
				<span class="Remember-email">Vui lòng nhập tên tài khoản hoặc email để tìm kiếm tài khoản</span>
				<input class="txtInputRemember" type="text" name="txtEmail" id="txtEmail" placeholder="Tên tài khoản hoặc email">
			</div>
			<div class="footer-remember">
				<button class="footerBtn btn-success " type="submit" name="btnSuccess" id="btnSuccess">Tìm kiếm</button>
				<button class="footerBtn btn-cancel" type="button" name="btnCancel" id="btnCancel">Hủy</button>
			</div>
		</div>
	</div>
</form>
<!-- end find account -->
@stop
@section('script')
@parent
<script type="text/javascript" src="{!! url('public/js/Account/RememberFindAccount.js') !!}"></script>
@stop