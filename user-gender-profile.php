<?php

/*
 * Plugin Name: Gender Plugin
 * Description: Rozszerzenie profilu użytkownika o płeć
 * Author: Patryk Kasiczak
 * Version: 0.01
 */

?>
<?php
function pk_ugp_apply_gender($avatar_image, $id_or_email, $size, $default, $alt) {
    $avatar_url = get_avatar_url($id_or_email, array('size' => $size));
    $options = get_option('pk_ugp_options');
    $avatar_side_class = 'pk-ugp-side-' . $options['side'];
    $color_man = $options['colour_man'];
    $color_woman = $options['colour_woman'];

    if(is_numeric($id_or_email)) {
        $user_id = (int)$id_or_email;
    }
    elseif(is_object($id_or_email)) {
        $user_id = $id_or_email->user_id;
    }
    if(isset($user_id) && $user_id != 0) {
        if($user_gender = get_user_meta($user_id, 'pk_ugp_gender', true)) {
            if($user_gender == 'man') {
                $avatar_style = "border-color: $color_man;";
            }
            elseif($user_gender == 'woman') {
                $avatar_style = "border-color: $color_woman;";
            }
            else {
                $avatar_class = 'pk-ugp-no-gender';
            }
        }
        else {
            $avatar_class = 'pk-ugp-no-gender';
        }
    }
    else {
        $avatar_class = 'pk-ugp-no-gender';
    }
    $avatar = "<img alt='$alt' src='$avatar_url' style='$avatar_style' class='avatar avatar-$size photo $avatar_side_class' height='$size' width='$size' />";
    return $avatar;
}

function pk_ugp_init() {
    add_filter('get_avatar', 'pk_ugp_apply_gender', 10, 5);
    wp_enqueue_style('pk-ugp-style', plugin_dir_url(__FILE__) . 'css/user-gender-profile-style.css');
}
add_action('init', 'pk_ugp_init');

function pk_ugp_create_options_page() {
    add_options_page('Gender Plugin Settings', 'Gender Plugin', 'manage_options', 'pk_ugp', 'pk_ugp_options_code');
}
add_action('admin_menu', 'pk_ugp_create_options_page');

function pk_ugp_options_code() {
    wp_enqueue_script('pk-ugp-admin-script', plugin_dir_url(__FILE__) . 'js/admin-script.js', array('jquery', 'iris'), '0.01');
    wp_enqueue_style('pk-ugp-admin-style', plugin_dir_url(__FILE__) . 'css/admin-style.css', '', '0.01');
    ?>
    <div class="wrap">
        <form action="options.php" method="post">
            <?php
            settings_fields('pk_ugp_options');
            do_settings_sections('pk_ugp')
            ?>
            <input type="submit" name="submit" class="button-primary" value="Zapisz">
        </form>
    </div>
    <?php
}

function pk_ugp_admin_init() {
    register_setting('pk_ugp_options', 'pk_ugp_options', 'pk_ugp_validate_options');
    add_settings_section('pk_ugp_settings_main', 'Ustawienia wtyczki', 'pk_ugp_settings_main_text', 'pk_ugp');
    add_settings_field('pk_ugp_gender_side', 'Podaj stronę wyświetlania paska płci', 'pk_ugp_setting_side_select', 'pk_ugp', 'pk_ugp_settings_main');
    add_settings_field('pk_ugp_man_colour', 'Wybierz kolor paska płci mężczyzny', 'pk_ugp_man_colour_input', 'pk_ugp', 'pk_ugp_settings_main');
    add_settings_field('pk_ugp_woman_colour', 'Wybierz kolor paska płci kobiety', 'pk_ugp_woman_colour_input', 'pk_ugp', 'pk_ugp_settings_main');
    add_settings_field('pk_ugp_nogender_colour', 'Wybierz kolor paska gdy brak ustawionej płci', 'pk_ugp_nogender_colour_input', 'pk_ugp', 'pk_ugp_settings_main');
}
add_action('admin_init', 'pk_ugp_admin_init');

