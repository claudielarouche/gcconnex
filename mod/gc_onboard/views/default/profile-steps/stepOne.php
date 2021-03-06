<?php
/*
 * stepOne.php - profile
 *
 * Step one asks users for basic information(job title, location, phone, mobile). Also gives user option to sync with GCdirectory.
 */
?>

<div class="col-md-8 clearfix" id="step">
    <h1>
        <?php echo elgg_echo('onboard:profile:one:title'); ?>
    </h1>
    <?php
        //dont display GEDS interface if the plugin is not active
    if(elgg_is_active_plugin('geds_sync') && elgg_view_exists('welcome-steps/geds/sync')){ ?>
    <section class="alert-gc clearfix">
        <div class="clearfix">
            <div class="pull-left mrgn-lft-0">
                <i class="fa fa-info-circle fa-3x alert-gc-icon" aria-hidden="true"></i>
            </div>
            <div style="width:80%;" class="pull-left alert-gc-msg">
                <h3>
                    <?php echo elgg_echo("onboard:geds:title"); ?>
                </h3>
                <p>
                    <?php echo elgg_echo("onboard:geds:body"); ?>
                </p>
            </div>
        </div>

        <?php
        elgg_push_context('profile-onboard');
            echo elgg_view('welcome-steps/geds/sync');
              //echo elgg_view('welcome-steps/geds_sync_button');
        ?>
    </section>
    <div id="test"></div>
    <?php } ?>
    <div class="mrgn-tp-md clearfix" id="onboard-table">

        <div class="mrgn-lft-sm mrgn-bttm-sm">
            <?php
            if(elgg_is_active_plugin('geds_sync') && elgg_view_exists('welcome-steps/geds/sync')){
                echo elgg_echo('onboard:welcome:one:noGeds');
            }?>

        </div>

        <?php
        $user = elgg_get_logged_in_user_entity();

        $fields = array('Job', 'Location', 'Phone', 'Mobile');

        foreach ($fields as $field) {

            echo '<div class="basic-profile-field-wrapper col-xs-12">'; // field wrapper for css styling

            $field = strtolower($field);

            echo '<label for="' . $field . '" class="basic-profile-label ' . $field . '-label">' . elgg_echo('gcconnex_profile:basic:' . $field) . '</label>'; // field label

            $value = $user->get($field);

            // setup the input for this field
            $params = array(
                'name' => $field,
                'id' => $field,
                'class' => 'mrgn-bttm-sm gcconnex-basic-' . $field,
                'value' => $value,
            );
            echo '<div class="basic-profile-field">'; // field wrapper for css styling

            echo elgg_view("input/text", $params);

            echo '</div>'; //close div class = basic-profile-field

            echo '</div>'; //close div class = basic-profile-field


        }
        ?>
    </div>
    <div class="mrgn-bttm-md mrgn-tp-md pull-right" id="stepOneButtons">
        <a id="skip" class="mrgn-lft-sm btn btn-default" href="#">
            <?php echo elgg_echo('onboard:skip'); ?>
        </a>
        <?php
        echo elgg_view('output/url', array(
                'href'=>'#',
                'class'=>'btn btn-primary',
                'text' => elgg_echo('onboard:welcome:next'),
                'id' => 'onboard-info',

            ));
        ?>

    </div>
    <script>
    //submit entered fields
    $('#onboard-info').on('click', function () {

        elgg.action('onboard/update-profile', {
            data: {
                section: 'details',
                job: $('.gcconnex-basic-job').val(),
                location: $('.gcconnex-basic-location').val(),
                phone: $('.gcconnex-basic-phone').val(),
                mobile: $('.gcconnex-basic-mobile').val(),
                website: $('.gcconnex-basic-website').val(),
            },
            success: function (wrapper) {
                if (wrapper.output) {
                    //alert(wrapper.output.sum);
                } else {
                    // the system prevented the action from running
                }

                //grab next step
                elgg.get('ajax/view/profile-steps/stepTwo', {
                    success: function (output) {
                        changeStepProgress(3);
                        $('#step').html(output);


                    }
                });

                //update profile strength percent
                elgg.get('ajax/view/profileStrength/info', {
                    success: function (output) {

                        $('#profileInfo').html(output);

                    }
                });
            }
        });
    });

    //skip to next step
    $('#skip').on('click', function () {
        elgg.get('ajax/view/profile-steps/stepTwo', {
            success: function (output) {
                changeStepProgress(3);
                $('#step').html(output);

                elgg.get('ajax/view/profileStrength/info', {
                    success: function (output) {

                        $('#profileInfo').html(output);
                    }
                });
            }
        });
    });
    </script>

</div>
<style>
    .alert-gc {
        border: 2px solid #047177;
        background: white;
        margin: 3px;
        padding:5px;
    }

    .alert-gc-icon {
        color: #567784;
        margin:10px;
    }

    .alert-gc-msg {
        margin-left:5px;
    }

    .alert-gc-msg h3 {
        margin-top:10px;

    }


</style>


<div class="col-md-4">
    <div class="panel panel-custom">
        <div class="panel-heading">
            <h2 class="panel-title">
                <?php echo elgg_echo('onboard:steps'); ?>
            </h2>
        </div>
        <div class="panel-body" id="step-progress">

                <?php echo elgg_view('page/elements/step_counter', array('current_step'=>1, 'total_steps'=>6, 'class' => 'mrgn-tp-md'));?>

        </div>
    </div>

            <div class="panel panel-custom">
                <div class="panel-heading">
                    <h2 class="panel-title"><?php echo elgg_echo('profile:strength'); ?></h2>
                </div>
                <div class="panel-body">
                    <div id="profileInfo">
                        <?php
            echo elgg_view('profileStrength/info', array('noJava' => 1));
                        ?>
                    </div>
                    <?php echo elgg_view('profileStrength/content');?>
                    <div id="complete" class="">
                    </div>

                </div>
            </div>
        </div>
