<div class="message-list">
  <div class="message-session" ng-repeat="(session, msg) in messages" ng-class="(msg.unreaded) ? 'has-unreaded' : ''">
    <div class="unread" ng-show="(msg.unreaded) ? true : false">
      [[msg.unreaded]]
    </div>
    <div class="group-head">
      <div class="from">
        [[msg.from.name]]
      </div>
      <div class="session-start">
        [[msg.created_at]]
      </div>
      <div class="subject" ng-class="(msg.subject == '') ? 'no-sub' : ''">
        [[msg.subject]] <span ng-show="(msg.unreaded) ? true : false" class="unread-label">[[msg.unreaded]] {lang text="olvasatlan üzenet"}</span>
      </div>
    </div>
  </div>
</div>
