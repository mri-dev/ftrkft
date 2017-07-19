<div ng-show="!data_loaded" class="alert alert-warning" style="margin-top: 10px;">
  <i class="fa fa-spin fa-spinner"></i> {lang text="Üzenet betöltése folyamatban..."}
</div>
<div class="messanger-reader" ng-show="data_loaded">
  <div class="header">
    <div class="archiving" ng-show="(messages['{$msgsession}'].archived_by_admin == 0)?true:false" ng-click="archiveMessageSession('{$msgsession}', 1)">
      <i class="fa fa-archive"></i> {lang text="Archiválás"}
    </div>
    <h2>[[messages['{$msgsession}'].subject]]</h2>
    <div class="from">
      <span class="name">[[messages['{$msgsession}'].from.name]]</span>
      <span class="time" data-toggle="tooltip" title="{lang text='A beszélgetés kezdete'}" data-placement="bottom">[[messages['{$msgsession}'].created_at]]</span>
    </div>
    <div class="notice">
      <div class="writen" ng-show="(messages['{$msgsession}'].notice_by_admin)?true:false">
        &mdash; [[messages['{$msgsession}'].notice_by_admin]] <span ng-click="(msgtgl['{$msgsession}']) ? msgtgl['{$msgsession}']=false : msgtgl['{$msgsession}']=true" class="edit-notice" data-toggle="tooltip" title="{lang text='Megjegyzés módosítása'}"><i class="fa fa-pencil"></i></span>
      </div>
      <div class="newnotice" ng-show="(!messages['{$msgsession}'].notice_by_admin)?true:false">
        <a href="javascript:void(0);" ng-click="(msgtgl['{$msgsession}']) ? msgtgl['{$msgsession}']=false : msgtgl['{$msgsession}']=true"><i class="fa fa-sticky-note-o"></i> {lang text="Saját megjegyzés írása ehhez az üzenetváltáshoz..."}</a>
      </div>
      <div ng-show="msgtgl['{$msgsession}']">
        <div class="input-group">
          <input type="text" ng-model="newnotice" ng-value="messages['{$msgsession}'].notice_by_admin" ng-change="messages['{$msgsession}'].notice_by_admin = newnotice" class="form-control" placeholder="{lang text='Megjegyzés...'}">
          <span class="input-group-btn"><button ng-click="saveMsgSessionData('{$msgsession}', 'notice_by_admin', newnotice)" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
        </div>
        <div class="newnotice-error" ng-show="(newnoticemsg['{$msgsession}'])?true:false">
          [[newnoticemsg['{$msgsession}'] ]]
        </div>
      </div>
    </div>
    <div class="user-spot" ng-show="messages['{$msgsession}'].from.user_data">
      <div class="ico">
        <i class="fa fa-user"></i>
      </div>
      <div class="data">
        <strong>Felhasználó adatai:</strong>
        <div>
          <span class="name"><i class="fa fa-user"></i> [[messages['{$msgsession}'].from.user_data.name]]</span>
          <span class="phone">
          <i class="fa fa-phone"></i> [[messages['{$msgsession}'].from.user_data.phone]]</span>
          <span class="email"><i class="fa fa-globe"></i> [[messages['{$msgsession}'].from.user_data.email]]</span>
        </div>
        <div class="email">

        </div>
      </div>
    </div>
    <div class="allas-spot" ng-show="messages['{$msgsession}'].allas">
      <div class="ico">
        <i class="fa fa-file-text-o"></i>
      </div>
      <div class="data">
        <a target="_blank" href="[[ messages['{$msgsession}'].allas.url ]]">[[ messages['{$msgsession}'].allas.text ]]</a>
        <div class="categories">
          <span class="city"><i class="fa fa-map-marker"></i> [[ messages['{$msgsession}'].allas.city ]]</span> <span class="type">[[ messages['{$msgsession}'].allas.type ]]</span> <span class="cat">[[ messages['{$msgsession}'].allas.cat ]]</span>
        </div>
      </div>
    </div>
  </div>
  <div class="conversation-creator" ng-hide="messages['{$msgsession}'].closed">
    <textarea focus-me="newmsg_focused" ng-model="newmsg" maxlength="1000" ng-change="newmsg_left_length = 1000-newmsg.length" class="form-control" placeholder="{lang text='Új üzenet írása...'}"></textarea>
    <div class="conv-footer">
      <div class="text">[[newmsg_left_length]] {lang text="karakter maradt"}</div>
      <button class="btn btn-success" ng-click="sendMessage('{$msgsession}', {$admin->getID()}, messages['{$msgsession}'].user_to_id, 1)"><span ng-show="!newmsg_send_progress">{lang text="Küldés"} <i class="fa fa-arrow-circle-right"></i></span><span ng-show="newmsg_send_progress">{lang text="Küldés folyamatban..."} <i class="fa fa-spin fa-spinner"></i></span></button>
    </div>
    <div class="clearfix"></div>
  </div>
  <div class="archived-msg" ng-show="(messages['{$msgsession}'].archived_by_admin == 1)?true:false">
    <i class="fa fa-archive"></i>
    <h3>{lang text="Archivált üzenetváltás"}</h3>
    <div class="">{lang text="Archivált üzenetváltás text"}</div>
  </div>
  <div class="closed-msg" ng-show="messages['{$msgsession}'].closed">
    <i class="fa fa-lock"></i>
    <h3>{lang text="Lezárt üzenet"}</h3>
    <div class="">{lang text="Lezárt üzenet text"}</div>
    <div class="time">[[messages['{$msgsession}'].closed_at]]</div>
  </div>
  <div class="conversation-creator-errmsg" ng-show="(newmsgerrmsg)?true:false">
    [[newmsgerrmsg]]
  </div>
  <div class="conversations">
    <div class="conversations-header">
      [[messages['{$msgsession}'].msg.length]] {lang text='db'} {lang text="üzenet"}
    </div>
    <div class="conversation" ng-class="(conv.from_id == {$admin->getID()} )?'from-me':'from-sender'" ng-repeat="conv in messages['{$msgsession}'].msg">
      <div class="bubble">
        <div class="text" ng-bind-html="conv.msg|nl2br"></div>
      </div>
      <div class="from">
        <span class="unreaded" ng-show="(conv.unreaded) ? true : false">{lang text="Új üzenet"}</span> <strong>[[conv.from.name]]</strong> <span class="timestamp">[[conv.send_at]]</span>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
