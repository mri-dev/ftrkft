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
					{if !empty($cv_szakma_text)}
					<div class="szakma_text">
						{$cv_szakma_text}
					</div>
					{/if}
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
								{if $has_access_contact}
									{$cv_email}
								{else}
									<span class="restrict-access">
										*** {lang text="Hozzáférés szükséges"} ***
									</span>
								{/if}
							</div>
						</div>
						{/if}
						{if $cv_telefon}
						<div class="cv-row">
							<div class="label">
								{lang text="Telefonszám"}
							</div>
							<div class="value">
								{if $has_access_contact}
									{$cv_telefon}
								{else}
									<span class="restrict-access">
										*** {lang text="Hozzáférés szükséges"} ***
									</span>
								{/if}
							</div>
						</div>
						{/if}
						<div class="data-title">
							{lang text="Közösségi oldalak"}
						</div>
						<div class="socials">
							{if $has_access_contact}
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
							{else}
								<span class="restrict-access">
									*** {lang text="Hozzáférés szükséges"} ***
								</span>
							{/if}
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
				{if empty($vegzettsegek)}
				<span class="no-data-setted">(!) {lang text="Az adat nem lett megadva."}</span>
				{else}
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
				{/if}
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
				{if empty($kepesitesek)}
				<span class="no-data-setted">(!) {lang text="Az adat nem lett megadva."}</span>
				{else}
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
				{/if}
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
				{assign var=jogositvanyok value=$cv->getTermValues('jogositvanyok', $u->getValue('jogositvanyok'), true)}
				{if empty($jogositvanyok)}
				<span class="no-data-setted">(!) {lang text="Az adat nem lett megadva."}</span>
				{else}
				{foreach from=$jogositvanyok item=jogositvany}
					<div class="simple-list-item">{$jogositvany.neve}</div>
				{/foreach}
				{/if}
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
				{if empty($szamitogepismeretek)}
				<span class="no-data-setted">(!) {lang text="Az adat nem lett megadva."}</span>
				{else}
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
				{/if}
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
				{if empty($nyelvismeretek)}
					<span class="no-data-setted">(!) {lang text="Az adat nem lett megadva."}</span>
				{else}
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
				{/if}
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
	<div class="sub-group cv-row cv-a-top">
		<div class="cv-col c3">
			<div class="title">
				<div class="t">
					{lang text="Megszerzett munkatapasztalat"}
				</div>
			</div>
		</div>
		<div class="cv-col c7">
			{assign var=munkatapasztalat value=$cv->getTermValues('munkatapasztalat', $u->getValue('munkatapasztalat'))}
			{if !$munkatapasztalat}
				<span class="no-data-setted">(!) {lang text="Az adat nem lett megadva."}</span>
			{else}
				{$munkatapasztalat.neve}
			{/if}
		</div>
	</div>
	<div class="sub-group cv-row cv-a-top">
		<div class="cv-col c3">
			<div class="title">
				<div class="t">
					{lang text="Munkatapasztalatok, korábbi munkahelyek"}
				</div>
			</div>
		</div>
		<div class="cv-col c7">
			{assign var=munkatapasztalatok value=$cv->getModul('munkatapasztalat', 'munkatapasztalat')}
			{if empty($munkatapasztalatok)}
			<span class="no-data-setted">(!) {lang text="Az adat nem lett megadva."}</span>
			{else}
			{foreach from=$munkatapasztalatok item=mk}
			<div class="modul-groups">
				<div class="cv-row modul-group-item">
					<div class="label">
						{lang text="Év"}
					</div>
					<div class="value">
						{$mk.startdate.year.value} &mdash; {$mk.enddate.year.value}
					</div>
				</div>

				{if !empty($mk.munkakor.value)}
				<div class="cv-row modul-group-item">
					<div class="label">
						{lang text="Szakterület/Munkakör"}
					</div>
					<div class="value">
						{$mk.munkakor.value}
					</div>
				</div>
				{/if}
				{if !empty($mk.beosztas.value)}
				<div class="cv-row modul-group-item">
					<div class="label">
						{lang text="Beosztás megnevezése"}
					</div>
					<div class="value">
						{$mk.beosztas.value}
					</div>
				</div>
				{/if}
				{if !empty($mk.feladatok.value)}
				<div class="cv-row modul-group-item">
					<div class="label">
						{lang text="Beosztásban végzett feladatok"}
					</div>
					<div class="value">
						{$mk.feladatok.value}
					</div>
				</div>
				{/if}
				{if !empty($mk.munkavegzes_helye.value)}
				<div class="cv-row modul-group-item">
					<div class="label">
						{lang text="Munkavégzés helye"}
					</div>
					<div class="value">
						{$mk.munkavegzes_helye.value}
					</div>
				</div>
				{/if}
				{if !empty($mk.ceg_neve.value)}
				<div class="cv-row modul-group-item">
					<div class="label">
						{lang text="Cég neve"}
					</div>
					<div class="value">
						{$mk.ceg_neve.value}
					</div>
				</div>
				{/if}
				{if !empty($mk.beosztasi_szint.value)}
				<div class="cv-row modul-group-item">
					<div class="label">
						{lang text="Beosztás szintje"}
					</div>
					<div class="value">
						{$mk.beosztasi_szint.value}
					</div>
				</div>
				{/if}
				{if !empty($mk.startdate.year.value)}
				<div class="cv-row modul-group-item">
					<div class="label">
						{lang text="Kezdés ideje"}
					</div>
					<div class="value">
						{$mk.startdate.year.value}{if !empty($mk.startdate.month.value)}/{$mk.startdate.month.value}. {lang text="hó"}{/if}
					</div>
				</div>
				{/if}
				{if !empty($mk.enddate.year.value) && $mk.folyamatban.value == '0'}
				<div class="cv-row modul-group-item">
					<div class="label">
						{lang text="Befejezés ideje"}
					</div>
					<div class="value">
						{$mk.enddate.year.value}{if !empty($mk.enddate.month.value)}/{$mk.enddate.month.value}. {lang text="hó"}{/if}
					</div>
				</div>
				{/if}
				{if !empty($mk.folyamatban.value) && $mk.folyamatban.value == '1'}
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
			{/if}
		</div>
	</div>
