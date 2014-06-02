<article ng-if="isDesign && submissions.length > 0">
    <div ng-if="firstPlaceSubmission" ng-repeat="submission in submissions" class="winnerRow {{$index > 1 ? 'hideOnMobi' : ''}}">
        <div class="place {{['first', 'second', 'third', 'other'][$index]}}">{{$index + 1}}<span>{{(placeSuffix = ['st', 'nd', 'rd'][$index]) ? placeSuffix : 'th'}}</span></div>
        <!-- #/end place-->
        <div class="image">
            <img src="{{submission.previewDownloadLink}}" alt="winner"/>
        </div>
        
        <!-- #/end image-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/{{submission.handle}}" class="coderTextOrange">{{submission.handle}}</a>
            <div class="">
                <h3>${{challenge.prize[0]}}</h3>
                <span class="title">PRIZE</span>
                <span class="date">Registration Date</span>
                <span class="time">{{formatDate(submission.registrationDate)}}</span>
            </div>
            <div class="">
                <h3>{{submission.points}}</h3>
                <span class="title">Studio Cup Points</span>
                <span class="date">Submitted Date</span>
                <span class="time">{{formatDate(submission.submissionDate)}}</span>
            </div>
        </div>
        <!-- #/end details-->
        <div class="actions">
            <a href="{{submission.previewDownloadLink}}" class="view">View</a>
            <a href="{{submission.submissionDownloadLink}}" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>

    <!--#/end winnerrow-->
    <div class="winnerRow hideOnMobi hide">
        <div class="place other client">CLIENT<span>SELECTION</span></div>
        <!-- #/end place-->
        <div class="image">
            <img src="" alt="winner" alt="winner"/>
        </div>
        <!-- #/end image-->
        <div class="details">
            <a href="javascript:" class="coderTextOrange">Handlegoeshere</a>
            <div class="">
                <h3>$200</h3>
                <span class="title">PRIZE</span>
                <span class="date">Registration Date</span>
                <span class="time">01.07.2014 09:37 AM EST</span>
            </div>
            <div class="">
                <h3>100</h3>
                <span class="title">Studio Cup Points</span>
                <span class="date">Submitted Date</span>
                <span class="time">01.07.2014 09:37 AM EST</span>
            </div>
        </div>
        <!-- #/end details-->
        <div class="actions">
            <a href="" class="view" class="view">View</a>
            <a href="" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <!--#/end winnerrow-->
    <div class="showMore hideOnMobi hide">
        <a class="fiveMore" href="javascript:">5 more winners</a>
    </div>
    <!--#/end showMore-->
    <div class="competitionDetails">
        <div class="registrant">
            <h2>Registrants</h2>
            <div class="values">
                <span class="count">{{challenge.numberOfRegistrants}}</span>
            </div>
        </div>
        <!--#/end registrant-->
        <div class="round {{numCheckpointSubmissions == -1 ? 'hide' : ''}}">
            <h2>Round 1 (Checkpoint)</h2>
            <!--<div class="values">
                <span class="count"><span class="sup">&nbsp;</span></span>
                <span class="type">Submitter</span>
                <span class="type">&nbsp;</span>
            </div>-->
            <div class="values">
                <span class="count"></span>
                <span class="type">Submissions</span>
            </div>
            <!--<div class="values">
                <span class="count">N/A<span class="sup">(N/A%)</span></span>
                <span class="type">Passed Review</span>
            </div>-->
        </div>
        <!--#/end round-->
        <div class="round round2">
            <h2>Round 2 (Final)</h2>
            <!--<div class="values">
                <span class="count">N/A<span class="sup">&nbsp;</span></span>
                <span class="type">Submitter</span>
                <span class="type">&nbsp;</span>
            </div>-->
            <div class="values">
                <span class="count">{{submissions.length}}</span>
                <span class="type">Submissions</span>
            </div>
            <!--<div class="values">
                <span class="count">N/A<span class="sup">(N/A%)</span></span>
                <span class="type">Passed Review</span>
            </div>-->
        </div>
        <!--#/end round-->
        <div class="clear"></div>
    </div>
    <!--#/end competitionDetails-->
</article>

