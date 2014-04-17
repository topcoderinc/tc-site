<?php
/**
 * Challenges Feed Template
 */
header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>';
echo '<?xml-stylesheet type="text/xsl" media="screen" href="' . get_stylesheet_directory_uri() . '/css/rss2full.xsl"?>';

$listType = get_query_var('list');
$contestType = get_query_var('contestType');
$contests = array();

if ($contestType == 'all') {
    $contestDesign = get_contests_rss($listType, 'design');
    $contestDevelop = get_contests_rss($listType, 'develop');
    $contestData = get_contests_rss($listType, 'data');

    if (isset($contestDesign->data) && is_array($contestDesign->data) && !empty($contestDesign->data)) {
        $contests += $contestDesign->data;
    }

    if (isset($contestDevelop->data) && is_array($contestDevelop->data) && !empty($contestDevelop->data)) {
        $contests += $contestDevelop->data;
    }

    if (isset($contestData->data) && is_array($contestData->data) && !empty($contestData->data)) {
      $contests += $contestData->data;
    }

} else {
    $contests = get_contests_rss($listType, $contestType);
    $contests = isset($contests->data) && is_array($contests->data) ? $contests->data : array();
}

?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
    <?php do_action('rss2_ns'); ?>>
    <channel>
        <title><?php bloginfo_rss('name'); ?> - Challenges</title>
        <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml"/>
        <link><?php bloginfo_rss('url') ?></link>
        <description><?php bloginfo_rss('description') ?></description>
        <sy:updatePeriod><?php echo apply_filters('rss_update_period', 'hourly'); ?></sy:updatePeriod>
        <sy:updateFrequency><?php echo apply_filters('rss_update_frequency', '1'); ?></sy:updateFrequency>
        <?php do_action('rss2_head'); ?>
        <?php
        $contests = array();
        if ($listType == 'upcoming') {
                //api request stalls out above 50 items, need better api server
		$upcomingContests = get_upcoming_contests_ajax($userkey, $contestType, 1, 50);
		if (is_array($upcomingContests->data)) {
                    $contests = array_merge($contests, $upcomingContests->data);
                }
        } elseif ($listType == 'active') {
            if ($contestType == 'data') {
                $marathonContests = get_active_contests_ajax($userkey, 'data-marathon', 1, 1000);
                if (is_array($marathonContests->data)) {
                    $contests = array_merge($contests, $marathonContests->data);
                }
                /*$srmContests = get_active_contests_ajax($userkey, 'data-srm', 1, 1000);
                if(is_array($srmContests->data)){
                    $contests = array_merge($contests, $srmContests->data);
                }*/
            } else {
                if ($contestType == 'all') {
                    $developContests = get_active_contests_ajax($userkey, 'develop', 1, 1000);
                    if (is_array($developContests->data)) {
                        $contests = array_merge($contests, $developContests->data);
                    }
                    $designContests = get_active_contests_ajax($userkey, 'design', 1, 1000);
                    if (is_array($designContests->data)) {
                        $contests = array_merge($contests, $designContests->data);
                    }
                    $marathonContests = get_active_contests_ajax($userkey, 'data-marathon', 1, 1000);
                    if (is_array($marathonContests->data)) {
                        $contests = array_merge($contests, $marathonContests->data);
                    }
                    /*$srmContests = get_active_contests_ajax($userkey, 'data-srm', 1, 1000);
                    if(is_array($srmContests->data)){
                        $contests = array_merge($contests, $srmContests->data);
                    }*/
                } else {
                    $contests = get_active_contests_ajax($userkey, $contestType)->data;
                }
            }
        } else {
            if ($contestType == 'data') {
                $marathonContests = get_past_contests_ajax($userkey, 'data-marathon', 1, 1000);
                if (is_array($marathonContests->data)) {
                    $contests = array_merge($contests, $marathonContests->data);
                }
                /*$srmContests = get_past_contests_ajax($userkey, 'data-srm', 1, 1000);
                if(is_array($srmContests->data)){
                    $contests = array_merge($contests, $srmContests->data);
                }*/
            } else {
                if ($contestType == 'all') {
                    $developContests = get_past_contests_ajax($userkey, 'develop', 1, 1000);
                    if (is_array($developContests->data)) {
                        $contests = array_merge($contests, $developContests->data);
                    }
                    $designContests = get_past_contests_ajax($userkey, 'design', 1, 1000);
                    if (is_array($designContests->data)) {
                        $contests = array_merge($contests, $designContests->data);
                    }
                    $marathonContests = get_past_contests_ajax($userkey, 'data-marathon', 1, 1000);
                    if (is_array($marathonContests->data)) {
                        $contests = array_merge($contests, $marathonContests->data);
                    }
                    /*$srmContests = get_past_contests_ajax($userkey, 'data-srm', 1, 1000);
                    if(is_array($srmContests->data)){
                        $contests = array_merge($contests, $srmContests->data);
                    }*/
                } else {
                    $contests = get_past_contests_ajax($userkey, $contestType, 1, 1000)->data;
                }
            }
        }

        if (!is_array($contests)) {
            $contests = array();
        }
        ?>
        <?php foreach ($contests as $contest): ?>
            <?php if ($contest->challengeCommunity == 'design' || $contest->challengeCommunity == 'develop') : ?>
                <?php
                $contestDetail = get_contest_detail($userkey, $contest->challengeId, $contest->challengeCommunity);
                ?>
                <item>
                    <title><?php echo $contestDetail->challengeName; ?></title>
                    <link><?php echo bloginfo(
                                'siteurl'
                            ) . '/challenge-details/' . $contestDetail->challengeId . '?type=' . $contestType ?></link>
                    <description><![CDATA[<?php echo $contestDetail->detailedRequirements ?>]]></description>
                    <content:encoded><![CDATA[<?php echo $contestDetail->detailedRequirements ?>]]></content:encoded>
                    <?php rss_enclosure(); ?>
                    <?php do_action('rss2_item'); ?>
                </item>
            <?php else: ?>
                <item>
                    <title><?php echo $contest->name; ?></title>
                    <?php rss_enclosure(); ?>
                    <?php do_action('rss2_item'); ?>
                </item>
            <?php endif; ?>
        <?php endforeach; ?>
    </channel>
</rss>
