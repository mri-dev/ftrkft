<h1>Nyelvi fájlok szerkesztése</h1>
<div class="translator-page">
  <div ng-app="Translator" ng-controller="Translate" ng-init="init()">
    <div class="avaiable-langs" ng-show="languages.length!=0">
      <h2>Elérhető nyelvek:</h2>
      <table class="table table-striped table-bordered">
        <thead class="thead-inverse">
          <tr>
            <th class="center">Kód</th>
            <th class="text">Nyelv elnevezés</th>
            <th>Státusz</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="l in languages">
            <td>[[l.code]]</td>
            <td><strong>[[l.name]]</strong> <span ng-if="l.default">(alapértelmezett)</span></td>
            <td>
              <span ng-if="l.active">Aktív <button ng-if="!l.default" type="button" ng-click="switchStatusLang(l.code, 0)" class="btn btn-danger">inaktivál</button> </span>
              <span ng-if="!l.active">Inaktív <button type="button" ng-click="switchStatusLang(l.code, 1)" class="btn btn-success">aktivál</button></span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="alert alert-warning" ng-show="languages.length==0">
      Nyelvek betöltése folyamatban...
    </div>
    <div class="language-chooser" ng-show="!loaded_langs">
      <h2>Melyik nyelv szövegeit szerkesztené?</h2>
      <button type="button" onclick="$('#newkey').slideToggle();" class="btn btn-success pull-right">Új nyelvi kulcs hozzáadása</button>
      <ul>
        <li ng-click="changeLang(lang.code)" ng-class="(lang.code == lang_edit)?'selected':''" ng-repeat="lang in languages">[[lang.name]]</li>
        <li class="new" onclick="$('#newlang').slideToggle();" data-toggle="tooltip" title="Új nyelv hozzáadása"><i class="fa fa-plus"></i></li>
      </ul>
    </div>
    <div class="language-container">
      <div class="" ng-show="loaded_langs">
        <div class="alert alert-warning">
          <i class="fa fa-spin fa-spinner"></i> Nyelvek betöltése folyamatban
        </div>
      </div>
      <div class="box" id="newlang" style="display: none;">
        <h3>Új nyelv hozzáadása</h3>
        <strong style="color:red;">Figyelem: egy új nyelv létrehozásnál nincs mód a kimenő, automatikus értesítő email üzenetek módosítására. Új nyelv hozzáadása esetén keresse a rendszer fejlesztőjét ezen üzenetek fordításával kapcsolatosan.</strong>
        <br><br>
        <div class="alert alert-danger" ng-show="newlang_error">
          [[newlang_error]]
        </div>
        <div class="row">
          <div class="col-md-3">
            <label for="code">Nyelvi azonosító*</label>
            <input type="text" maxlength="3" id="code" class="form-control" ng-model="newlang.code" placeholder="Pl.: en, ru, de, fr,...">
          </div>
          <div class="col-md-8">
            <label for="nametext">Nyelv elnevezés - lehetőleg az adott nyelven írva</label>
            <input type="text" id="nametext" class="form-control" ng-model="newlang.nametext" placeholder="Pl.: English, Pусский, Deutsch, Français,...">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <div class="pull-right" class="inprogress-text" ng-show="newlang_uploading">
              Létrehozás folyamatban...
            </div>
            <div class="clearfix"></div>
            <button type="button" ng-hide="newlang_uploading" ng-click="createNewLang()" class="btn btn-danger pull-right">Nyelv hozzáadása</button>
          </div>
        </div>
      </div>
      <div class="box" id="newkey" style="display: none;">
        <h3>Új alapértelmezett nyelvi szöveg regisztrálása</h3>
        <strong>Az alapértelmezett nyelven hozza létre, majd a lenti fordító segítségével szerkesztheti a nyelvi megfelelőjét.</strong>
        <br><br>
        <div class="alert alert-danger" ng-show="new_error">
          [[new_error]]
        </div>
        <div class="alert alert-success" ng-show="new_success">
          Sikeresen hozzáadott egy új nyelvi kulcsot.
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="srcstr">Nyelvi szöveg azonosító kulcs</label>
            <input type="text" id="srcstr" class="form-control" ng-model="new.srcstr">
          </div>
          <div class="col-md-8">
            <label for="textvalue">Megjelenő MAGYAR szöveg</label>
            <textarea id="textvalue" class="form-control" ng-model="new.textvalue"></textarea>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <div class="pull-right" class="inprogress-text" ng-show="new_uploading">
              Feltöltés folyamatban...
            </div>
            <div class="clearfix"></div>
            <button type="button" ng-hide="new_uploading" ng-click="uploadNewText()" class="btn btn-danger pull-right">Nyelvi szöveg feltöltése</button>
          </div>
        </div>
      </div>
      <div class="alert alert-info" ng-hide="loaded">
        <i class="fa fa-spin fa-spinner"></i> Betöltés folyamatban...
      </div>
      <div class="translator" ng-show="loaded">
          <div class="text-filter-input">
              <input type="text" class="form-control" placeholder="Szöveg, nyelvi fordítás részlet keresés..." ng-model="filter_text" ng-keyup="filterList()" name="" value="">
          </div>

          <table class="table table-striped table-bordered">
            <thead class="thead-inverse">
              <tr>
                <th class="id center">ID</th>
                <th class="text">Szöveg</th>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="t in texts" ng-show="((!filter_text) || ((t.textvalue.indexOf(filter_text) !== -1 && t.textvalue) || (t.origin_textvalue && t.origin_textvalue.indexOf(filter_text) !== -1)))">
                <td>[[t.ID]]</td>
                <td>
                  <textarea ng-model="t.textvalue" class="form-control"></textarea>
                  <div class="row" style="align-items:center;">
                    <div class="col-md-10">
                      <div class="origin_lang_translate" ng-show="t.origin_textvalue">
                        Alapé. nyelvi fordítás: <span class="text">[[t.origin_textvalue]]</span> <span class="id">(#[[t.parentID]])</span>
                      </div>
                      <div class="srcstr">
                        Nyelvi azonosító kulcs: <strong>[[t.srcstr]]</strong>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="inprogress-text pull-right" ng-show="(index_progress == t.ID)">Mentés folyamatban...</div>
                      <button type="button" ng-hide="(index_progress == t.ID)" ng-class="(index_saved.indexOf(t.ID) !== -1)?'btn-success':'btn-primary'" class="btn input-sm pull-right" ng-click="saveText(t.ID, t.textvalue, t.parentID)">Mentés <i class="fa fa-save"></i></button>

                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
      </div>
    </div>
  </div>
</div>
