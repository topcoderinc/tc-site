<h3>Downloads:</h3>
<div class="inner">
  <ul class="downloadDocumentList">
    <li ng-if="challenge.Documents && challenge.Documents.length > 1" ng-repeat="document in challenge.Documents">
      <a href="document.url">{{document.documentName}}</a>
    </li>
    <li ng-if="!(challenge.Documents && challenge.Documents.length > 1)">
      <strong>None</strong>
    </li>
  </ul>
</div>
