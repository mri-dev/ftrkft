<h1>Nyelvi fájlok szerkesztése</h1>
<div class="translator-page">
  <div ng-app="Translator" ng-controller="Translate" ng-init="init()">
    <div class="language-chooser">
      <strong>Melyik nyelv szövegeit szerkesztené?</strong>
      <ul>
        <li ng-click="changeLang(lang.code)" ng-class="(lang.code == lang_edit)?'selected':''" ng-repeat="lang in languages"><img src="{$smarty.const.IMG}flag-[[lang.code]].svg" alt="[[lang.name]]" data-toggle="tooltip" title="[[lang.name]]"></li>
      </ul>
    </div>
    <div class="alert alert-info" ng-hide="loaded">
      <i class="fa fa-spin fa-spinner"></i> Betöltés folyamatban...
    </div>
    <div class="translator" ng-show="loaded">
        <div class="text-filter-input">
            <input type="text" class="form-control" placeholder="Szöveg, nyelvi fordítás részlet keresés..." ng-model="filter_text" ng-keyup="filterList()" name="" value="">
        </div>

        <table class="table table-striped table-bordered">
          <thead>
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