<article ng-if="!isDesign && submissions.length > 0">
    <div ng-if="firstPlaceSubmission" class="winnerRow">
        <div class="place first">1<span>st</span></div>
        <!-- #/end place-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/{{firstPlaceSubmission.handle}}" class="coderTextYellow">{{firstPlaceSubmission.handle}}</a>
        </div>
        <!-- #/end details-->
        <div class="price">
            <span class="price">${{challenge.prize[0]}}</span>
            <span>PRIZE</span>
        </div>
        <!-- #/end price-->
        <div class="point">
            <span class="point">{{firstPlaceSubmission.points}}</span>
            <span>DR POINT</span>
        </div>
        <!-- #/end price-->
        <div class="actions">
            <a href="{{firstPlaceSubmission.submissionDownloadLink" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <!--#/end winnerrow-->
    <div ng-if="secondPlaceSubmission && challenge.prize[1]" class="winnerRow">
        <div class="place second">2<span>nd</span></div>
        <!-- #/end place-->
        <div class="details">
            <a href="<?php bloginfo('wpurl'); ?>/member-profile/{{secondPlaceSubmission.handle}}" class="coderTextGray">{{secondPlaceSubmission.handle}}</a>
        </div>
        <!-- #/end details-->
        <div class="price">
            <span class="price">${{challenge.prize[1]}}</span>
            <span>PRIZE</span>
        </div>
        <!-- #/end price-->
        <div class="point">
            <span class="point">{{secondPlaceSubmission.points}}</span>
            <span>DR POINT</span>
        </div>
        <!-- #/end price-->
        <div class="actions">
            <a href="{{secondPlaceSubmission.submissionDownloadLink}}" class="download">Download</a>
        </div>
        <!-- #/end actions-->
        <div class="clear"></div>
    </div>
    <!--#/end winnerrow-->
    <table class="registrantsTable hideOnMobi">
        <thead>
        <tr>
            <th class="leftAlign">
                <a href="javascript:" class="">Handle</a>
            </th>
            <th>
                <a href="javascript:" class="">Registration Date</a>
            </th>
            <th>
                <a href="javascript:" class="">Submission Date</a>
            </th>
            <th>
                <a href="javascript:" class="">Screening Score</a>
            </th>
            <th>
                <a href="javascript:" class="">Initial/ Final Score</a>
            </th>
            <th>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="submission in submissions" class="{{$index % 2 == 1 ? 'alt' : ''}}">
            <td class="leftAlign"><a href="<?php bloginfo('wpurl'); ?>/member-profile/{{submission.handle}}" class="coderTextGray">{{submission.handle}}</a></td>
            <td>{{formatDate(submission.registrationDate)}}</td>
            <td>{{formatDate(submission.submissionDate)}}</td>
            <td><span class="pass">{{submission.screeningScore}}</span></td>
            <td><span class="initialScore">{{submission.initialScore}}</span>/<a href="javascript:" class="finalScore">{{submission.finalScore}}</a> </td>
            <td><a href="{{submission.submissionDownloadLink}}">Download</a></td>
        </tr>
        </tbody>
    </table>
    <div class="registrantsTable onMobi">

        <div ng-repeat="submission in submissions" class="registrantSection">
            <div class="registrantSectionRow registrantHandle"><a href="<?php bloginfo('wpurl'); ?>/member-profile/{{submission.handle}}" class=" coder coderTextYellow">{{submission.handle}}</a></div>
            <div class="registrantSectionRow">
                <div class="registrantLabel">Registration Date:</div>
                <div class="registrantField">{{formatDate(submission.registrationDate)}}</div>
                <div class="clear"></div>
            </div>
            <div class="registrantSectionRow">
                <div class="registrantLabel">Submission Date:</div>
                <div class="registrantField">{{formatDate(submission.submissionDate)}}</div>
                <div class="clear"></div>
            </div>
            <div class="registrantSectionRow">
                <div class="registrantLabel">Screening Score:</div>
                <div class="registrantField"><span class="pass">{{submission.screeningScore}}</span></div>
                <div class="clear"></div>
            </div>
            <div class="registrantSectionRow">
                <div class="registrantLabel">Initial/ Final Score:</div>
                <div class="registrantField"><a href="javascript:">{{submission.screeningScore}}/{{submission.finalScore}}</a></div>
                <div class="clear"></div>
            </div>
            <div class="registrantSectionRow">
                <div class="registrantLabel"><a href="javascript:" class="download">Download</a></div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <div class="competitionDetails">
        <div class="registrant">
            <h2>Registrants</h2>
            <div class="values">
                <span class="count">{{challenge.numberOfRegistrants}}</span>
            </div>
        </div>
        <!--#/end registrant-->
        <div class="round {{!checkpointData ? 'hide' : ''}}">
            <h2>Checkpoint</h2>
            <!--<div class="values">
                <span class="count"><span class="sup">&nbsp;</span></span>
                <span class="type">Submitter</span>
                <span class="type">&nbsp;</span>
            </div>-->
            <div class="values">
                <span class="count">{{numCheckpointSubmissions}}</span>
                <span class="type">Submissions</span>
            </div>
            <!--<div class="values">
                <span class="count">N/A<span class="sup">(N/A%)</span></span>
                <span class="type">Passed Review</span>
            </div>-->
        </div>
        <!--#/end round-->
        <div class="round round2">
            <h2>Final</h2>
            <!--<div class="values">
                <span class="count"><span class="sup">&nbsp;</span></span>
                <span class="type">Submitter</span>
                <span class="type">&nbsp;</span>
            </div>-->
            <div class="values">
                <span class="count" ng-bind="challenge.numberOfSubmissions"></span>
                <span class="type">Submissions</span>
            </div>
            <!--<div class="values">
                <span class="count">N/A<span class="sup">(N/A%)</span></span>
                <span class="type">Passed Review</span>
            </div>-->
        </div>
        <!--#/end round-->
        <div class="average">
            <h2>AVERAGE SCORE</h2>
            <div class="values">
                <span class="count">{{round(initialScoreSum * 1.0 / submissions.length)}}<span class="sup">&nbsp;</span></span>
                <span class="type">Average</span>
                <span class="type">Initial Score</span>
            </div>
            <div class="values">
                <span class="count">{{round(finalScoreSum * 1.0 / submissions.length)}}<span class="sup">&nbsp;</span></span>
                <span class="type">Average</span>
                <span class="type">Final Score</span>
            </div>
        </div>
        <!--#/end round-->
        <div class="clear"></div>
    </div>

</article>
<div ng-if="!submissions || submissions.length == 0">
  There are no submissions for this contest.
</div>

