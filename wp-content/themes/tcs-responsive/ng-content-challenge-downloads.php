<h3>Downloads:</h3>
<div class="inner">
  <ul class="downloadDocumentList">
    <li ng-if="challenge.Documents && challenge.Documents.length > 1" ng-repeat="document in challenge.Documents">
      <a href="document.url">{{document.documentName}} Scoop</a>
    </li>
    <li ng-if="challenge.Documents && challenge.Documents.length === 0">
      <strong>None</strong>
    </li>
    <li ng-if="typeof challenge.Documents === 'undefined'">
      <strong>Register to Download Files (if available)</strong>
    </li>
  </ul>
</div>
