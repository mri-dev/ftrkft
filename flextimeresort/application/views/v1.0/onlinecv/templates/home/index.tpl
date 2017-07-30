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
						{assign var=allampolgarsag value=$cv->getTermValues('allampolgarsag', $u->getValue('allampolgarsag'))}
						{if $allampolgarsag}
						<div class="cv-row">
							<div class="label">
								{lang text="Állampolgárság"}
							</div>
							<div class="value">
								{$allampolgarsag.neve}
							</div>
						</div>
						{/if}
						{assign var=anyanyelv value=$cv->getTermValues('anyanyelv', $u->getValue('anyanyelv'))}
						{if $anyanyelv}
						<div class="cv-row">
							<div class="label">
								{lang text="Anyanyelv"}
							</div>
							<div class="value">
								{$anyanyelv.neve}
							</div>
						</div>
						{/if}
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
					<div class="t">
						{lang text="Legmagasabb iskolai végzettség"}
					</div>
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
					<div class="t">
					{lang text="Végzettségek"}
					</div>
				</div>
			</div>
			<div class="cv-col c7">
				{assign var=vegzettsegek value=$cv->getModul('vegzettseg', 'vegzettseg')}
				{foreach from=$vegzettsegek item=vegzettseg}
				<div class="modul-groups">
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Év"}
						</div>
						<div class="value">
							{$vegzettseg.startdate.year.value} &mdash; {$vegzettseg.enddate.year.value}
						</div>
					</div>
					{if !empty($vegzettseg.vegzettseg_szint.value)}
						<div class="cv-row modul-group-item">
							<div class="label">
								{lang text="Végzettség szintje"}
							</div>
							<div class="value">
								{$vegzettseg.vegzettseg_szint.value}
							</div>
						</div>
					{/if}
					{if !empty($vegzettseg.szakirany.value)}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Terület / Szakirányzat"}
						</div>
						<div class="value">
							{$vegzettseg.szakirany.value}
						</div>
					</div>
					{/if}
					{if !empty($vegzettseg.intezmeny.value)}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Intézmény neve"}
						</div>
						<div class="value">
							{$vegzettseg.intezmeny.value}
						</div>
					</div>
					{/if}
					{if !empty($vegzettseg.keszsegek.value)}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Végzettség készségei"}
						</div>
						<div class="value">
							{$vegzettseg.keszsegek.value}
						</div>
					</div>
					{/if}
					{if !empty($vegzettseg.startdate.year.value)}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Kezdés ideje"}
						</div>
						<div class="value">
							{$vegzettseg.startdate.year.value}{if !empty($vegzettseg.startdate.month.value)}/{$vegzettseg.startdate.month.value}. {lang text="hó"}{/if}
						</div>
					</div>
					{/if}
					{if !empty($vegzettseg.enddate.year.value) && $vegzettseg.folyamatban.value == '0'}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Befejezés ideje"}
						</div>
						<div class="value">
							{$vegzettseg.enddate.year.value}{if !empty($vegzettseg.enddate.month.value)}/{$vegzettseg.enddate.month.value}. {lang text="hó"}{/if}
						</div>
					</div>
					{/if}
					{if !empty($vegzettseg.folyamatban.value) && $vegzettseg.folyamatban.value == '1'}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Folyamatban"}
						</div>
						<div class="value">
							{lang text="Igen"}
						</div>
					</div>
					{/if}
				</div>
				{/foreach}
			</div>
		</div>
		<div class="sub-group cv-row cv-a-top">
			<div class="cv-col c3">
				<div class="title">
					<div class="t">
						{lang text="További képzettség, tréning, tanfolyam"}
					</div>
				</div>
			</div>
			<div class="cv-col c7">
				{assign var=kepesitesek value=$cv->getModul('vegzettseg', 'kepesitesek')}
				{foreach from=$kepesitesek item=kepesites}
				<div class="modul-groups">
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Év"}
						</div>
						<div class="value">
							{$kepesites.enddate.year.value}{if !empty($kepesites.enddate.month.value)}/{$kepesites.enddate.month.value}. {lang text="hó"}{/if}
						</div>
					</div>
					{if !empty($kepesites.megnevezes.value)}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Megnevezés"}
						</div>
						<div class="value">
							{$kepesites.megnevezes.value}
						</div>
					</div>
					{/if}
					{if !empty($kepesites.intezmeny.value)}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Intézmény neve"}
						</div>
						<div class="value">
							{$kepesites.intezmeny.value}
						</div>
					</div>
					{/if}
					{if !empty($kepesites.keszsegek.value)}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Végzettség készségei"}
						</div>
						<div class="value">
							{$kepesites.keszsegek.value}
						</div>
					</div>
					{/if}
				</div>
				{/foreach}
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
					<div class="t">
						{lang text="Jogosítványok"}
					</div>
				</div>
			</div>
			<div class="cv-col c7">
				{assign var=jogositvanyok value=$cv->getTermValues('jogositvanyok', $u->getValue('jogositvanyok'))}
				{foreach from=$jogositvanyok item=jogositvany}
					<div class="simple-list-item">{$jogositvany.neve}</div>
				{/foreach}
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="sub-group cv-row cv-a-top">
			<div class="cv-col c3">
				<div class="title">
					<div class="t">
						{lang text="Számítógépes ismeretek"}
					</div>
				</div>
			</div>
			<div class="cv-col c7">
				{assign var=szamitogepismeretek value=$cv->getModul('ismeretek', 'szamitogepes')}
				{foreach from=$szamitogepismeretek item=szgi}
				<div class="modul-groups">
					{if !empty($szgi.szamitastechnikai_ismeret.value)}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Szakterület"}
						</div>
						<div class="value">
							{$szgi.szamitastechnikai_ismeret.value}
						</div>
					</div>
					{/if}
					{if !empty($szgi.tudasszint.value)}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Tudásszint"}
						</div>
						<div class="value">
							{$szgi.tudasszint.value}
						</div>
					</div>
					{/if}
					{if !empty($szgi.tapasztalat_ev.value)}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Tapasztalat"}
						</div>
						<div class="value">
							{$szgi.tapasztalat_ev.value} {lang text="év"}
						</div>
					</div>
					{/if}
				</div>
				{/foreach}
			</div>
		</div>
		<div class="sub-group cv-row cv-a-top">
			<div class="cv-col c3">
				<div class="title">
					<div class="t">
						{lang text="Nyelvismeret"}
					</div>
				</div>
			</div>
			<div class="cv-col c7">
				{assign var=nyelvismeretek value=$cv->getModul('ismeretek', 'nyelvismeret')}
				{foreach from=$nyelvismeretek item=nyelv}
				<div class="modul-groups">
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Nyelv"}
						</div>
						<div class="value">
							{$nyelv.nyelv.value}
						</div>
					</div>
					{if !empty($nyelv.szobeli_szint.value)}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Szóbeli készség szintje"}
						</div>
						<div class="value">
							{$nyelv.szobeli_szint.value}
						</div>
					</div>
					{/if}
					{if !empty($nyelv.irasbeli_szint.value)}
					<div class="cv-row modul-group-item">
						<div class="label">
							{lang text="Írásbeli készség szintje"}
						</div>
						<div class="value">
							{$nyelv.irasbeli_szint.value}
						</div>
					</div>
					{/if}
				</div>
				{/foreach}
			</div>
		</div>
		{if !empty($cv_ismeretek_egyeb) && $cv_ismeretek_egyeb}
		<div class="sub-group cv-row cv-a-top">
			<div class="cv-col c3">
				<div class="title">
					<div class="t">
						{lang text="Egyéb"}
					</div>
				</div>
			</div>
			<div class="cv-col c7">
				{$cv_ismeretek_egyeb}
			</div>
		</div>
		{/if}
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
