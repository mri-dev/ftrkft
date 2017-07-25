<div class="row input-line">
  <div class="col-md-3">
    <label><i class="fa fa-facebook-square"></i> {lang text="Facebook"}</label>
  </div>
  <div class="col-md-9">
    <div class="input-wrapper">
      <input type="text" class="form-control" ng-model="form.social_url_facebook">
      <div ng-class="'form-helper ' + ( (form.social_url_facebook) ? 'valid' : 'invalid')"></div>
    </div>
  </div>
</div>
<div class="row input-line">
  <div class="col-md-3">
    <label><i class="fa fa-twitter-square"></i> {lang text="Twitter"}</label>
  </div>
  <div class="col-md-9">
    <div class="input-wrapper">
      <input type="text" class="form-control" ng-model="form.social_url_twitter">
      <div ng-class="'form-helper ' + ( (form.social_url_twitter) ? 'valid' : 'invalid')"></div>
    </div>
  </div>
</div>
<div class="row input-line">
  <div class="col-md-3">
    <label><i class="fa fa-linkedin-square"></i> {lang text="LinkedIn"}</label>
  </div>
  <div class="col-md-9">
    <div class="input-wrapper">
      <input type="text" class="form-control" ng-model="form.social_url_linkedin">
      <div ng-class="'form-helper ' + ( (form.social_url_linkedin) ? 'valid' : 'invalid')"></div>
    </div>
  </div>
</div>
