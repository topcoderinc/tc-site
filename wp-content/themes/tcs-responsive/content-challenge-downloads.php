<h3>Downloads:</h3>
<div class="inner">
  <?php
  echo '<ul>';
  if (!empty($documents)) {
    foreach ($documents as $document) {
      echo '<li><a href="' . $document->url . '">' . $document->documentName . '</a></li>';
    }
  }
  else {
    echo '<li><strong>None</li></strong>';
  }
  echo '</ul>';
  ?>
</div>