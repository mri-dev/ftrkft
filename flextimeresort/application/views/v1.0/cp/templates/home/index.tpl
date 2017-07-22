{if $admin->logged}
	<div class="home">
		<div class="row">
			<div class="col-md-12">
				<h2>Állásajánlatok / Jelentkezések</h2>
				<div class="row">
					<div class="col-md-4">
						<div class="box bordered border-orange">
							<div class="primary text-orange with-link">
								<div class="">
									<div class="n">
										<i class="fa fa-hourglass-half"></i> 1 279
									</div>
									<div class="t">
										feldolgozásra váró jelentkezések
									</div>
								</div>
								<div class="a">
									<a href="{$root}ads/requests/?onlyunpicked=1"><i class="fa fa-external-link-square"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="box bordered border-darkorange">
							<div class="primary text-darkorange with-link">
								<div class="">
									<div class="n">
										<i class="fa fa-retweet"></i> 11
									</div>
									<div class="t">
										általam felvett, nem lezárt jelentkezések
									</div>
								</div>
								<div class="a">
									<a href="{$root}ads/requests/?ownpicked=1&undown=1"><i class="fa fa-external-link-square"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="box bordered border-green">
							<div class="primary text-green with-link">
								<div class="">
									<div class="n">
										<i class="fa fa-check-circle"></i> 240
									</div>
									<div class="t">
										általam felvett, engedélyezett jelentkezések
									</div>
								</div>
								<div class="a">
									<a href="{$root}ads/requests/?onlyaccepted=1"><i class="fa fa-external-link-square"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 offset-md-2">
						<div class="box">
							<div class="primary">
								<div class="n">
									9 990
								</div>
								<div class="t">
									aktív, futó állásajánlat
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="box">
							<div class="primary">
								<div class="n">
									0
								</div>
								<div class="t">
									létrehozott állásajánlat az elmúlt 30 napban
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-5">
				<h2>Felhasználók</h2>
				<div class="row">
					<div class="col-md-12">
						<div class="box">
							<div class="primary splitted centered">
								<div class="text-orange">
									<div class="n">
										999
									</div>
									<div class="t">
										munkavállaló
									</div>
								</div>
								<div class="text-red">
									<div class="n">
										1 920
									</div>
									<div class="t">
										munkaadó
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="box">
							<div class="primary">
								<div class="n">
									1 478
								</div>
								<div class="t">
									aktív felhasználó
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="box">
							<div class="primary">
								<div class="n">
									490
								</div>
								<div class="t">
									új regisztrált felhasználó
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<h2>Üzenetek</h2>
			</div>
		</div>
	</div>
{/if}
