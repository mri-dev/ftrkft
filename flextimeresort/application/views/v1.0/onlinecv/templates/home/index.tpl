<div class="cv-title">
	<h1>{lang text="Önéletrajz"}</h1>
</div>
<div class="main-data">
	<div class="cv-row cv-a-top">
		<div class="cv-col c2 profil-img">
			<div class="img">
				<img src="{$cv->ProfilImg()}" alt="">
			</div>
		</div>
		<div class="cv-col c8">
			<div class="datalines">
				<div class="name-pop">
					{$cv_nev}
				</div>
				<div class="cv-row cv-a-top">
					<div class="cv-col c5">
						<div class="data-title">
							{lang text="Személyes"}
						</div>
						{if $cv_szuletett}
						<div class="cv-row">
							<div class="label">
								{lang text="Született"}
							</div>
							<div class="value">
								{$cv_szuletett}
							</div>
						</div>
						{/if}
						<div class="cv-row">
							<div class="label">
								{lang text="Állampolgárság"}
							</div>
							<div class="value">
							</div>
						</div>
						<div class="cv-row">
							<div class="label">
								{lang text="Anyanyelv"}
							</div>
							<div class="value">
							</div>
						</div>
						{if $cv_cim}
						<div class="cv-row">
							<div class="label">
								{lang text="Lakcím"}
							</div>
							<div class="value">
								{$cv_cim}
							</div>
						</div>
						{/if}
					</div>
					<div class="cv-col c5">
						<div class="data-title">
							{lang text="Kapcsolat"}
						</div>
						{if $cv_email}
						<div class="cv-row">
							<div class="label">
								{lang text="E-mail cím"}
							</div>
							<div class="value">
								{$cv_email}
							</div>
						</div>
						{/if}
						{if $cv_telefon}
						<div class="cv-row">
							<div class="label">
								{lang text="Telefonszám"}
							</div>
							<div class="value">
								{$cv_telefon}
							</div>
						</div>
						{/if}
						<div class="data-title">
							{lang text="Közösségi oldalak"}
						</div>
						<div class="socials">
							<div class="show-on-print">
								{if $cv_social_facebook}
								<div class="social-item-print">
									{lang text="Facebook"}:
									<div><strong>{$cv_social_facebook}</strong></div>
								</div>
								{/if}
								{if $cv_social_twitter}
								<div class="social-item-print">
									{lang text="Twitter"}:
									<div><strong>{$cv_social_twitter}</strong></div>
								</div>
								{/if}
								{if $cv_social_linkedin}
								<div class="social-item-print">
									{lang text="LinkedIn"}:
									<div><strong>{$cv_social_linkedin}</strong></div>
								</div>
								{/if}
							</div>
							<div class="hide-on-print">
								{if $cv_social_facebook}
								<a target="_blank" class="social social-facebook" href="{$cv_social_facebook}"><i class="fa fa-facebook"></i></a>
								{/if}
								{if $cv_social_twitter}
								<a target="_blank" class="social social-twitter" href="{$cv_social_twitter}"><i class="fa fa-twitter"></i></a>
								{/if}
								{if $cv_social_linkedin}
								<a target="_blank" class="social social-linkedin" href="{$cv_social_linkedin}"><i class="fa fa-linkedin"></i></a>
								{/if}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="data-group">
	<div class="cv-title">
		<h2>{lang text="Végzettség, szakképesítések"}</h2>
	</div>
	<div class="group-wrapper">
		<div class="sub-group cv-row">
			<div class="cv-col c3">
				<div class="title">
					{lang text="Legmagasabb iskolai végzettség"}
				</div>
			</div>
			<div class="cv-col c7">
				{assign var=iskolai_vegzettseg value=$cv->getTermValues('iskolai_vegzettsegi_szintek', $u->getValue('iskolai_vegzettsegi_szintek'))}
				{$iskolai_vegzettseg.neve}
			</div>
		</div>
		<div class="sub-group cv-row cv-a-top">
			<div class="cv-col c3">
				<div class="title">
					{lang text="Végzettségek"}
				</div>
			</div>
			<div class="cv-col c7">
				{assign var=vegzettsegek value=$cv->getModul('vegzettseg', 'vegzettseg')}
				<div class="modul-groups">
				{foreach from=$vegzettsegek item=vegzettseg}
					<div class="cv-row modul-group-item cv-a-top">
						<div class="label">
							{lang text="Év"}
						</div>
						<div class="value">
							{$vegzettseg.startdate.year.value} &mdash; {$vegzettseg.enddate.year.value}
						</div>
					</div>
					<div class="cv-row modul-group-item cv-a-top">
						<div class="label">
							{lang text="Végzettség szintje"}
						</div>
						<div class="value">
							{$vegzettseg.vegzettseg_szint.value}
						</div>
					</div>
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Terület / Szakirányzat"}
						</div>
						<div class="value">
							{$vegzettseg.szakirany.value}
						</div>
					</div>
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Intézmény neve"}
						</div>
						<div class="value">
							{$vegzettseg.intezmeny.value}
						</div>
					</div>
				{/foreach}
				</div>
			</div>
		</div>
		<div class="sub-group cv-row">
			<div class="cv-col c3">
				<div class="title">
					{lang text="További képzettség, tréning, tanfolyam"}
				</div>
			</div>
			<div class="cv-col c7">

			</div>
		</div>
	</div>
</div>
<div class="data-group">
	<div class="cv-title">
		<h2>{lang text="Ismeretek"}</h2>
	</div>
	<div class="group-wrapper">
		<div class="sub-group cv-row">
			<div class="cv-col c3">
				<div class="title">
					{lang text="Jogosítványok"}
				</div>
			</div>
			<div class="cv-col c7">
				{assign var=jogositvanyok value=$cv->getTermValues('jogositvanyok', $u->getValue('jogositvanyok'))}
				{foreach from=$jogositvanyok item=jogositvany}
					<span class="simple-list-item">{$jogositvany.neve}</span>
				{/foreach}
			</div>
		</div>
	</div>
</div>
<div class="data-group">
	<div class="cv-title">
		<h2>{lang text="Munkatapasztalatok"}</h2>
	</div>
</div>
<div class="data-group">
	<div class="cv-title">
		<h2>{lang text="Elvárások és igények"}</h2>
	</div>
</div>
<pre>{$u->user|print_r}</pre>
