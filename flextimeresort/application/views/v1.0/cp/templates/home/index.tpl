{if $admin->logged}
	<div class="home">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-5">
						<h2>Állásajánlatok</h2>
						<div class="row">
							<div class="col-md-6">
								<div class="box">
									<div class="primary">
										<div class="n">
											{$dashboardinfo.ads.aktiv|intval}
										</div>
										<div class="t">
											aktív állásajánlat
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="box">
									<div class="primary">
										<div class="n">
											{$dashboardinfo.ads.30d_created|intval}
										</div>
										<div class="t">
											állásajánlat az elmúlt 30 napban
										</div>
									</div>
								</div>
							</div>
						</div>
						<h2>Jelentkezések</h2>
						<div class="row">
							<div class="col-md-12">
								<div class="box bordered border-orange">
									<div class="primary text-orange with-link">
										<div class="">
											<div class="n">
												<i class="fa fa-hourglass-half"></i> {$dashboardinfo.requests.unpicked|intval}
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
							<div class="col-md-12">
								<div class="box bordered border-darkorange">
									<div class="primary text-darkorange with-link">
										<div class="">
											<div class="n">
												<i class="fa fa-retweet"></i> {$dashboardinfo.requests.ownpicked_inprogress|intval}
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
							<div class="col-md-12">
								<div class="box bordered border-green">
									<div class="primary text-green with-link">
										<div class="">
											<div class="n">
												<i class="fa fa-check-circle"></i> {$dashboardinfo.requests.ownpicked_done|intval}
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
					</div>
					<div class="col-md-7">
						<h2>Üzenetek</h2>
						<div class="row">
							<div class="col-md-12">
								<div class="box">
									<div class="primary splitted centered">
										<div class="text-orange">
											<div class="n">
												{$dashboardinfo.messages.unreaded|intval}
											</div>
											<div class="t">
												olvasatlan üzenet
											</div>
										</div>
										<div class="text-red">
											<div class="n">
												{$dashboardinfo.messages.myunreaded|intval}
											</div>
											<div class="t">
												olvasatlan, általam nyitott üzenet
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<h2>Felhasználók</h2>
						<div class="row">
							<div class="col-md-12">
								<div class="box">
									<div class="primary splitted centered">
										<div class="text-orange">
											<div class="n">
												{$dashboardinfo.user.munkavallalo|intval}
											</div>
											<div class="t">
												munkavállaló
											</div>
										</div>
										<div class="text-red">
											<div class="n">
												{$dashboardinfo.user.munkaado|intval}
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
											{$dashboardinfo.user.active|intval}
										</div>
										<div class="t">
											aktív felhasználó (30 nap)
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="box">
									<div class="primary">
										<div class="n">
											{$dashboardinfo.user.newreg|intval}
										</div>
										<div class="t">
											új regisztrált felhasználó (30 nap)
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">

			</div>
			<div class="col-md-6">
			</div>
		</div>
	</div>
{/if}
