<div class="searchFilter hide">
  <div class="filterOpts">
    <section class="types">
      <h5>Contest types:</h5>
      <div class="data">
        <?php if($contest_type=="design") : ?>
          <ul class="list">
            <li><input type="radio" id="fAll" name="radioFilterChallenge" value="all"> <label for="fAll"><strong>All</strong></label></li>
            <li><input type="radio" id="fLogo" name="radioFilterChallenge" value="Logo Design"> <label for="fAll">Logo Design</label></li>
            <li><input type="radio" id="fAFE" name="radioFilterChallenge" value="Application Front-End Design"> <label for="fAll">Application Front End</label></li>
            <li><input type="radio" id="fP" name="radioFilterChallenge" value="Print/Presentation"> <label for="fAll">Print/Presentation</label></li>
            <li><input type="radio" id="fIco" name="radioFilterChallenge" value="Banners/Icons"> <label for="fAll">Banner/Icon</label></li>
            <li><input type="radio" id="fW" name="radioFilterChallenge" value="Web Design"> <label for="fAll">Web Design</label></li>
            <li><input type="radio" id="df2f" name="radioFilterChallenge" value="Design First2Finish"> <label for="fAll">First2Finish</label></li>
            <li><input type="radio" id="fWI" name="radioFilterChallenge" value="Widget or Mobile Screen Design"> <label for="fAll">Widget/Mobile Screen</label></li>
            <li><input type="radio" id="fIG" name="radioFilterChallenge" value="Idea Generation"> <label for="fAll">Idea Generation</label></li>
            <li><input type="radio" id="fWF" name="radioFilterChallenge" value="Wireframes"> <label for="fAll">Wireframe</label></li>
          </ul>
        <?php else : ?>
          <ul class="list">
            <li><input type="radio" id="fAll" name="radioFilterChallenge" class="all" value="all" /> <label for="fAll"><strong>All</strong></label></li>
            <li><input type="radio" id="fDev" name="radioFilterChallenge" value="Development" /> <label for="fDev">Component Development</label></li>
            <li><input type="radio" id="fArc" name="radioFilterChallenge" value="Architecture" /> <label for="fArc">Architecture</label></li>
            <li><input type="radio" id="f2f" name="radioFilterChallenge" value="First2Finish" /> <label for="f2f">First2Finish</label></li>
            <li><input type="radio" id="fAC" name="radioFilterChallenge" value="Assembly Competition" /> <label for="fAC">Assembly Competition</label></li>
            <li><input type="radio" id="fRep" name="radioFilterChallenge" value="Reporting" /> <label for="fRep">Reporting</label></li>
            <li><input type="radio" id="fBH" name="radioFilterChallenge" value="Bug Hunt" /> <label for="fBH">Bug Hunt</label></li>
            <li><input type="radio" id="fRia" name="radioFilterChallenge" value="RIA Build Competition" /> <label for="fRia">RIA Build Competition</label></li>
            <li><input type="radio" id="fCode" name="radioFilterChallenge" value="Code" /> <label for="fCode">Code</label></li>
            <li><input type="radio" id="fSpec" name="radioFilterChallenge" value="Specification" /> <label for="fSpec">Specification</label></li>
            <li><input type="radio" id="fCoP" name="radioFilterChallenge" value="Copilot Posting" /> <label for="fCoP">Copilot Posting</label></li>
            <li><input type="radio" id="fTS" name="radioFilterChallenge" value="Test Scenarios" /> <label for="fTS">Test Scenarios</label></li>
            <li><input type="radio" id="fCon" name="radioFilterChallenge" value="Conceptualization" /> <label for="fCon">Conceptualization</label></li>
            <li><input type="radio" id="fTeS" name="radioFilterChallenge" value="Test Suites" /> <label for="fTeS">Test Suites</label></li>
            <li><input type="radio" id="fCC" name="radioFilterChallenge" value="Content Creation" /> <label for="fCC">Content Creation</label></li>
            <li><input type="radio" id="fTC" name="radioFilterChallenge" value="Testing Competition" /> <label for="fTC">Testing Competition</label></li>
            <li><input type="radio" id="fDe" name="radioFilterChallenge" value="Design" /> <label for="fDe">Component Design</label></li>
            <li><input type="radio" id="fUI" name="radioFilterChallenge" value="UI Prototype Competition" /> <label for="fUI">UI Prototype Competition</label></li>
          </ul>
        <?php endif; ?>
      </div>
    </section>
    <section class="otherOpts" >
      <ul>
        <li class="date row"><div class="lbl">
            <input type="checkbox" id="fSDate" />
            <label for="fSDate"><strong>Submission End From:</strong></label>
          </div>
          <div class="val">
            <span class="datePickerWrap"><input id="startDate" type="text" class="datepicker from" /></span>
          </div>
        </li>
        <li class="date row">
          <div class="lbl">
            <input type="checkbox" id="fEDate" />
            <label for="fEDate"><strong>Submission End To:</strong></label>
          </div>

          <div class="val">
            <span class="datePickerWrap"><input id="endDate"  type="text" class="datepicker to" /></span>
          </div>
        </li>
      </ul>
    </section>
    <?php if ($contest_type == "develop") : ?>
    <section class="tags">
      <h5>Technology Tags:</h5>
      <div class="data">
        <select data-placeholder=" " class="chosen-select" multiple tabindex="4">
          <option value=" "> </option>
        </select>
      </div>
    </section>
    <?php endif; ?>
    <div class="clear"></div>
  </div>
  <!-- /.filterOpts -->
  <div class="actions">
    <a href="javascript:;" class="btn btnSecondary btnClose">Close</a>
    <a href="javascript:;" class="btn btnApply">Apply</a>
  </div>
</div>
<!-- /.searchFilter -->