<h3>Downloads:</h3>
<div class="inner">
  <?php
  echo '<ul class="downloadDocumentList">';
  if (!empty($documents)) {
    foreach ($documents as $document) {
      echo '<li><a href="' . $document->url . '">' . $document->documentName . '</a></li>';
    }
  }
  else {
    echo '<li><strong>Register</strong> to download files.</li>';
  }
  echo '</ul>';
  ?>
</div>