function pk_ugp_settings_main_text() {
    echo "<p>W tym miejscu zdefiniuj ustawienia</p>";
}

function pk_ugp_setting_side_select() {
    $options = get_option('pk_ugp_options');
    $side = $options['side']
    ?>
    <select name="pk_ugp_options[side]">
        <option value="left" <?=selected($side,'left')?>>Lewo</option>
        <option value="right" <?=selected($side,'right')?>>Prawo</option>
        <option value="top" <?=selected($side,'top')?>>Góra</option>
        <option value="bottom" <?=selected($side,'bottom')?>>Dół</option>
    </select>
    <?php
}

function pk_ugp_man_colour_input() {
    $options = get_option('pk_ugp_options');
    $colour = $options['colour_man'];
    ?>
    <input type="text" name="pk_ugp_options[colour_man]" id='color-picker-man' value="<?=esc_attr($colour)?>" />
    <?php
}
function pk_ugp_woman_colour_input() {
    $options = get_option('pk_ugp_options');
    $colour = $options['colour_woman'];
    ?>
    <input type="text" name="pk_ugp_options[colour_woman]" id='color-picker-woman' value="<?=esc_attr($colour)?>" />
    <?php
}
function pk_ugp_nogender_colour_input() {
    $options = get_option('pk_ugp_options');
    $colour = $options['colour_nogender'];
    ?>
    <input type="text" name="pk_ugp_options[colour_nogender]" id='color-picker-nogender' value="<?=esc_attr($colour)?>" />
    <?php
}

function pk_ugp_validate_options($input) {
    $valid = array();
    if(preg_match('/^#[0-9A-Fa-f]{6}$/', $input['colour_man'])) {
        $valid['colour_man'] = $input['colour_man'];
    }
    else {
        $valid['colour_man'] = '#ffffff';
    }
    if(preg_match('/^#[0-9A-Fa-f]{6}$/', $input['colour_woman'])) {
        $valid['colour_woman'] = $input['colour_woman'];
    }
    else {
        $valid['colour_woman'] = '#ffffff';
    }
    if(preg_match('/^#[0-9A-Fa-f]{6}$/', $input['colour_nogender'])) {
        $valid['colour_nogender'] = $input['colour_nogender'];
    }
    else {
        $valid['colour_nogender'] = '#ffffff';
    }
    $valid_sides = array('left', 'right', 'top', 'bottom');
    if(in_array($input['side'], $valid_sides)) {
        $valid['side'] = $input['side'];
    }
    else {
        add_settings_error('pk_ugp_gender_side', 'pk_ugp_texterror', 'Sprawa zgłoszona na policję, dziękuję bardzo', 'error');
    }
    return $valid;
}

function pk_ugp_display_field($user) {
    $userID = $user->ID;
    $gender = get_user_meta($userID, 'pk_ugp_gender', true);
    ?>

    <tr>
        <th scope="row">Płeć</th>
        <td>
            <select name="pk_ugp_gender">
                <option value="none" <?=selected('none', $gender)?>>Brak</option>
                <option value="man" <?=selected('man', $gender)?>>Mężczyzna</option>
                <option value="woman" <?=selected('woman', $gender)?>>Kobieta</option>
            </select>
        </td>
    </tr>

    <?php
}
add_action('personal_options', 'pk_ugp_display_field');

function pk_ugp_update_profile($userID) {
    if(isset($_POST['pk_ugp_gender'])) {
        switch($_POST['pk_ugp_gender']) {
            case 'man':
            case 'woman':
            case 'none':
                $gender = $_POST['pk_ugp_gender'];
                break;
            default:
                $gender = 'none';
        }
        update_user_meta($userID, 'pk_ugp_gender', $gender);
    }
}
add_action('personal_options_update', 'pk_ugp_update_profile');
?>