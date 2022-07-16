<?php

if(class_exists('GoodGame_VC_Block_Base') && function_exists('vc_map'))
{
    class GoodGame_Twitch_Videos extends GoodGame_VC_Block_Base {

        public $shortcode = 'twitch_videos';
        public $classname = 'GoodGame_Twitch_Videos';    //for 5.2 compatibility.

        /*
         * Return parameters
         */
        public function getParams() {

            return array(
                'name'                => esc_html__('Twitch recent videos', 'planetshine-goodgame'),
                'description'        => esc_html__('Display up to 100 recent videos', 'planetshine-goodgame'),
                'base'                => 'twitch_videos',
                "content_element"    => true,
                'class'                => '',
                'category'            => esc_html__('GoodGame Post Blocks', 'planetshine-goodgame'),
                'params'            => array(
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "class" => "",
                        "heading" => esc_html__("Title", 'planetshine-goodgame'),
                        "param_name" => "title",
                        "value" => esc_html__("Recent videos", 'planetshine-goodgame'),
                        "description" => esc_html__("The title for videos block", 'planetshine-goodgame')
                    ),
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "class" => "",
                        "heading" => esc_html__("Username", 'planetshine-goodgame'),
                        "param_name" => "username",
                        "value" => '',
                        "description" => esc_html__("Streamer's username", 'planetshine-goodgame')
                    ),
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "class" => "",
                        "heading" => esc_html__("Count", 'planetshine-goodgame'),
                        "param_name" => "count",
                        "value" => 12,
                        "description" => esc_html__("How many videos should be shown (maximum value is 100)", 'planetshine-goodgame')
                    ),
                ),
            );
        }

        /*
         * Shortcode content
         */
        public static function shortcode($atts = array(), $content = '') {

            ob_start();
            global $post;

            extract( shortcode_atts( array(
                'title' => 'Recent videos',
                'username' => '',
                'count' => 12,
            ), $atts ) );

            $count = intval($count);
            if(!($count > 0 && $count < 101))
            {
                $count = 12;
            }

            $unique_id = uniqid();
            $twitch_id = GoodGameInstance()->get_twitch_user_id($username);
            $videos = GoodGameInstance()->get_twitch_data('videos', $twitch_id);

            if($videos)
            {?>
                <div class="title-default">
                    <div><span><?php echo esc_html($title); ?></span></div>
                    <a href="<?php echo esc_url('https://www.twitch.tv/' . sanitize_user($username) . '/videos/all'); ?>" class="more"><span><?php echo esc_html__('View more', 'planetshine-goodgame') ?></span></a>
                </div>


                <div class="twitch-recent-streams">
                    <?php
                    if(intval($videos['_total']) > 0) {
                        $i = 0;
                        $i_per_row = 4;

                        foreach ($videos['videos'] as $video)
                        {
                            $i++;
                            $time = NULL;
                            if(!empty($video['recorded_at']))
                            {
                                $time = human_time_diff(strtotime($video['recorded_at']), current_time('timestamp'));
                            }

                            if($i % $i_per_row == 1)
                            {
                                echo '<div class="row">';
                            }?>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="stream">
                                    <a href="<?php if(!empty($video['url'])) { echo esc_url($video['url']); } ?>" class="thumb">
                                        <span class="btn-circle btn-twitch"></span>
                                        <?php if(!empty($video['preview']['large'])) { echo '<img src="' . esc_url($video['preview']['large']) . '">'; } ?>
                                    </a>
                                    <div class="text">
                                        <h4><a href="<?php if(!empty($video['url'])){ echo esc_url($video['url']); } ?>"><?php if(!empty($video['title'])){ echo esc_html($video['title']); } ?></a></h4>
                                        <div class="legend">
                                            <?php if(!empty($time)) { echo '<span class="time">' . esc_html($time) . ' ' . esc_html__(' ago', 'planetshine-goodgame' ) . '</span>'; } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            if($i % $i_per_row == 0)
                            {
                                echo '</div>';
                            }

                            if($i == $count)
                            {
                                break;
                            }
                        }

                        if($i % $i_per_row != 0)
                        {
                            echo '</div>';
                        }
                    }
                    else
                    {
                        echo '<p>' . esc_html__('No recent videos were found!', 'planetshine-goodgame') . '</p>';
                    }
                    ?>
                </div>
                <?php
            }
            else
            {
                echo '<p>' . esc_html__('Something went wrong. Make sure "Planetshine GoodGame Theme Extension" plugin is installed. Check your Twitch client ID and streamer\'s username.', 'planetshine-goodgame') . '</p>';
            }



            $return = ob_get_contents();
            ob_end_clean();
            wp_reset_postdata();
            return $return;
        }

    }

    //Create instance of VC block
    new GoodGame_Twitch_Videos();

}
