<section id="" class="cm">
    <div class="left box padding">
        <div class="postbox">
            <h3><span>General Support</span></h3>
            <div class="inside">
                <h4>Submit your Support Request</h4>
                <p>Please click on the button to visit the WordPress.org forum and to submit your support request. </p>
                <p><a href="<?php echo $this->getOption('plugin-support-url'); ?>" target="_blank" class="buttonblue">Open
                        WordPress.org Support Forum</a></p>

            </div>
        </div>

        <div class="postbox">
            <h3><span>About Comentarismo</span></h3>
            <div class="inside">
                <p>
                    <a href="<?php echo $this->addAffiliateCode('https://api.comentarismo.com/'); ?>" target="_blank">
                        The Comentarismo team
                    </a>
                    specializes in creating cutting-edge Applications & Extensions, aimed to satisfy the growing needs of website administrators,
                    designers, developers, publishers, bloggers worldwide.
                </p>
                <p>
                    Comentarismo is a comment system that replaces your WordPress comment system with your comments hosted and powered by Comentarismo.
                    Head over to the Comments admin page to set up your Comentarismo Comment System, and relax.
                </p>
                <p>
                    With Comentarismo you have for free a Seamless comments platform, with free Sentiment Analysis that works with Big Data HTTP/JSON APIs
                    and many integrations with Mobile platforms like Ionic 1, Ionic 2, Electron, Cordova, Phonegap, and many others.
                    Comentarismo was designed having in mind a Intuitive and secure platform, We take care to provide you the best experience with comments,
                </p>
                <hr/>
                <h4>Follow Comentarismo</h4>
                <a href="https://twitter.com/comentarismo" class="twitter-follow-button" data-show-count="false"
                   data-size="large" data-dnt="true">@comentarismo</a>
                <script>!function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = p + '://platform.twitter.com/widgets.js';
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, 'script', 'twitter-wjs');
                </script>
                <br/>
                <div class="g-follow" data-annotation="none" data-height="24"
                     data-href="https://plus.google.com/+Comentarismo" data-rel="publisher"></div>

                <script type="text/javascript">
                    (function () {
                        var po = document.createElement('script');
                        po.type = 'text/javascript';
                        po.async = true;
                        po.src = 'https://apis.google.com/js/platform.js';
                        var s = document.getElementsByTagName('script')[0];
                        s.parentNode.insertBefore(po, s);
                    })();
                </script>

                <div id="fb-root"></div>
                <script>( function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id))
                            return;
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=118792195142376";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk') );</script>

                <div class="fb-follow" data-href="https://www.facebook.com/comentarismo" data-layout="standard"
                     data-show-faces="false"></div>


            </div>
        </div>

        <div class="postbox">
            <h3><span>System Information</span></h3>
            <div class="inside">
                <?php echo $this->displayServerInformationTab(); ?>
            </div>
        </div>
    </div>

    <div class="right box padding">
        <div id="pages" class="pages postbox">
            <h3>
                <span>Plugin Documentation</span>
                <?php if ($this->getUserguideUrl()): ?>
                    <strong class="label-title-link"> <a class="label-title-link-class" target="_blank"
                                                         href="<?php echo $this->getUserguideUrl(); ?>">View Plugin
                            Documentation >></a></strong>
                <?php endif; ?>
            </h3>

            <div class="inside">
                <h4>Plugin User Guide</h4>
                <p>For more detailed explanations please visit the plugin <a
                        href="<?php echo $this->addAffiliateCode($this->getUserguideUrl()); ?>"
                        target="_blank">online documentation</a>.
                    We also have a <a
                        href="<?php echo $this->addAffiliateCode($this->getOption('plugin-store-url')); ?>"
                        target="_blank">detailed product page</a>
                    for this plugin which includes demos and <a
                        href="<?php echo $this->addAffiliateCode('https://www.comentarismo.com/cm-plugins-video-library/'); ?>"
                        target="_blank">video tutorials</a>.
                </p>
                <hr/>
                <h4>CSS Customizations</h4>
                <p>To easily customize the CSS using live WYSIWYG you can use <a
                        href="https://wordpress.org/plugins/yellow-pencil-visual-theme-customizer/"><strong>Visual Theme
                            Customizer</strong></a> plugin. </p>
                <?php
                $videos = $this->getOption('plugin-guide-videos');
                $height = 280;
                $width = $height * 1.78125;

                if (!empty($videos) && is_array($videos)) :
                    ?>
                    <?php foreach ($videos as $key => $video) : ?>
                    <hr/>
                    <h4>Installation Tutorial</h4>
                    <div class="label-video">
                        <iframe
                            src="https://player.vimeo.com/video/<?php echo $video['video_id']; ?>?title=0&byline=0&portrait=0"
                            width="<?php echo $width; ?>" height="<?php echo $height; ?>" frameborder="0"
                            webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
                <hr/>

            </div>
        </div>


    </div>
</section>