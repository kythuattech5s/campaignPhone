<div id="changepass" class="modal fade" role="dialog">
	<form onsubmit="changePassNow();return false;" action="" method="post" class="form-group">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">{{trans('db::change_pass')}}</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<label for="" class="my-2">{{trans('db::old_pass')}}</label>
					<input type="password" name="current_password" class="form-control" autocomplete/>
					<label for="" class="my-2">{{trans('db::new_pass')}}</label>
					<input type="password" name="password" class="form-control" autocomplete/>
					<label for="" class="my-2">{{trans('db::re_new_pass')}}</label>
					<input type="password" name="password_confirmation" class="form-control" autocomplete/>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success" >{{trans('db::change')}}</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	function changePassNow(){
		if(!validateChangePass()){
			return false;
		}
		$('#changepass').modal('hide');
		$.ajax({
			url: '{{$admincp}}/changepass',
			type: 'POST',
			data: $('#changepass form').serialize(),
		})
		.done(function(data) {
			try{
				var json = JSON.parse(data);
				if(json.code==200){
					window.location.href="{{$admincp}}/logout";
				}  
			}
			catch(ex){}
		})
		.fail(function() {
		})
		.always(function() {
		});
	}
	function validateChangePass(){
		try{
			var cp = $('#changepass input[name=current_password]').val().trim();
			var np = $('#changepass input[name=password]').val().trim();
			var rp = $('#changepass input[name=password_confirmation]').val().trim();
			if(cp=="" || np ==""||rp==""){
				bootbox.alert("{{trans('db::please_input')}}");
				return false;
			}
			else if(np!=rp){
				bootbox.alert("{{trans('db::wrong_re_pass')}}"); 
				return false;
			}  
			return true;
		}
		catch(ex){
		}
	}
</script>