</div>
<div class="data-group">
	<div class="cv-title">
		<h2>{lang text="Elvárások és igények"}</h2>
	</div>
	<div class="sub-group cv-row cv-a-top">
		<div class="cv-col c3">
			<div class="title">
				<div class="t">
					{lang text="Bérigény (bruttó)"}
				</div>
			</div>
		</div>
		<div class="cv-col c7">
			{assign var=igeny_brutto value=$u->getValue('fizetesi_igeny')}
			{if !$igeny_brutto}
				<span class="no-data-setted">(!) {lang text="Hiányzó adat."}</span>
			{else}
				{$igeny_brutto|number_format:0:"":" "} HUF
			{/if}
		</div>
	</div>
	{if !empty($u->getValue('megyeaholdolgozok'))}
	<div class="sub-group cv-row cv-a-top">
		<div class="cv-col c3">
			<div class="title">
				<div class="t">
					{lang text="Megyék, ahol munkát vállalna"}
				</div>
			</div>
		</div>
		<div class="cv-col c7">
			{assign var=megyeaholdolgozok value=$cv->getTermValues('megyek', $u->getValue('megyeaholdolgozok'),true)}
			{if empty($megyeaholdolgozok)}
			<span class="no-data-setted">(!) {lang text="Az adat nem lett megadva."}</span>
			{else}
				{foreach from=$megyeaholdolgozok item=megye_dolgoz}
					<div class="simple-list-item">{$megye_dolgoz.neve}</div>
				{/foreach}
			{/if}
			<div class="clearfix"></div>
		</div>
	</div>
	{/if}
	{if !empty($u->getValue('elvaras_munkateruletek'))}
	<div class="sub-group cv-row cv-a-top">
		<div class="cv-col c3">
			<div class="title">
				<div class="t">
					{lang text="Munkaterületek, ahol munkát vállalna"}
				</div>
			</div>
		</div>
		<div class="cv-col c7">
			{assign var=elvaras_munkateruletek value=$cv->getTermValues('munkakorok', $u->getValue('elvaras_munkateruletek'),true)}
			{if empty($elvaras_munkateruletek)}
			<span class="no-data-setted">(!) {lang text="Az adat nem lett megadva."}</span>
			{else}
			{foreach from=$elvaras_munkateruletek item=munkateruletek}
				<div class="simple-list-item">{$munkateruletek.neve}</div>
			{/foreach}
			{/if}
			<div class="clearfix"></div>
		</div>
	</div>
	{/if}
	{if !empty($u->getValue('elvaras_munkakorok'))}
	<div class="sub-group cv-row cv-a-top">
		<div class="cv-col c3">
			<div class="title">
				<div class="t">
					{lang text="Lehetséges munkakörök"}
				</div>
			</div>
		</div>
		<div class="cv-col c7">
			{assign var=elvaras_munkakorok value=$cv->getTermValues('munkakorok', $u->getValue('elvaras_munkakorok'),true)}
			{if empty($elvaras_munkakorok)}
			<span class="no-data-setted">(!) {lang text="Az adat nem lett megadva."}</span>
			{else}
			{foreach from=$elvaras_munkakorok item=imk}
				<div class="simple-list-item">{$imk.parent.neve} / <strong>{$imk.neve}</strong></div>
			{/foreach}
			{/if}
			<div class="clearfix"></div>
		</div>
	</div>
	{/if}
	{if !empty($cv_igenyek_egyeb_munkakorok) && $cv_igenyek_egyeb_munkakorok}
	<div class="sub-group cv-row cv-a-top">
		<div class="cv-col c3">
			<div class="title">
				<div class="t">
					{lang text="Egyéb munkakörök"}
				</div>
			</div>
		</div>
		<div class="cv-col c7">
			{$cv_igenyek_egyeb_munkakorok}
		</div>
	</div>
	{/if}
	{assign var=munkaba_allas_ideje value=$u->getValue('munkaba_allas_ideje')}
	{if $munkaba_allas_ideje}
		<div class="sub-group cv-row cv-a-top">
			<div class="cv-col c3">
				<div class="title">
					<div class="t">
						{lang text="Lehetséges munkába állás ideje"}
					</div>
				</div>
			</div>
			<div class="cv-col c7">
				{$munkaba_allas_ideje|date_format:"%Y. %m. %d."}
			</div>
		</div>
	{else}
		<span class="no-data-setted">(!) {lang text="Hiányzó adat."}</span>
	{/if}
	{if !empty($cv_igenyek_egyeb) && $cv_igenyek_egyeb}
	<div class="sub-group cv-row cv-a-top">
		<div class="cv-col c3">
			<div class="title">
				<div class="t">
					{lang text="Egyéb"}
				</div>
			</div>
		</div>
		<div class="cv-col c7">
			{$cv_igenyek_egyeb}
		</div>
	</div>
	{/if}
