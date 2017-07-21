<div class="message-list">
  <div ng-show="!data_loaded" class="alert alert-warning" style="margin-top: 10px;">
    <i class="fa fa-spin fa-spinner"></i> {lang text="Üzenet betöltése folyamatban..."}
  </div>
  <div class="no-messages" ng-show="!messages">
    <i class="fa fa-comments-o"></i>
    <h3>{lang text="Nincsennek üzenetek"}.</h3>
    {lang text="Nincsennek üzenetek_SUBTEXT"}
  </div>
  <div class="message-session" ng-repeat="(session, msg) in messages" ng-class="(msg.unreaded) ? 'has-unreaded' : ''">
    <div class="unread" ng-show="(msg.unreaded) ? true : false">
      [[msg.unreaded]]
    </div>
    <div class="group-head">
      <div class="from">
        [[msg.from.name]]
      </div>
      <div class="session-start" >
        <span data-toggle="tooltip" title="{lang text='A beszélgetés kezdete'}">[[msg.created_at]]</span>
        <span data-toggle="tooltip" data-placement="left" title="{lang text='Beszélgetés indító admin'}" ng-show="msg.from_admin_name"> &bull; <span class="from_admin">[[msg.from_admin_name]]</span></span>
      </div>
      <div class="subject" ng-class="(msg.subject == '') ? 'no-sub' : ''">
        <span class="locked" data-toggle="tooltip" title="{lang text='Ez a beszélgetés zárolva lett'}." ng-show="msg.closed"><i class="fa fa-lock"></i></span> <a href="{$root}messanger/session/[[session]]">[[msg.subject]]</a> <span ng-show="(msg.unreaded) ? true : false" class="unread-label"><a href="{$root}messanger/session/[[session]]">[[msg.unreaded]] {lang text="olvasatlan üzenet"} <i class="fa fa-arrow-circle-right"></i></a></span>
      </div>
      <div class="notice">
        <div class="writen" ng-show="(msg.notice_by_user)?true:false">
          &mdash; [[msg.notice_by_user]] <span ng-click="(msgtgl[session]) ? msgtgl[session]=false : msgtgl[session]=true" class="edit-notice" data-toggle="tooltip" title="{lang text='Megjegyzés módosítása'}"><i class="fa fa-pencil"></i></span>
        </div>
        <div class="newnotice" ng-show="(!msg.notice_by_user)?true:false">
          <a href="javascript:void(0);" ng-click="(msgtgl[session]) ? msgtgl[session]=false : msgtgl[session]=true"><i class="fa fa-sticky-note-o"></i> {lang text="Saját megjegyzés írása ehhez az üzenetváltáshoz..."}</a>
        </div>
        <div ng-show="msgtgl[session]" class="notice-input">
          <div class="input-group">
            <input type="text" ng-model="newnotice" ng-value="msg.notice_by_user" ng-change="msg.notice_by_user = newnotice" class="form-control" placeholder="{lang text='Megjegyzés...'}">
            <span class="input-group-btn"><button ng-click="saveMsgSessionData(session, 'notice_by_user', newnotice)" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
          </div>
          <div class="newnotice-error" ng-show="(newnoticemsg[session])?true:false">
            [[newnoticemsg[session] ]]
          </div>
        </div>
      </div>
    </div>
    <div class="allas-spot" ng-show="msg.allas">
      <div class="ico">
        <i class="fa fa-file-text-o"></i>
      </div>
      <div class="data">
        <a target="_blank" href="[[ msg.allas.url ]]">[[ msg.allas.text ]]</a>
        <div class="categories">
          <span class="city"><i class="fa fa-map-marker"></i> [[ msg.allas.city ]]</span> <span class="type">[[ msg.allas.type ]]</span> <span class="cat">[[ msg.allas.cat ]]</span>
        </div>
      </div>
    </div>
  </div>
</div>
