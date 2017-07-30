<div class="cv-title">
	<h1>{lang text="Önéletrajz"}</h1>
</div>
<div class="main-data">
	<div class="cv-row cv-a-top">
		<div class="cv-col c3 profil-img">
			<div class="img">
				<img src="{$u->getProfilImg()}" alt="">
			</div>
		</div>
		<div class="cv-col c7">
			<div class="datalines">
				<div class="name-pop">
					{$u->getName()}
				</div>
				<div class="cv-row cv-a-top">
					<div class="cv-col c5">
						<div class="data-title">
							{lang text="Személyes"}
						</div>
						<div class="cv-row">
							<div class="label">
								{lang text="Született"}
							</div>
							<div class="value">
								{$u->getBirthDate()}
							</div>
						</div>
						<div class="cv-row">
							<div class="label">
								{lang text="Állampolgárság"}
							</div>
							<div class="value">
								{$u->getTermValues('allampolgarsag', $u->getValue('allampolgarsag')|intval)}
							</div>
						</div>
						<div class="cv-row">
							<div class="label">
								{lang text="Anyanyelv"}
							</div>
							<div class="value">
								{$u->getTermValues('anyanyelv', $u->getValue('anyanyelv')|intval)}
							</div>
						</div>
						<div class="cv-row">
							<div class="label">
								{lang text="Lakcím"}
							</div>
							<div class="value">
								{$u->getAddress()}
							</div>
						</div>
					</div>
					<div class="cv-col c5">
						<div class="data-title">
							{lang text="Kapcsolat"}
						</div>
						<div class="cv-row">
							<div class="label">
								{lang text="E-mail cím"}
							</div>
							<div class="value">
								{$u->getEmail()}
							</div>
						</div>
						<div class="cv-row">
							<div class="label">
								{lang text="Telefonszám"}
							</div>
							<div class="value">
								{$u->getPhone()}
							</div>
						</div>
						<div class="data-title">
							{lang text="Közösségi oldalak"}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<pre>{$u->user|print_r}</pre>