</div>

<div class="data-group">
	<div class="cv-title">
		<h2>{lang text="Dokumentumok"}</h2>
	</div>
	{if !empty($cv_kulso_oneletrajz_url) && $cv_kulso_oneletrajz_url}
	<div class="sub-group cv-row cv-a-top">
		<div class="cv-col c3">
			<div class="title">
				<div class="t">
					{lang text="Külső önéletrajz linkje"}
				</div>
			</div>
		</div>
		<div class="cv-col c7">
			<a href="{$cv_kulso_oneletrajz_url}" target="_blank"><i class="fa fa-external-link"></i> {$cv_kulso_oneletrajz_url}</a>
		</div>
	</div>
	{/if}
	{if !empty($mycv)}
	<div class="sub-group cv-row cv-a-top">
		<div class="cv-col c3">
			<div class="title">
				<div class="t">
					{lang text="Letölthető egyéb önéletrajz"}
				</div>
			</div>
		</div>
		<div class="cv-col c7">
			<a href="{$mycv.filepath}" target="_blank"><i class="fa fa-download"></i> <strong>{$cv_nev} {$mycv.name}</strong> <small>(.{$mycv.file_type}, {$mycv.file_size} KB)</small></a>
			<div class="show-on-print">
				({$settings.page_url|cat:$mycv.filepath})
			</div>
		</div>
	</div>
	{/if}
	{if !empty($documents)}
	<div class="sub-group cv-row cv-a-top">
		<div class="cv-col c3">
			<div class="title">
				<div class="t">
					{lang text="Egyéb dokumentumok"}
				</div>
			</div>
		</div>
		<div class="cv-col c7">
			<div class="documents">
				{foreach from=$documents item=docs}
					<a href="{$docs.filepath}" target="_blank"><i class="fa fa-file-o"></i> <strong>{$docs.name}</strong> <small>(.{$docs.file_type}, {$docs.file_size})</small></a>
					<div class="show-on-print">
						({$settings.page_url|cat:$docs.filepath})
					</div>
				{/foreach}
			</div>
		</div>
	</div>
	{/if}
</div>
<div class="footer-copy">
	<div class="copy">
		{lang text="Az önéletrajz a(z) %page% rendszerével készült" page=$settings.page_title}
	</div>
	<div class="url">
		{$settings.page_url}/u/{$smarty.get.uid}/{$smarty.get.nameslug}
	</div>
</div>
