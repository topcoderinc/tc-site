<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" exclude-result-prefixes="feedburner"
                xmlns:feedburner="http://rssnamespace.org/feedburner/ext/1.0">
    <xsl:output method="html" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
                doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"/>
    <xsl:variable name="godecoding">go_decoding();</xsl:variable>
    <xsl:variable name="title" select="/rss/channel/title"/>
    <xsl:variable name="feedUrl" select="/rss/channel/atom10:link[@rel='self']/@href"
                  xmlns:atom10="http://www.w3.org/2005/Atom"/>
    <xsl:template match="/">
        <xsl:element name="html">
            <head>
                <title>
                    <xsl:value-of select="$title"/> - powered by FeedBurner
                </title>
                <link href="//feedburner.google.com/fb/lib/stylesheets/undohtml.css" rel="stylesheet" type="text/css"
                      media="all"/>
                <link href="//feedburner.google.com/fb/feed-styles/bf30.css" rel="stylesheet" type="text/css"
                      media="all"/>
                <link rel="alternate" type="application/rss+xml" title="{$title}" href="{$feedUrl}"/>
                <xsl:element name="script">
                    <xsl:attribute name="type">text/javascript</xsl:attribute>
                    <xsl:attribute name="src">//feedburner.google.com/fb/feed-styles/bf30.js</xsl:attribute>
                </xsl:element>
            </head>
            <xsl:apply-templates select="rss/channel"/>
        </xsl:element>
    </xsl:template>
    <xsl:template match="channel">
        <body id="browserfriendly" onload="jsFeedUrl='{$feedUrl}';loadSubscribeAreaUltra('standard');go_decoding()">
            <div id="cometestme" style="display:none;">
                <xsl:text disable-output-escaping="yes">&amp;amp;</xsl:text>
            </div>
            <div id="bodycontainer">
                <div id="bannerblock">
                    <xsl:apply-templates select="image"/>
                    <h1>
                        <xsl:choose>
                            <xsl:when test="link">
                                <a href="{normalize-space(link)}" title="Link to original website">
                                    <xsl:value-of select="$title"/>
                                </a>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:value-of select="$title"/>
                            </xsl:otherwise>
                        </xsl:choose>
                    </h1>
                    <h2>syndicated content powered by FeedBurner</h2>
                    <p style="clear:both"/>
                </div>
                <div id="bodyblock">
                    <div id="subscribenow" class="subscribeblock action">
                        <div id="subscribe-userchoice" style="display:none">
                            <p id="subscribeLink">
                                <a href="#">...</a>
                            </p>
                            <p id="resetLink">Reset this favorite;
                                <a href="#" onclick="return clearUserchoice('standard')">show all Subscribe options</a>
                            </p>
                        </div>
                        <div id="subscribe-options">
                            <h3>Subscribe Now!</h3>
                            <h4>...with web-based news readers. Click your choice below:</h4>
                            <div id="webbased">
                                <xsl:choose>
                                    <xsl:when test="feedburner:feedFlare">
                                        <xsl:apply-templates select="feedburner:feedFlare"/>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <a href="http://add.my.yahoo.com/rss?url={$feedUrl}"
                                           onclick="this.href = subscribeNow(this.href,'My Yahoo!');return true">
                                            <img src="http://us.i1.yimg.com/us.yimg.com/i/us/my/addtomyyahoo4.gif"
                                                 width="91" height="17" alt="addtomyyahoo4"/>
                                        </a>
                                        <br/>
                                        <a class="img" href="http://www.bloglines.com/sub/{$feedUrl}"
                                           onclick="this.href=subscribeNow(this.href,'Bloglines');return true">
                                            <img src="http://www.bloglines.com/images/sub_modern5.gif"
                                                 alt="Subscribe with Bloglines"/>
                                        </a>
                                        <a href="http://www.netvibes.com/subscribe.php?url={$feedUrl}"
                                           onclick="this.href=subscribeNow(this.href,'Netvibes');return true">
                                            <img src="http://www.netvibes.com/img/add2netvibes.gif"
                                                 alt="Add to netvibes"/>
                                        </a>
                                        <br/>
                                        <a href="http://www.pageflakes.com/subscribe.aspx?url={$feedUrl}"
                                           onclick="this.href=subscribeNow(this.href,'Pageflakes');return true">
                                            <img src="http://www.pageflakes.com/subscribe2.gif" border="0"/>
                                        </a>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </div>
                            <xsl:if test="true()">
                                <h4>...with other readers:</h4>
                                <form action="http://feedburner.google.com" method="get">
                                    <select onchange="location.href=subscribeNowUltra('feed:{$feedUrl}',this.options[this.selectedIndex].value)">
                                        <option value="--" disabled="disabled" selected="selected"
                                                style="padding-left:0">(Choose Your Reader)
                                        </option>
                                        <option value="FeedDemon">FeedDemon</option>
                                        <option value="NetNewsWire">NetNewsWire</option>
                                        <option value="NewsFire">NewsFire</option>
                                        <option value="NewsGator Outlook Edition">NewsGator Outlook Edition</option>
                                        <option value="RSSOwl">RSSOwl</option>
                                        <option value="shrook">Shrook</option>
                                        <option value="USM">Universal Subscription Mechanism (USM)</option>
                                    </select>
                                </form>
                            </xsl:if>
                            <xsl:if test="feedburner:emailServiceId">
                                <xsl:variable name="feedhost" select="/rss/channel/feedburner:feedburnerHostname"/>
                                <xsl:variable name="ffid" select="/rss/channel/feedburner:emailServiceId"/>
                                <p id="emailthis">
                                    <a onclick="window.open('{$feedhost}/fb/a/mailverify?uri={$ffid}', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true"
                                       target="popupwindow" href="{$feedhost}/fb/a/mailverify?uri={$ffid}">Get
                                        <xsl:value-of select="$title"/> delivered by email
                                    </a>
                                </p>
                            </xsl:if>
                            <xsl:choose>
                                <xsl:when test="feedburner:xmlView">
                                    <xsl:variable name="originalHref" select="/rss/channel/feedburner:xmlView/@href"/>
                                    <p>
                                        <a href="{$originalHref}">
                                            <img src="//feedburner.google.com/fb/lib/images/icons/feed-icon-12x12-orange.gif"
                                                 alt="original feed"/>
                                        </a>
                                        <xsl:text> </xsl:text>
                                        <a href="{$originalHref}">View Feed XML</a>
                                    </p>
                                </xsl:when>
                                <xsl:otherwise>
                                    <!-- purely for spacing -->
                                    <p>
                                        <xsl:text> </xsl:text>
                                    </p>
                                </xsl:otherwise>
                            </xsl:choose>

                        </div>
                        <input id="savechoice" type="hidden" value="standard"/>
                    </div>
                    <p class="about">FeedBurner makes it easy to receive content updates in My Yahoo!, Newsgator,
                        Bloglines, and other news readers.
                    </p>
                    <p class="about">
                        <a href="http://www.google.com/support/feedburner/bin/answer.py?answer=79408">Learn more about
                            syndication and FeedBurner...
                        </a>
                    </p>
                    <xsl:apply-templates select="feedburner:browserFriendly"/>
                    <h3 id="currentFeedContent">Current Feed Content</h3>
                    <ul>
                        <xsl:apply-templates select="item"/>
                    </ul>
                </div>
                <div id="footer">
                    <a href="http://feedburner.google.com">
                        <img src="http://feedburner.google.com/fb/feed-styles/images/footer_logo.gif"/>
                    </a>
                    <p>FeedBurner delivers the world's subscriptions wherever they need to go. Publish a feed for text
                        or podcasting?
                        <a href="http://feedburner.google.com" target="_blank"><br/>You should try FeedBurner today
                        </a>
                        .
                    </p>
                </div>
            </div>
        </body>
    </xsl:template>
    <xsl:template match="feedburner:feedFlare">
        <xsl:variable name="alttext" select="."/>
        <a href="{@href}" onclick="this.href = subscribeNowUltra(this.href,'{$alttext}');return true">
            <img src="{@src}" alt="{$alttext}"/>
        </a>
    </xsl:template>
    <xsl:template match="item" xmlns:dc="http://purl.org/dc/elements/1.1/">
        <li class="regularitem">
            <h4 class="itemtitle">
                <xsl:choose>
                    <xsl:when test="guid[@isPermaLink='true' or not(@isPermaLink)]">
                        <a href="{normalize-space(guid)}">
                            <xsl:value-of select="title"/>
                        </a>
                    </xsl:when>
                    <xsl:when test="link">
                        <a href="{normalize-space(link)}">
                            <xsl:value-of select="title"/>
                        </a>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select="title"/>
                    </xsl:otherwise>
                </xsl:choose>
            </h4>
            <h5 class="itemposttime">
                <xsl:if test="count(child::pubDate)=1">
                    <span>Posted:</span>
                    <xsl:value-of select="pubDate"/>
                </xsl:if>
                <xsl:if test="count(child::dc:date)=1">
                    <span>Posted:</span>
                    <xsl:value-of select="dc:date"/>
                </xsl:if>
            </h5>
            <div class="itemcontent" name="decodeable">
                <xsl:call-template name="outputContent"/>
            </div>
            <xsl:if test="count(child::enclosure)=1">
                <p class="mediaenclosure">MEDIA ENCLOSURE:
                    <a href="{enclosure/@url}">
                        <xsl:value-of select="child::enclosure/@url"/>
                    </a>
                </p>
            </xsl:if>
        </li>
    </xsl:template>
    <xsl:template match="image">
        <a href="{normalize-space(link)}" title="Link to original website">
            <img src="{url}" id="feedimage" alt="{title}"/>
        </a>
        <xsl:text/>
    </xsl:template>
    <xsl:template match="feedburner:browserFriendly">
        <p class="about">
            <span style="color:#000">A message from this feed's publisher:</span>
            <xsl:apply-templates/>
        </p>
    </xsl:template>
    <xsl:template name="replaceAdSpace">
        <xsl:param name="body"/>
        <xsl:choose>
            <xsl:when test="contains($body, '&lt;p&gt;&lt;a href=&quot;http://feedads.g.doubleclick.net/~a')">
                <xsl:value-of select="substring-before($body, '&lt;a href=&quot;http://feedads.g.doubleclick.net/~a')"/>
                <xsl:text disable-output-escaping="yes">&lt;iframe src="http://feedads.g.doubleclick.net/~ah/</xsl:text>
                <xsl:value-of
                        select="substring-before(substring-after(substring-after($body, '&lt;p&gt;&lt;a href=&quot;http://feedads.g.doubleclick.net/~a'), '/'), '/')"/>
                <xsl:text disable-output-escaping="yes">/h?w=300&amp;h=250&amp;src=bf" width="100%" height="250" frameborder="0" scrolling="no" style="margin-top:1em"&gt;&lt;/iframe&gt;</xsl:text>
                <xsl:value-of
                        select="substring-after(substring-after(substring-after($body, '&lt;p&gt;&lt;a href=&quot;http://feedads.g.doubleclick.net/~a'), '/1/da'), '&lt;/a&gt;')"/>
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="$body"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    <xsl:template name="outputContent">
        <xsl:choose>
            <xsl:when xmlns:xhtml="http://www.w3.org/1999/xhtml" test="xhtml:body">
                <xsl:copy-of select="xhtml:body/*"/>
            </xsl:when>
            <xsl:when xmlns:xhtml="http://www.w3.org/1999/xhtml" test="xhtml:div">
                <xsl:copy-of select="xhtml:div"/>
            </xsl:when>
            <xsl:when xmlns:content="http://purl.org/rss/1.0/modules/content/" test="content:encoded">
                <xsl:value-of select="content:encoded" disable-output-escaping="yes"/>
            </xsl:when>
            <xsl:when test="description">
                <xsl:variable name="itemBody">
                    <xsl:call-template name="replaceAdSpace">
                        <xsl:with-param name="body" select="description"/>
                    </xsl:call-template>
                </xsl:variable>
                <xsl:value-of select="$itemBody" disable-output-escaping="yes"/>
            </xsl:when>
        </xsl:choose>
    </xsl:template>
</xsl:stylesheet>
