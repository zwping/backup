<div class="box">
	<div class="box-header">
		<h3 class="box-title">已存在的备份</h3>

		<div class="box-tools">
			<button data-url="{{ route('dcat.admin.backup-run') }}" 
				class="btn btn-primary dialog-create btn-outline backup-run"
				data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order"
			>
				<i class="feather icon-plus"></i>
				<span class="d-none d-sm-inline">&nbsp; 备份</span>
			</button>
		</div>
	</div>
	<!-- /.box-header -->
	<div class="box-body table-responsive no-padding">
		<table class="table table-hover">
			<tbody>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Disk</th>
				<th>Reachable</th>
				<th>Healthy</th>
				<th># of backups</th>
				<th>Newest backup</th>
				<th>Used storage</th>
			</tr>
			@foreach($backups as $index => $backup)
			<tr data-toggle="collapse" data-target="#trace-{{$index+1}}" style="cursor: pointer;">
				<td>{{ $index+1 }}.</td>
				<td>{{ @$backup[0] }}</td>
				<td>{{ @$backup['disk'] }}</td>
				<td>{{ @$backup[1] }}</td>
				<td>{{ @$backup[2] }}</td>
				<td>{{ @$backup['amount'] }}</td>
				<td>{{ @$backup['newest'] }}</td>
				<td>{{ @$backup['usedStorage'] }}</td>
			</tr>
			<tr class="collapse" id="trace-{{$index+1}}">
				<td colspan="8">
					<ul class="todo-list ui-sortable">
						@foreach($backup['files'] as $file)
						<li class="{{ $file['name'] }}">
							<span class="text">{{ $file['path'] }}</span> <sub>{{ $file['size'] }}</sub>
							<!-- Emphasis label -->

							<div class="tools">
								<a target="_blank" href="{{ route('dcat.admin.backup-download', ['disk' => $backup['disk'], 'file' => $backup[0].'/'.$file['path']]) }}"><i class="fa fa-download"></i></a>
								<a data-name="{{ $file['name'] }}" data-url="{{ route('dcat.admin.backup-delete', ['disk' => $backup['disk'], 'file' => $backup[0].'/'.$file['path']]) }}" class="backup-delete" ><i class="fa fa-trash-o"></i></a>
							</div>
						</li>
						@endforeach
					</ul>
				</td>
			</tr>
			@endforeach

			</tbody>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<div class="box box-default output-box hide">
	<div class="box-header with-border">
		<i class="fa fa-terminal"></i>

		<h3 class="box-title">Output</h3>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<pre class="output-body"></pre>
	</div>
	<!-- /.box-body -->
</div>
<script require="@zwping.backup">
    $(".backup-run").click(function() {
        let btn = $(this);
		let url = btn.data('url')

        btn.buttonLoading();
        Dcat.NP.start();
		$.post({
			url,
			data: {
				_token: Dcat.token,
			},
			success: (result) => {
				if (result.status) {
					// setTimeout(() => {
					// 	Dcat.reload()
					// }, 1000);
				}
				$('.output-box').removeClass('hide');
				$('.output-box .output-body').html(result.message)

				btn.buttonLoading(false);
				Dcat.NP.done();
			},
		})

        return false;
    });

    $(".backup-delete").click(function() {
		let btn		= $(this);
		let url		= btn.data('url')
		let name	= btn.data('name')

		Dcat.confirm('确认删除备份文件', `${name}.zip`, () => {
			$.delete({
				url,
				data: {
					_token: Dcat.token,
				},
				success: (result) => {
					// Dcat.reload()
					if (result.status) {
						$(`.${name}`).remove()
						Dcat.success(result.message)
					} else {
						Dcat.error(result.message)
					}
				},
			})
		})

		return false;
		});
</script>